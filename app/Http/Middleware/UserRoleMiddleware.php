<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
class UserRoleMiddleware
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
        if(Auth::user()->UserRole != "Administratorius")
          return response()->json(['message' => 'Neturite teisių atlikti šiam veiksmui.'], 403);
        else
          return $next($request);
    }
}
