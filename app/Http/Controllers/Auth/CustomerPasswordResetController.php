<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Str;

class CustomerPasswordResetController extends Controller
{
    /**
     * Display the password reset request form.
     */
    public function showLinkRequestForm()
    {
        return view('auth.customer.forgot-password');
    }

    /**
     * Handle password reset link request.
     */
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        // Send password reset link
        $status = Password::broker('customers')->sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
            ? back()->with(['status' => __($status)])
            : back()->withErrors(['email' => __($status)]);
    }

    /**
     * Display the password reset form.
     */
    public function showResetForm(Request $request, $token)
    {
        return view('auth.customer.reset-password', [
            'token' => $token,
            'email' => $request->email
        ]);
    }

    /**
     * Handle password reset.
     */
    public function reset(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);

        $status = Password::broker('customers')->reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));

                $user->save();

                event(new PasswordReset($user));
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('customer.login')->with('status', __($status))
            : back()->withErrors(['email' => [__($status)]]);
    }
}
