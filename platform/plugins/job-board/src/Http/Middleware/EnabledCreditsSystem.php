<?php

namespace Botble\JobBoard\Http\Middleware;

use Botble\JobBoard\Facades\JobBoardHelper;
use Closure;
use Illuminate\Http\Request;

class EnabledCreditsSystem
{
    public function handle(Request $request, Closure $next)
    {
        abort_unless(JobBoardHelper::isEnabledCreditsSystem(), 404);

        return $next($request);
    }
}
