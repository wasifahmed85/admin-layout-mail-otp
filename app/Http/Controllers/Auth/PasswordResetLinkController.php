<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\UserOtpMail;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Session;
use Illuminate\View\View;

class PasswordResetLinkController extends Controller
{
    /**
     * Display the password reset link request view.
     */
    public function create(): View
    {
        return view('frontend.auth.user.forgot-password');
    }

    /**
     * Handle an incoming password reset link request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email', 'exists:users,email'],
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            session()->flash('error', 'Email not found.');
            return redirect()->back();
        }

        // Apply rate limiting for sending forgot password OTP (important for security)
        $throttleKey = 'forgot_password_otp_send_' . $user->id; // Use user ID for throttle key

        if (RateLimiter::tooManyAttempts($throttleKey, $perMinute = 1)) { // 1 attempt per minute
            $secondsRemaining = RateLimiter::availableIn($throttleKey);
            session()->flash('error', "Too many attempts to send OTP. Please try again in {$secondsRemaining} seconds.");
            return redirect()->back();
        }
        RateLimiter::hit($throttleKey, $decayMinutes = 1); // Hit the throttle for this attempt

        // Generate and store OTP
        $otp = random_int(100000, 999999);
        $user->email_otp = $otp;
        $user->email_otp_expires_at = now()->addMinutes(2);
        $user->last_otp_sent_at = now();
        $user->save();

        // Send OTP email
        Mail::to($user->email)->send(new UserOtpMail($user, $user->email_otp));

        // Store the user's ID in the session for OtpVerificationController
        Session::put('otp_verification_user_id', $user->id);
        // Redirect to OTP verification page for forgot password flow
        return redirect()->route('otp-verification', ['forgot' => true]);

        // // We will send the password reset link to this user. Once we have attempted
        // // to send the link, we will examine the response then see the message we
        // // need to show to the user. Finally, we'll send out a proper response.
        // $status = Password::sendResetLink(
        //     $request->only('email')
        // );

        // return $status == Password::RESET_LINK_SENT
        //     ? back()->with('status', __($status))
        //     : back()->withInput($request->only('email'))
        //     ->withErrors(['email' => __($status)]);
    }
}
