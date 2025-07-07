<?php

namespace Botble\JobBoard\Http\Middleware;

use Botble\Base\Supports\Language;
use Botble\JobBoard\Models\Account;
use Closure;
use Illuminate\Http\Request;

class LocaleMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        /**
         * @var Account $account
         */
        $account = auth('account')->user();

        if ($account->isJobSeeker()) {
            return $next($request);
        }

        $currentLocale = Language::getDefaultLanguage();

        $userLocale = $account->getMetaData('locale', true);

        if ($userLocale && array_key_exists($userLocale, $availableLocales = Language::getAvailableLocales())) {
            $currentLocale = $availableLocales[$userLocale];
        }

        if ($currentLocale && isset($currentLocale['locale'])) {
            app()->setLocale($currentLocale['locale']);
            $request->setLocale($currentLocale['locale']);
            $request->session()->put('locale_direction', $currentLocale['is_rtl']);
        }

        return $next($request);
    }
}
