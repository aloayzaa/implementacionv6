<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param Closure $next
     * @param $roles
     * @return mixed
     */
    public function handle($request, Closure $next, $roles)
    {
        $roles = array_slice(func_get_args(), 2);

        if (!Auth::check()){
            return redirect()->route('enter');
        }

        $user = Auth::user();

        foreach($roles as $role) {
            if($user->hasRole($role)){
                return $next($request);
            } elseif($role == '*') {
                return $next($request);
            }
        }

        return redirect()->route('logout');
    }
}
