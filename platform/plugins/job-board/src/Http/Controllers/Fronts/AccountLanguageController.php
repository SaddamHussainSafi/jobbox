<?php

namespace Botble\JobBoard\Http\Controllers\Fronts;

use Botble\Base\Http\Actions\DeleteResourceAction;
use Botble\Base\Http\Controllers\BaseController;
use Botble\JobBoard\Forms\Fronts\AccountLanguageForm;
use Botble\JobBoard\Http\Requests\AccountLanguageRequest;
use Botble\JobBoard\Models\AccountLanguage;

class AccountLanguageController extends BaseController
{
    public function store(AccountLanguageRequest $request)
    {
        $form = AccountLanguageForm::create()->setRequest($request)->onlyValidatedData();

        $form->saving(function (AccountLanguageForm $form): void {
            $model = $form->getModel();

            $model->fill([
                ...$form->getRequestData(),
                'account_id' => auth('account')->id(),
            ]);

            $model->save();
        });

        return $this
            ->httpResponse()
            ->setNextRoute('public.account.settings')
            ->withCreatedSuccessMessage();
    }

    public function destroy(string $id)
    {
        $language = AccountLanguage::query()
            ->where('account_id', auth('account')->id())
            ->findOrFail($id);

        return DeleteResourceAction::make($language);
    }
}
