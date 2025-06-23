<?php

namespace App\Http\Controllers\Backend\Admin\Auth;

use App\Http\Controllers\Controller;
use App\Mail\AdminOtpMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, DB, Log, Mail, RateLimiter};
use App\Models\Admin;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Password;
use Throwable;
use Carbon\Carbon;

class OtpVerificationController extends Controller
{
    protected const OTP_EXPIRY_MINUTES = 2;
    protected const MAX_OTP_ATTEMPTS = 5;
    protected const OTP_RESEND_THROTTLE = 1;

    public function otp(Request $request)
    {
        $isForgot = $request->filled('forgot');
        $admin = $this->resolveAdmin($request);

        if (!$admin) {
            return $this->handleMissingAdmin($isForgot);
        }

        if ($admin->hasVerifiedEmail() && !$isForgot) {
            return redirect()->route('admin.dashboard')->with('info', 'Email already verified.');
        }

        $this->conditionallySendOtp($admin, $isForgot);

        return view('frontend.auth.admin.otp-verification', [
            'isForgot' => $isForgot,
            'lastOtpSentAt' => $admin->last_otp_sent_at?->timestamp,
            'email' => $admin->email,
            'otpExpiryMinutes' => self::OTP_EXPIRY_MINUTES
        ]);
    }

    public function verify(Request $request)
    {
        $request->validate(['otp' => 'required|integer|digits:6']);
        $isForgot = $request->filled('forgot');

        return DB::transaction(function () use ($request, $isForgot) {
            $admin = $this->resolveAdmin($request);

            if (!$admin) {
                throw ValidationException::withMessages([
                    'otp' => $this->adminNotFoundMessage($isForgot)
                ]);
            }

            $this->validateOtpAttempts($admin);
            $this->validateOtpCode($admin, $request->otp);
            $this->clearOtp($admin);

            if (!$isForgot) {
                $admin->markEmailAsVerified();
                Auth::guard('admin')->login($admin);
                return redirect()->route('admin.dashboard')->with('success', 'Email verified successfully!');
            }

            return redirect()->route('admin.password.reset', [
                'token' => $this->createPasswordResetToken($admin),
                'email' => $admin->email
            ])->with('success', 'OTP verified. Please reset your password.');
        });
    }

    public function resend(Request $request)
    {
        $admin = $this->resolveAdmin($request);
        $isForgot = $request->filled('forgot');

        if (!$admin) {
            return response()->json([
                'success' => false,
                'message' => $this->adminNotFoundMessage($isForgot),
            ], 401);
        }

        $throttleKey = "otp_resend_{$admin->id}";

        if (RateLimiter::tooManyAttempts($throttleKey, self::OTP_RESEND_THROTTLE)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            return response()->json([
                'success' => false,
                'message' => "Please wait {$seconds} seconds before resending.",
                'retry_after' => $seconds
            ], 429);
        }

        RateLimiter::hit($throttleKey);
        $this->sendNewOtp($admin);

        return response()->json([
            'success' => true,
            'message' => 'New OTP sent to your email.',
            'last_sent_at' => $admin->last_otp_sent_at->timestamp,
            'expires_at' => $admin->email_otp_expires_at
        ]);
    }



    /**
     * Resolve the admin for OTP verification based on the given request.
     * If the request is for forgot password, get the admin from session.
     * Otherwise, get the admin from the authenticated web guard.
     *
     * @param \Illuminate\Http\Request $request
     * @return \App\Models\Admin|null
     */
    protected function resolveAdmin(Request $request): ?Admin
    {
        if ($request->filled('forgot')) {
            return Admin::withoutGlobalScopes()->find(session('otp_verification_admin_id'));
        }
        return Auth::guard('admin')->user();
    }

    protected function conditionallySendOtp(Admin $admin, bool $isForgot): void
    {
        $needsNewOtp = !$admin->last_otp_sent_at ||
            $admin->email_otp_expires_at?->isPast() ||
            ($isForgot && !$admin->last_otp_sent_at);

        if ($needsNewOtp) {
            $this->sendNewOtp($admin);
        }
    }

    protected function sendNewOtp(Admin $admin): void
    {
        $admin->update([
            'email_otp' => rand(100000, 999999),
            'email_otp_expires_at' => now()->addMinutes(self::OTP_EXPIRY_MINUTES),
            'last_otp_sent_at' => now()
        ]);

        try {
            Mail::to($admin->email)->send(new AdminOtpMail($admin, $admin->email_otp));
            Log::info('OTP email sent', ['admin_id' => $admin->id]);
        } catch (\Exception $e) {
            Log::error('Failed to send OTP email', [
                'admin_id' => $admin->id,
                'error' => $e->getMessage()
            ]);
        }
    }

    protected function validateOtpAttempts(Admin $admin): void
    {
        $key = "otp_attempts_{$admin->id}";

        if (RateLimiter::tooManyAttempts($key, self::MAX_OTP_ATTEMPTS)) {
            throw ValidationException::withMessages([
                'otp' => 'Too many attempts. Please try again in ' . RateLimiter::availableIn($key) . ' seconds.'
            ]);
        }
    }

    protected function validateOtpCode(Admin $admin, string $otp): void
    {
        if ((string)$admin->email_otp !== $otp) {
            throw ValidationException::withMessages(['otp' => 'Invalid verification code.']);
        }

        if (now()->gt($admin->email_otp_expires_at)) {
            throw ValidationException::withMessages(['otp' => 'Verification code has expired.']);
        }
    }


    protected function clearOtp(Admin $admin): void
    {
        $admin->update([
            'email_otp' => null,
            'email_otp_expires_at' => null,
            'last_otp_sent_at' => null
        ]);
    }



    protected function createPasswordResetToken(Admin $admin): string
    {
        $token = Password::createToken($admin);
        session()->forget('otp_verification_admin_id');
        return $token;
    }

    protected function handleMissingAdmin(bool $isForgot)
    {
        return $isForgot
            ? redirect()->route('admin.password.request')->withErrors(['email' => 'Please initiate password reset first.'])
            : redirect()->route('admin.login');
    }

    protected function adminNotFoundMessage(bool $isForgot): string
    {
        return $isForgot
            ? 'Session expired. Please re-initiate password reset.'
            : 'Authentication error. Please log in again.';
    }
}
