<?php

namespace App\Http\Middleware\Panel;

use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectAuthenticatedUser
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
            if ($request->ajax()) {
                return response()->json(['location'=>'/admin']);
            } else {
                return redirect()->guest('/admin');
            }
        }

        return $next($request);
    }
}
