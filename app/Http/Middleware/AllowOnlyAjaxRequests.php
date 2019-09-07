<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;


class AllowOnlyAjaxRequests
{
    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @param  Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if($request->ajax()){
            return $next($request);
        }
        abort(404);
    }
}
