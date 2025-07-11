<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user(); // Get the authenticated user from the request (works with Sanctum)
        // Check if the user is authenticated and has the 'admin' role
        if($user && $user->role === 'admin') {
            return $next($request);
        }
        abort(403, 'Unauthorized');
    }
}
