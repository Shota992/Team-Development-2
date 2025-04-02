<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class MentorMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        // 例: ログインしていなければリダイレクト
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'ログインが必要です');
        }

        // 例: 特定の権限を持っているかを確認
        // if (!auth()->user()->hasRole('mentor')) {
        //     abort(403, '権限がありません');
        // }

        return $next($request);
    }
}
