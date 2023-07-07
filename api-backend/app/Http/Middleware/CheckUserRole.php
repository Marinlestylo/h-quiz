<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckUserRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $role): Response
    {
        if(!$request->user()) {
			return abort(401, 'Unauthenticated.');
		}

		if($request->user()->hasRole($role) || !$role){
            return $next($request);
        }

        return abort(403, 'Unauthorized.');
    }
}
