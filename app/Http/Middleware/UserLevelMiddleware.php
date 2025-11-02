<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UserLevelMiddleware
{
    private const LEVEL_HIERARCHY = [
        'free' => 1,
        'premium1' => 2,
        'premium2' => 3,
    ];

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $requiredLevel): Response
    {
        if (!$request->user()) {
            return redirect('login');
        }

        $userLevel = self::LEVEL_HIERARCHY[$request->user()->user_level] ?? 0;
        $required = self::LEVEL_HIERARCHY[$requiredLevel] ?? 999;

        if ($userLevel < $required) {
            return redirect()->back()->with('error', 'この機能を使用するにはアップグレードが必要です。');
        }

        return $next($request);
    }
}
