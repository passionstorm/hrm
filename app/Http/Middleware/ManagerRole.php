<?php

namespace App\Http\Middleware;

use App\Constants;
use Closure;
use Illuminate\Support\Facades\Auth;


class ManagerRole
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $managerRoles = [Constants::ROLE_ADMIN => true, Constants::ROLE_STAFF => true];
        if (Auth::check() && isset($managerRoles[Auth::user()->role])) {
            return $next($request);
        }

        abort(404);
    }
}
