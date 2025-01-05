<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  mixed  ...$roles
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        // Check if user is authenticated and has a role
        if ($request->user() && in_array($request->user()->role, $roles)) {
            return $next($request);
        }

        // Redirect if the user's role does not match
        return redirect('/');  // Or you can specify a custom route or view
    }


}
