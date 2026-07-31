<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UseAdminGuard
{
    public function handle(Request $request, Closure $next)
    {
        Auth::shouldUse('admin');

        return $next($request);
    }
}
