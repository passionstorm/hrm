<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class Role
{
    /**
     * @param $request
     * @param Closure $next
     * @param mixed ...$roles
     * @return mixed
     */
    public function handle($request, Closure $next, ...$roles)
    {
        if( !Auth::check() ){
            return redirect('login')->with('fail', 'You must login!');
        }
        if (Auth::check() && in_array(Auth::user()->role, $roles)) {
            return $next($request);
        }
        abort(404);
    }
}
