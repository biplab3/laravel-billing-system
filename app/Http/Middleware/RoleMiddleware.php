<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    // public function handle(Request $request, Closure $next): Response
    // {
    //     if (!Auth::check()) {
    //         return redirect('/login');
    //     }

    //     if (!in_array(Auth::user()->role, $roles)) {
    //         abort(403, 'Unauthorized Access');
    //     }
    //     return $next($request);
    // }
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!Auth::check()) {
            return redirect('/login');
        }

        if (!in_array(Auth::user()->role, $roles)) {
            abort(403, 'Unauthorized Access');
        }

        return $next($request);
    }
}
