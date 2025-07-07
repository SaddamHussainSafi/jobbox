<?php

namespace Botble\JobBoard\Http\Middleware;

use Botble\Theme\Facades\AdminBar;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

class RedirectIfNotAccount
{
    public function handle(Request $request, Closure $next, $type = null)
    {
        if (! Auth::guard('account')->check()) {
            if ($request->ajax() || $request->wantsJson()) {
                return response('Unauthorized.', 401);
            }

            return redirect()->guest(route('public.account.login'));
        }

        if (
            ! Auth::guard('account')->user()->type->getKey() &&
            ! in_array(Route::currentRouteName(), ['public.account.settings', 'public.account.post.settings',  'public.account.logout'])
        ) {
            return redirect(route('public.account.settings'));
        }

        if ($type && Auth::guard('account')->user()->type != $type) {
            return redirect(route('public.index'));
        }

        AdminBar::setIsDisplay(false);

        return $next($request);
    }
}
