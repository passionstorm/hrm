<?php

namespace App\Http\Middleware;

use App\Constants;
use Closure;
use Illuminate\Support\Facades\Auth;


class AdminRole
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
        if (Auth::check() && Auth::user()->role == Constants::ROLE_ADMIN) {
            return $next($request);
        }

        abort(404);
    }
}
