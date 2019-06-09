<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;


class PreventMem
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(Auth::check()){
            if(Auth::user()->role != 0){
                return $next($request);
            }else{
                return redirect('mempage')->with('fail', 'You can not access this page');
            }
        }else{
            return redirect('login')->with('fail', 'You must login first to access that page');
        }
    }
}
