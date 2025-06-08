<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckRoleClient
{
    public function handle($request, Closure $next, ...$roles)
    {
        if (!Auth::check()) {
            return redirect('/');
        }

        $userRole = Auth::user()->role;

        if (!in_array($userRole, $roles)) {
            return redirect('/');
        }

        return $next($request);
    }
}
