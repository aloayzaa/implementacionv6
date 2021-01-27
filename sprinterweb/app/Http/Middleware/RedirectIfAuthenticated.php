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
            $rol = Auth::user()->rol_id;
            if ($rol == 3 || $rol == 1) {
                return redirect()->route('home');
            } elseif($rol == 2) {
                return redirect()->route('enter');
            }
        }

        return $next($request);
    }
}
