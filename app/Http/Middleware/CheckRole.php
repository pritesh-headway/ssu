<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
class CheckRole
{
   /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, ...$roles)
    {
        // if (Auth::user()->role_name != $role) {
         if (!in_array(Auth::user()->role_name, $roles)) {
            abort(401, 'This action is unauthorized.');
        }
        return $next($request);
    }
}