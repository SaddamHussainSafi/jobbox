<?php

namespace Botble\JobBoard\Http\Controllers\Fronts;

use Botble\Base\Http\Controllers\BaseController;
use Botble\JobBoard\Forms\Fronts\AccountSettingForm;
use Botble\JobBoard\Http\Requests\SettingRequest;
use Botble\JobBoard\Models\Account;
use Botble\JobBoard\Models\AccountActivityLog;
use Botble\Optimize\Facades\OptimizerHelper;
use Botble\SeoHelper\Facades\SeoHelper;

class EmployerSettingController extends BaseController
{
    public function __construct()
    {
        OptimizerHelper::disable();
    }

    public function edit()
    {
        SeoHelper::setTitle(__('Account settings'));
        /**
         * @var Account $account
         */
        $account = auth('account')->user();

        return AccountSettingForm::createFromModel($account)->renderForm();
    }

    public function update(SettingRequest $request)
    {
        /**
         * @var Account $account
         */
        $account = auth('account')->user();
        $data = $request->validated();

        AccountSettingForm::createFromModel($account)
            ->saving(function (AccountSettingForm $form) use ($data): void {
                $model = $form->getModel();

                $model->fill($data);
                $model->save();
            });

        AccountActivityLog::query()->create(['action' => 'update_setting']);

        return $this
            ->httpResponse()
            ->setNextUrl(route('public.account.employer.settings.edit'))
            ->setMessage(__('Update settings successfully!'));
    }
}
