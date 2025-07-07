<?php

namespace Botble\JobBoard\Http\Controllers\Fronts;

use Botble\Base\Http\Controllers\BaseController;
use Botble\JobBoard\Facades\JobBoardHelper;
use Botble\JobBoard\Http\Requests\AccountExperienceRequest;
use Botble\JobBoard\Models\Account;
use Botble\JobBoard\Models\AccountExperience;
use Illuminate\Support\Facades\Auth;

class AccountExperienceController extends BaseController
{
    public function index()
    {
        $account = auth('account')->user();
        $experiences = AccountExperience::where('account_id', $account->id)->get();

        return JobBoardHelper::scope('account.experiences.index', compact('account', 'experiences'));
    }

    public function create()
    {
        $account = auth('account')->user();

        return JobBoardHelper::scope('account.experiences.create', compact('account'));
    }

    public function store(AccountExperienceRequest $request)
    {
        /** @var \Botble\JobBoard\Models\Account $account */
        $account = Account::findOrFail(auth('account')->id());

        if ($account->isJobSeeker()) {
            AccountExperience::create(array_merge(
                $request->validated(),
                ['account_id' => $account->id]
            ));
        }

        return $this
            ->httpResponse()
            ->setNextUrl(route('public.account.experiences.index'))
            ->setMessage(trans('plugins/job-board::account.experiences.store'));
    }

    public function edit($id)
    {
        $account = auth('account')->user();
        $experience = AccountExperience::query()
            ->where('account_id', $account->id)
            ->where('id', $id)
            ->firstOrFail();

        return JobBoardHelper::scope('account.experiences.edit', compact('account', 'experience'));
    }

    public function update(AccountExperienceRequest $request, $id)
    {
        $experience = AccountExperience::query()
            ->where('id', $id)
            ->where('account_id', Auth::guard('account')->id())
            ->firstOrFail();

        $experience->update($request->validated());

        return $this
            ->httpResponse()
            ->setNextUrl(route('public.account.experiences.index'))
            ->setMessage(trans('plugins/job-board::account.experiences.update'));
    }

    public function destroy($id)
    {
        $experience = AccountExperience::query()
            ->where('id', $id)
            ->where('account_id', Auth::guard('account')->id())
            ->firstOrFail();

        $experience->delete();

        return $this
            ->httpResponse()
            ->setNextUrl(route('public.account.experiences.index'))
            ->setMessage(trans('plugins/job-board::account.experiences.delete'));
    }
}
