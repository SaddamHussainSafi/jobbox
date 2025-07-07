<?php

namespace Botble\JobBoard\Http\Controllers;

use Botble\Base\Http\Controllers\BaseController;
use Botble\JobBoard\Facades\JobBoardHelper;
use Botble\JobBoard\Forms\AccountEducationForm;
use Botble\JobBoard\Http\Requests\AccountEducationRequest;
use Botble\JobBoard\Models\Account;
use Botble\JobBoard\Models\AccountEducation;

class AccountEducationController extends BaseController
{
    public function create()
    {
        $account = auth('account')->user();

        return JobBoardHelper::scope('account.educations.create', compact('account'));
    }

    public function store(AccountEducationRequest $request)
    {
        /**
         * @var Account $account
         */
        $account = Account::query()->findOrFail($request->input('account_id'));

        if ($account->isJobSeeker()) {
            AccountEducation::query()->create(array_merge(
                $request->validated(),
                ['account_id' => $account->getKey()]
            ));
        }

        return $this
            ->httpResponse()
            ->setNextUrl(route('public.account.educations.index'))
            ->setMessage(trans('plugins/job-board::account.educations.store'));
    }

    public function update(AccountEducationRequest $request, $id)
    {
        $education = AccountEducation::query()
            ->where('id', $id)
            ->where('account_id', $request->input('account_id'))
            ->firstOrFail();

        $education->update($request->validated());

        return $this
            ->httpResponse()
            ->setNextUrl(route('public.account.educations.index'))
            ->setMessage(trans('plugins/job-board::account.educations.update'));
    }

    public function destroy($id)
    {
        $education = AccountEducation::query()->findOrFail($id);

        $education->delete();

        return $this
            ->httpResponse()
            ->setNextUrl(route('public.account.educations.index'))
            ->setMessage(trans('plugins/job-board::account.educations.delete'));
    }

    public function editModal($id, $accountId)
    {
        $education = AccountEducation::query()
            ->where('account_id', $accountId)
            ->where('id', $id)
            ->firstOrFail();

        return AccountEducationForm::createFromModel($education)->setFormOptions([
            'url' => route('accounts.educations..update', $id),
        ])->renderForm();
    }
}
