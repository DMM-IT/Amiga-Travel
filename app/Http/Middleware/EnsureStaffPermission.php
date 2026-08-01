<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureStaffPermission
{
    public function handle(Request $request, Closure $next, string $permission): mixed
    {
        $user = Auth::guard('admin')->user() ?? Auth::user();

        if (! $user || ! $user->isStaff()) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Unauthorized.'], 403);
            }

            return redirect()->route('dashboard')->with('error', 'You do not have access to that feature.');
        }

        if ($user->isSuperAdmin() || $user->hasAdminPermission($permission)) {
            return $next($request);
        }

        if ($request->expectsJson()) {
            return response()->json(['message' => 'You do not have permission to access this feature.'], 403);
        }

        return redirect()->route('dashboard')->with('error', 'You do not have access to that feature.');
    }
}
