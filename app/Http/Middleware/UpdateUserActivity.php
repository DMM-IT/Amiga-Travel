<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UpdateUserActivity
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::guard('api')->check()) {
            $user = Auth::guard('api')->user();
            $latestLogin = $user->loginHistories()->latest()->first();
            
            // Touch the latest login history to act as a 'last seen' timestamp,
            // but only if it hasn't been touched in the last 3 minutes to avoid excessive queries.
            if ($latestLogin && $latestLogin->updated_at < now()->subMinutes(3)) {
                $latestLogin->touch();
            }
        }

        return $next($request);
    }
}
