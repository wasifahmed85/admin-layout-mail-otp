<?php

namespace App\Http\Controllers\Backend\Admin\Auth;

use App\Http\Controllers\Controller;
use App\Mail\AdminOtpMail;
use App\Models\Admin;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\View\View;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Mail;

class PasswordResetLinkController extends Controller
{
    /**
     * Display the password reset link request view.
     */
    public function create(): View
    {
        return view('frontend.auth.admin.forgot-password');
    }

    /**
     * Handle an incoming password reset link request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email', 'exists:admins,email'],
        ]);

        $admin = Admin::where('email', $request->email)->first();

        if (!$admin) {
            session()->flash('error', 'Invalid email.');
            return redirect()->back();
        }

        // Apply rate limiting for sending forgot password OTP (important for security)
        $throttleKey = 'forgot_password_otp_send_' . $admin->id; // Use admin ID for throttle key

        if (RateLimiter::tooManyAttempts($throttleKey, $perMinute = 1)) { // 1 attempt per minute
            $secondsRemaining = RateLimiter::availableIn($throttleKey);
            session()->flash('error', "Too many attempts to send OTP. Please try again in {$secondsRemaining} seconds.");
            return redirect()->back();
        }
        RateLimiter::hit($throttleKey, $decayMinutes = 1); // Hit the throttle for this attempt

        // Generate and store OTP
        $otp = random_int(100000, 999999);
        $admin->email_otp = $otp;
        $admin->email_otp_expires_at = now()->addMinutes(2);
        $admin->last_otp_sent_at = now();
        $admin->save();

        // Send OTP email
        Mail::to($admin->email)->send(new AdminOtpMail($admin, $admin->email_otp));

        // Store the admin's ID in the session for OtpVerificationController
        Session::put('otp_verification_admin_id', $admin->id);
        // Redirect to OTP verification page for forgot password flow
        return redirect()->route('admin.otp-verification', ['forgot' => true]);

        // // We will send the password reset link to this admin. Once we have attempted
        // // to send the link, we will examine the response then see the message we
        // // need to show to the admin. Finally, we'll send out a proper response.
        // $status = Password::sendResetLink(
        //     $request->only('email')
        // );

        // return $status == Password::RESET_LINK_SENT
        //             ? back()->with('status', __($status))
        //             : back()->withInput($request->only('email'))
        //                 ->withErrors(['email' => __($status)]);
    }
}
