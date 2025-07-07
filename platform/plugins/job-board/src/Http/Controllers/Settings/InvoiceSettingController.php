<?php

namespace Botble\JobBoard\Http\Controllers\Settings;

use Botble\JobBoard\Forms\Settings\InvoiceSettingForm;
use Botble\JobBoard\Http\Requests\Settings\InvoiceSettingRequest;
use Botble\Setting\Http\Controllers\SettingController;

class InvoiceSettingController extends SettingController
{
    public function edit()
    {
        $this->pageTitle(trans('plugins/job-board::settings.invoice.title'));

        return InvoiceSettingForm::create()->renderForm();
    }

    public function update(InvoiceSettingRequest $request)
    {
        return $this->performUpdate($request->validated());
    }
}
