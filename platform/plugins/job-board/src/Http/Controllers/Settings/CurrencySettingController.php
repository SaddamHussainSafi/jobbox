<?php

namespace Botble\JobBoard\Http\Controllers\Settings;

use Botble\JobBoard\Forms\Settings\CurrencySettingForm;
use Botble\JobBoard\Http\Requests\Settings\CurrencySettingRequest;
use Botble\JobBoard\Services\StoreCurrenciesService;
use Botble\Setting\Http\Controllers\SettingController;

class CurrencySettingController extends SettingController
{
    public function edit()
    {
        $this->pageTitle(trans('plugins/job-board::settings.currency.title'));

        $form = CurrencySettingForm::create();

        return view('plugins/job-board::settings.currency', compact('form'));
    }

    public function update(CurrencySettingRequest $request, StoreCurrenciesService $service)
    {
        $this->saveSettings($request->except([
            'currencies',
            'currencies_data',
            'deleted_currencies',
        ]));

        $currencies = json_decode($request->validated('currencies'), true) ?: [];

        if (! $currencies) {
            return $this
                ->httpResponse()
                ->setError()
                ->setMessage(trans('plugins/job-board::settings.currency.require_at_least_one_currency'));
        }

        $deletedCurrencies = json_decode($request->input('deleted_currencies', []), true) ?: [];

        $storedCurrencies = $service->execute($currencies, $deletedCurrencies);

        if ($storedCurrencies['error']) {
            return $this
                ->httpResponse()
                ->setError()
                ->setMessage($storedCurrencies['message']);
        }

        return $this
            ->httpResponse()
            ->withUpdatedSuccessMessage();
    }
}
