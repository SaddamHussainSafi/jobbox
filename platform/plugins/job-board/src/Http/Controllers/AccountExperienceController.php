<?php

namespace Botble\JobBoard\Http\Controllers;

use Botble\Base\Http\Controllers\BaseController;
use Botble\JobBoard\Facades\JobBoardHelper;
use Botble\JobBoard\Forms\AccountExperienceForm;
use Botble\JobBoard\Http\Requests\AccountExperienceRequest;
use Botble\JobBoard\Models\Account;
use Botble\JobBoard\Models\AccountExperience;

class AccountExperienceController extends BaseController
{
    public function create()
    {
        $account = auth('account')->user();

        return JobBoardHelper::scope('account.experiences.create', compact('account'));
    }

    public function store(AccountExperienceRequest $request)
    {
        /**
         * @var Account $account
         */
        $account = Account::query()->findOrFail($request->input('account_id'));

        if ($account->isJobSeeker()) {
            AccountExperience::query()->create(array_merge(
                $request->validated(),
                ['account_id' => $account->getKey()]
            ));
        }

        return $this
            ->httpResponse()
            ->setNextUrl(route('public.account.experiences.index'))
            ->setMessage(trans('plugins/job-board::account.experiences.store'));
    }

    public function update(AccountExperienceRequest $request, $id)
    {
        $experience = AccountExperience::query()
            ->where('id', $id)
            ->where('account_id', $request->input('account_id'))
            ->firstOrFail();

        $experience->update($request->validated());

        return $this
            ->httpResponse()
            ->setNextUrl(route('public.account.experiences.index'))
            ->setMessage(trans('plugins/job-board::account.experiences.update'));
    }

    public function destroy($id)
    {
        $experience = AccountExperience::query()->findOrFail($id);

        $experience->delete();

        return $this
            ->httpResponse()
            ->setNextUrl(route('public.account.experiences.index'))
            ->setMessage(trans('plugins/job-board::account.experiences.delete'));
    }

    public function editModal($id, $accountId)
    {
        $experience = AccountExperience::query()
            ->where('account_id', $accountId)
            ->where('id', $id)
            ->firstOrFail();

        return AccountExperienceForm::createFromModel($experience)->setFormOptions([
            'url' => route('accounts.experiences..update', $id),
        ])->renderForm();
    }
}
