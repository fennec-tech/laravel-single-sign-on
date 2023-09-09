<?php

namespace FennecTech\LaravelSingleSignOn\Http\Middleware;

use Closure;
use FennecTech\LaravelSingleSignOn\Http\Controllers\SingleSignOnController;
use Illuminate\Http\Request;

class LaravelSingleSignOnMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, $guard = null)
    {
        $is_logged_in = SingleSignOnController::check_accounts_login($request);
        if ($is_logged_in) {
            auth()->guard($guard)->check();
        }

        return $next($request);
    }
}
