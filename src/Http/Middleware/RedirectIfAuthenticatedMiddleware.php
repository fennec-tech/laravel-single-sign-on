<?php

namespace FennecTech\LaravelSingleSignOn\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use FennecTech\LaravelSingleSignOn\Http\Controllers\SingleSignOnController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticatedMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  ...$guards
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$guards)
    {
        $guards = empty($guards) ? [null] : $guards;
        $is_logged_in = SingleSignOnController::check_accounts_login($request);
        if (!$is_logged_in) {
            $intendedUrl = redirect()->intended()->getTargetUrl();
            $redirectUrl = env('ACCOUNTS_URL') . '/login?redirect=' . urlencode($intendedUrl);
            // Redirect the user to the Accounts app
            return redirect($redirectUrl);
        }
        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                return redirect(RouteServiceProvider::HOME);
            }
        }

        return $next($request);
    }
}
