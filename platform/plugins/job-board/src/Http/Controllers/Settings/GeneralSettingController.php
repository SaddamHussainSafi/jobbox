<?php

namespace Botble\JobBoard\Http\Controllers\Settings;

use Botble\JobBoard\Forms\Settings\GeneralSettingForm;
use Botble\JobBoard\Http\Requests\Settings\GeneralSettingRequest;
use Botble\Setting\Http\Controllers\SettingController;

class GeneralSettingController extends SettingController
{
    public function edit()
    {
        $this->pageTitle(trans('plugins/job-board::settings.general.title'));

        return GeneralSettingForm::create()->renderForm();
    }

    public function update(GeneralSettingRequest $request)
    {
        return $this->performUpdate($request->validated());
    }
}
