<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class CustomerAuthController extends Controller
{
    /**
     * Display the customer login form.
     */
    public function showLoginForm()
    {
        return view('auth.customer.login');
    }

    /**
     * Handle customer login request.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::guard('customer')->attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            return redirect()->intended(route('stores.index'));
        }

        throw ValidationException::withMessages([
            'email' => 'ログイン情報が正しくありません。',
        ]);
    }

    /**
     * Handle customer logout request.
     */
    public function logout(Request $request)
    {
        Auth::guard('customer')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}
