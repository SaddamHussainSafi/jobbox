<?php

namespace Botble\JobBoard\Http\Controllers;

use Botble\Base\Events\UpdatedContentEvent;
use Botble\Base\Http\Actions\DeleteResourceAction;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Supports\Breadcrumb;
use Botble\JobBoard\Forms\JobApplicationForm;
use Botble\JobBoard\Http\Requests\EditJobApplicationRequest;
use Botble\JobBoard\Models\JobApplication;
use Botble\JobBoard\Tables\JobApplicationTable;
use Botble\Media\Facades\RvMedia;

class JobApplicationController extends BaseController
{
    protected function breadcrumb(): Breadcrumb
    {
        return parent::breadcrumb()
            ->add(trans('plugins/job-board::job-board.name'))
            ->add(trans('plugins/job-board::job-application.name'), route('job-applications.index'));
    }

    public function index(JobApplicationTable $table)
    {
        $this->pageTitle(trans('plugins/job-board::job-application.name'));

        return $table->renderTable();
    }

    public function edit(JobApplication $jobApplication)
    {
        $this->pageTitle(trans('plugins/job-board::job-application.edit'));

        return JobApplicationForm::createFromModel($jobApplication)->renderForm();
    }

    public function update(JobApplication $jobApplication, EditJobApplicationRequest $request)
    {
        $jobApplication->fill($request->input());
        $jobApplication->save();

        event(new UpdatedContentEvent(JOB_APPLICATION_MODULE_SCREEN_NAME, $request, $jobApplication));

        return $this
            ->httpResponse()
            ->setPreviousUrl(route('job-applications.index'))
            ->withUpdatedSuccessMessage();
    }

    public function destroy(JobApplication $jobApplication)
    {
        return DeleteResourceAction::make($jobApplication);
    }

    public function downloadCv(JobApplication $application)
    {
        if ($application->resume) {
            return RvMedia::responseDownloadFile($application->resume);
        }

        $account = $application->account;

        if ($account->id && $account->resume) {
            return RvMedia::responseDownloadFile($account->resume);
        }

        abort(404);
    }
}
