<?php

namespace Botble\JobBoard\Http\Controllers;

use Botble\Base\Http\Actions\DeleteResourceAction;
use Botble\Base\Http\Controllers\BaseController;
use Botble\JobBoard\Forms\AccountLanguageForm;
use Botble\JobBoard\Http\Requests\AccountLanguageRequest;
use Botble\JobBoard\Models\Account;
use Botble\JobBoard\Models\AccountLanguage;

class AccountLanguageController extends BaseController
{
    public function store(AccountLanguageRequest $request)
    {
        /**
         * @var Account $account
         */
        $account = Account::query()->findOrFail($request->input('account_id'));

        if ($account->isJobSeeker()) {
            AccountLanguage::query()->create([
                ...$request->validated(),
                'account_id' => $account->getKey(),
            ]);
        }

        return $this
            ->httpResponse()
            ->setNextUrl(route('public.account.experiences.index'))
            ->withCreatedSuccessMessage();
    }

    public function update(AccountLanguageRequest $request, $id)
    {
        $language = AccountLanguage::query()
            ->where('id', $id)
            ->where('account_id', $request->input('account_id'))
            ->firstOrFail();

        $language->update($request->validated());

        return $this
            ->httpResponse()
            ->setNextUrl(route('public.account.experiences.index'))
            ->withUpdatedSuccessMessage();
    }

    public function destroy($id)
    {
        $language = AccountLanguage::query()->findOrFail($id);

        return DeleteResourceAction::make($language);
    }

    public function editModal($id, $accountId)
    {
        $language = AccountLanguage::query()
            ->where('account_id', $accountId)
            ->where('id', $id)
            ->firstOrFail();

        return AccountLanguageForm::createFromModel($language)
            ->setUrl(route('accounts.languages.edit', $id))
            ->add(
                'account_id',
                'hidden',
                ['value' => $accountId]
            )
            ->renderForm();
    }
}
