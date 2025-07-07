<?php

namespace Botble\ACL\Http\Middleware;

use Closure;
use Illuminate\Auth\Middleware\Authenticate as BaseAuthenticate;

class Authenticate extends BaseAuthenticate
{
    protected array $guards;

    public function handle($request, Closure $next, ...$guards)
    {
        $this->guards = $guards;

        $this->authenticate($request, $guards);

        if (! $guards) {
            $route = $request->route();
            $flag = $route->getAction('permission');
            if ($flag === null) {
                $flag = $route->getName();
            }

            $flag = preg_replace('/.store$/', '.create', $flag);
            $flag = preg_replace('/.update$/', '.edit', $flag);

            if ($flag && ! $request->user()->hasAnyPermission((array) $flag)) {
                if ($request->expectsJson()) {
                    return response()->json(['message' => 'Unauthenticated.'], 401);
                }

                return redirect()->route('dashboard.index');
            }
        }

        return $next($request);
    }

    protected function redirectTo($request): ?string
    {
        if ($this->guards || $request->expectsJson()) {
            $redirectCallback = apply_filters('cms_unauthenticated_redirect_to', null, $request);

            if ($redirectCallback) {
                return $redirectCallback;
            }

            return parent::redirectTo($request);
        }

        return route('access.login');
    }
}
