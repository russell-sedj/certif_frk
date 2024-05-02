<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUsersHasRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if ($request->user() && in_array($request->user()->role, $roles)) {
            return $next($request);
        }
    
        echo '<script>alert("Vous n\'êtes pas autorisé à accéder à cette page.");</script>';
        echo '<script>window.history.back();</script>';
    
    }
}
