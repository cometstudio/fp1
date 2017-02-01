<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectUnauthenticatedUser
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
        if (!Auth::guard($guard)->check()) {

            $url = url()->route('login', [], false);

            if ($request->ajax()) {
                return response()->json(['location'=>$url]);
            } else {
                return redirect()->guest($url);
            }
        }

        return $next($request);
    }
}
