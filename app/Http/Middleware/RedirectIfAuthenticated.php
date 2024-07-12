<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @param  string|null  ...$guards
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, ...$guards)
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check() && Auth::User()->role == 'admin') {
                return Inertia::location(route('admin.dashboard'));
            } elseif (Auth::guard($guard)->check() && Auth::User()->role == 'employer') {
                return Inertia::location(route('employer.dashboard'));
            } elseif (Auth::guard($guard)->check() && Auth::User()->role == 'user') {
                return Inertia::location(route('user.dashboard'));
            }
        }

        return $next($request);
    }
}
