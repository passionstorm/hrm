<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Authenticate extends Middleware
{
    /**
     * @param Request $request
     * @param Closure $next
     * @param array ...$roles
     * @return RedirectResponse
     */
    public function handle($request, Closure $next, ...$roles)
    {
        if (!Auth::check()) {
            $request->session()->regenerate();
            $uri = $request->route()->uri();
            if ($uri && $uri !== 'login') {
                $request->session()->put('redirect', $uri);
            }

            return redirect('login')->with('fail', 'You must login!');
        }

        if (empty($roles)) {
            return $next($request);
        }

        if (in_array(Auth::user()->role, $roles)) {
            return $next($request);
        }

        abort(404);
    }
}
