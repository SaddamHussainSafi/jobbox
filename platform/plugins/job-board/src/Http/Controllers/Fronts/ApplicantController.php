<?php

namespace Botble\JobBoard\Http\Controllers\Fronts;

use Botble\Base\Events\UpdatedContentEvent;
use Botble\Base\Http\Actions\DeleteResourceAction;
use Botble\Base\Http\Controllers\BaseController;
use Botble\JobBoard\Facades\JobBoardHelper;
use Botble\JobBoard\Forms\Fronts\ApplicantForm;
use Botble\JobBoard\Http\Requests\EditJobApplicationRequest;
use Botble\JobBoard\Models\Account;
use Botble\JobBoard\Models\JobApplication;
use Botble\JobBoard\Tables\Fronts\ApplicantTable;
use Botble\SeoHelper\Facades\SeoHelper;
use Illuminate\Database\Eloquent\Builder;

class ApplicantController extends BaseController
{
    public function index(ApplicantTable $table)
    {
        $this->pageTitle(__('Applicants'));

        return $table->render(JobBoardHelper::viewPath('dashboard.table.base'));
    }

    public function edit(int|string $id)
    {
        /**
         * @var Account $account
         */
        $account = auth('account')->user();

        $jobApplication = JobApplication::query()
            ->select(['*'])
            ->whereHas('job.company.accounts', function (Builder $query) use ($account): void {
                $query->where('account_id', $account->getKey());
            })
            ->with(['account'])
            ->where('id', $id)
            ->firstOrFail();

        $title = __('View applicant ":name"', ['name' => $jobApplication->full_name]);

        $this->pageTitle($title);

        SeoHelper::setTitle($title);

        return ApplicantForm::createFromModel($jobApplication)->renderForm();
    }

    public function update(int|string $id, EditJobApplicationRequest $request)
    {
        /**
         * @var Account $account
         */
        $account = auth('account')->user();

        $jobApplication = JobApplication::query()
            ->select(['*'])
            ->whereHas('job.company.accounts', function (Builder $query) use ($account): void {
                $query->where('account_id', $account->getKey());
            })
            ->where('id', $id)
            ->firstOrFail();

        $jobApplication->fill($request->only(['status']));
        $jobApplication->save();

        event(new UpdatedContentEvent(JOB_APPLICATION_MODULE_SCREEN_NAME, $request, $jobApplication));

        return $this
            ->httpResponse()
            ->setPreviousUrl(route('public.account.applicants.index'))
            ->withUpdatedSuccessMessage();
    }

    public function destroy(int|string $id)
    {
        /**
         * @var Account $account
         */
        $account = auth('account')->user();

        $jobApplication = JobApplication::query()
            ->select(['*'])
            ->whereHas('job.company.accounts', function (Builder $query) use ($account): void {
                $query->where('account_id', $account->getKey());
            })
            ->where('id', $id)
            ->firstOrFail();

        return DeleteResourceAction::make($jobApplication);
    }
}
