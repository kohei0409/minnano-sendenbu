<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckActiveMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->user()) {
            return redirect('login');
        }

        if ($request->user()->status === 'suspended') {
            Auth::logout();
            return redirect('login')->with('error', 'アカウントが停止されています。');
        }

        if ($request->user()->status === 'pending') {
            Auth::logout();
            return redirect('login')->with('error', 'アカウントが承認待ちです。');
        }

        return $next($request);
    }
}
