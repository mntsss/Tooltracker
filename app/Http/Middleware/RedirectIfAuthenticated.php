<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (Auth::guard($guard)->check()) {
        //  if(Auth::user()->role == "Vadybininkas")
            return redirect('/active');
          /*if(Auth::user()->role == "Perziura")
              return redirect('/viewer');
          if(Auth::user()->role == "Tiekejas")
                return redirect('/supplier');*/
        }

        return $next($request);
    }
}
