<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\UserOtpMail;
use App\Models\AuthBaseModel;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('frontend.auth.user.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $response = DB::transaction(function () use ($request) {
            $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
                'password' => ['required', 'confirmed', Rules\Password::defaults()],
            ]);
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'status' => AuthBaseModel::STATUS_ACTIVE,
                'password' => Hash::make($request->password),
                'email_otp' => random_int(100000, 999999),
                'email_otp_expires_at' => now()->addMinutes(2),
                'last_otp_sent_at' => now()
            ]);
            event(new Registered($user));

            Auth::login($user);

            Mail::to($user->email)->send(new UserOtpMail($user, $user->email_otp));
            // Redirect to the OTP verification page.
            // Since the user is now authenticated, OtpVerificationController@otp
            return redirect()->route('otp-verification');
        });
        return $response;
    }
}
