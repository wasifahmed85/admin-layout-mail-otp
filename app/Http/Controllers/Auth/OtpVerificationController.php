<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, DB, Log, Mail, RateLimiter};
use App\Mail\UserOtpMail;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Password;


class OtpVerificationController extends Controller
{
    protected const OTP_EXPIRY_MINUTES = 2;
    protected const MAX_OTP_ATTEMPTS = 5;
    protected const OTP_RESEND_THROTTLE = 1;

    public function otp(Request $request)
    {
        $isForgot = $request->filled('forgot');
        $user = $this->resolveUser($request);

        if (!$user) {
            return $this->handleMissingUser($isForgot);
        }

        if ($user->hasVerifiedEmail() && !$isForgot) {
            return redirect()->route('user.dashboard')->with('info', 'Email already verified.');
        }

        $this->conditionallySendOtp($user, $isForgot);

        return view('frontend.auth.user.otp-verification', [
            'isForgot' => $isForgot,
            'lastOtpSentAt' => $user->last_otp_sent_at?->timestamp,
            'email' => $user->email,
            'otpExpiryMinutes' => self::OTP_EXPIRY_MINUTES
        ]);
    }

    public function verify(Request $request)
    {
        $request->validate(['otp' => 'required|integer|digits:6']);
        $isForgot = $request->filled('forgot');

        return DB::transaction(function () use ($request, $isForgot) {
            $user = $this->resolveUser($request);

            if (!$user) {
                throw ValidationException::withMessages([
                    'otp' => $this->userNotFoundMessage($isForgot)
                ]);
            }

            $this->validateOtpAttempts($user);
            $this->validateOtpCode($user, $request->otp);
            $this->clearOtp($user);

            if (!$isForgot) {
                $user->markEmailAsVerified();
                Auth::guard('web')->login($user);
                return redirect()->route('user.dashboard')->with('success', 'Email verified successfully!');
            }

            return redirect()->route('password.reset', [
                'token' => $this->createPasswordResetToken($user),
                'email' => $user->email
            ])->with('success', 'OTP verified. Please reset your password.');
        });
    }

    public function resend(Request $request)
    {
        $user = $this->resolveUser($request);
        $isForgot = $request->filled('forgot');

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => $this->userNotFoundMessage($isForgot),
            ], 401);
        }

        $throttleKey = "otp_resend_{$user->id}";

        if (RateLimiter::tooManyAttempts($throttleKey, self::OTP_RESEND_THROTTLE)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            return response()->json([
                'success' => false,
                'message' => "Please wait {$seconds} seconds before resending.",
                'retry_after' => $seconds
            ], 429);
        }

        RateLimiter::hit($throttleKey);
        $this->sendNewOtp($user);

        return response()->json([
            'success' => true,
            'message' => 'New OTP sent to your email.',
            'last_sent_at' => $user->last_otp_sent_at->timestamp,
            'expires_at' => $user->email_otp_expires_at
        ]);
    }

    protected function resolveUser(Request $request): ?User
    {
        if ($request->filled('forgot')) {
            return User::withoutGlobalScopes()->find(session('otp_verification_user_id'));
        }
        return Auth::guard('web')->user();
    }

    protected function conditionallySendOtp(User $user, bool $isForgot): void
    {
        $needsNewOtp = !$user->last_otp_sent_at ||
            $user->email_otp_expires_at?->isPast() ||
            ($isForgot && !$user->last_otp_sent_at);

        if ($needsNewOtp) {
            $this->sendNewOtp($user);
        }
    }

    protected function sendNewOtp(User $user): void
    {
        $user->update([
            'email_otp' => rand(100000, 999999),
            'email_otp_expires_at' => now()->addMinutes(self::OTP_EXPIRY_MINUTES),
            'last_otp_sent_at' => now()
        ]);

        try {
            Mail::to($user->email)->send(new UserOtpMail($user, $user->email_otp));
            Log::info('OTP email sent', ['user_id' => $user->id]);
        } catch (\Exception $e) {
            Log::error('Failed to send OTP email', [
                'user_id' => $user->id,
                'error' => $e->getMessage()
            ]);
        }
    }

    protected function validateOtpAttempts(User $user): void
    {
        $key = "otp_attempts_{$user->id}";

        if (RateLimiter::tooManyAttempts($key, self::MAX_OTP_ATTEMPTS)) {
            throw ValidationException::withMessages([
                'otp' => 'Too many attempts. Please try again in ' . RateLimiter::availableIn($key) . ' seconds.'
            ]);
        }
    }

    protected function validateOtpCode(User $user, string $otp): void
    {
        if ((string) $user->email_otp !== $otp) {
            throw ValidationException::withMessages(['otp' => 'Invalid verification code.']);
        }

        if (now()->gt($user->email_otp_expires_at)) {
            throw ValidationException::withMessages(['otp' => 'Verification code has expired.']);
        }
    }

    protected function clearOtp(User $user): void
    {
        $user->update([
            'email_otp' => null,
            'email_otp_expires_at' => null,
            'last_otp_sent_at' => null
        ]);
    }

    protected function createPasswordResetToken(User $user): string
    {
        $token = Password::createToken($user);
        session()->forget('otp_verification_user_id');
        return $token;
    }

    protected function handleMissingUser(bool $isForgot)
    {
        return $isForgot
            ? redirect()->route('password.request')->withErrors(['email' => 'Please initiate password reset first.'])
            : redirect()->route('login');
    }

    protected function userNotFoundMessage(bool $isForgot): string
    {
        return $isForgot
            ? 'Session expired. Please re-initiate password reset.'
            : 'Authentication error. Please log in again.';
    }
}
