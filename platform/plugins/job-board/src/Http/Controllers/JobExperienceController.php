<?php

namespace Botble\JobBoard\Http\Controllers;

use Botble\Base\Events\BeforeEditContentEvent;
use Botble\Base\Events\CreatedContentEvent;
use Botble\Base\Events\UpdatedContentEvent;
use Botble\Base\Http\Actions\DeleteResourceAction;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Supports\Breadcrumb;
use Botble\JobBoard\Forms\JobExperienceForm;
use Botble\JobBoard\Http\Requests\JobExperienceRequest;
use Botble\JobBoard\Models\JobExperience;
use Botble\JobBoard\Tables\JobExperienceTable;
use Illuminate\Http\Request;

class JobExperienceController extends BaseController
{
    protected function breadcrumb(): Breadcrumb
    {
        return parent::breadcrumb()
            ->add(trans('plugins/job-board::job-board.name'))
            ->add(trans('plugins/job-board::job-experience.name'), route('job-experiences.index'));
    }

    public function index(JobExperienceTable $table)
    {
        $this->pageTitle(trans('plugins/job-board::job-experience.name'));

        return $table->renderTable();
    }

    public function create()
    {
        $this->pageTitle(trans('plugins/job-board::job-experience.create'));

        return JobExperienceForm::create()->renderForm();
    }

    public function store(JobExperienceRequest $request)
    {
        if ($request->input('is_default')) {
            JobExperience::query()->where('id', '>', 0)->update(['is_default' => 0]);
        }

        $jobExperience = JobExperience::query()->create($request->input());

        event(new CreatedContentEvent(JOB_EXPERIENCE_MODULE_SCREEN_NAME, $request, $jobExperience));

        return $this
            ->httpResponse()
            ->setPreviousUrl(route('job-experiences.index'))
            ->setNextUrl(route('job-experiences.edit', $jobExperience->id))
            ->withCreatedSuccessMessage();
    }

    public function edit(JobExperience $jobExperience, Request $request)
    {
        event(new BeforeEditContentEvent($request, $jobExperience));

        $this->pageTitle(trans('core/base::forms.edit_item', ['name' => $jobExperience->name]));

        return JobExperienceForm::createFromModel($jobExperience)->renderForm();
    }

    public function update(JobExperience $jobExperience, JobExperienceRequest $request)
    {
        if ($request->input('is_default')) {
            JobExperience::query()->where('id', '!=', $jobExperience->getKey())->update(['is_default' => 0]);
        }

        $jobExperience->fill($request->input());
        $jobExperience->save();

        event(new UpdatedContentEvent(JOB_EXPERIENCE_MODULE_SCREEN_NAME, $request, $jobExperience));

        return $this
            ->httpResponse()
            ->setPreviousUrl(route('job-experiences.index'))
            ->withUpdatedSuccessMessage();
    }

    public function destroy(JobExperience $jobExperience)
    {
        return DeleteResourceAction::make($jobExperience);
    }
}
