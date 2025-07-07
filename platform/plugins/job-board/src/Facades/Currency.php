<?php

namespace Botble\JobBoard\Facades;

use Botble\JobBoard\Supports\CurrencySupport;
use Illuminate\Support\Facades\Facade;

/**
 * @method static void setApplicationCurrency(\Botble\JobBoard\Models\Currency $currency)
 * @method static \Botble\JobBoard\Models\Currency|null getApplicationCurrency()
 * @method static \Botble\JobBoard\Models\Currency|null getDefaultCurrency()
 * @method static \Illuminate\Support\Collection currencies()
 * @method static string|null detectedCurrencyCode()
 * @method static array countryCurrencies()
 * @method static array currencyCodes()
 *
 * @see \Botble\JobBoard\Supports\CurrencySupport
 */
class Currency extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return CurrencySupport::class;
    }
}
