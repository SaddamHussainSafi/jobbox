<?php

namespace Botble\JobBoard\Http\Controllers;

use Botble\Base\Events\BeforeEditContentEvent;
use Botble\Base\Events\CreatedContentEvent;
use Botble\Base\Events\UpdatedContentEvent;
use Botble\Base\Http\Actions\DeleteResourceAction;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Supports\Breadcrumb;
use Botble\JobBoard\Forms\JobTypeForm;
use Botble\JobBoard\Http\Requests\JobTypeRequest;
use Botble\JobBoard\Models\JobType;
use Botble\JobBoard\Tables\JobTypeTable;
use Illuminate\Http\Request;

class JobTypeController extends BaseController
{
    protected function breadcrumb(): Breadcrumb
    {
        return parent::breadcrumb()
            ->add(trans('plugins/job-board::job-board.name'))
            ->add(trans('plugins/job-board::job-type.name'), route('job-types.index'));
    }

    public function index(JobTypeTable $table)
    {
        $this->pageTitle(trans('plugins/job-board::job-type.name'));

        return $table->renderTable();
    }

    public function create()
    {
        $this->pageTitle(trans('plugins/job-board::job-type.create'));

        return JobTypeForm::create()->renderForm();
    }

    public function store(JobTypeRequest $request)
    {
        if ($request->input('is_default')) {
            JobType::query()->where('id', '>', 0)->update(['is_default' => 0]);
        }

        $jobType = JobType::query()->create($request->input());

        event(new CreatedContentEvent(JOB_TYPE_MODULE_SCREEN_NAME, $request, $jobType));

        return $this
            ->httpResponse()
            ->setPreviousUrl(route('job-types.index'))
            ->setNextUrl(route('job-types.edit', $jobType->id))
            ->withCreatedSuccessMessage();
    }

    public function edit(JobType $jobType, Request $request)
    {
        event(new BeforeEditContentEvent($request, $jobType));

        $this->pageTitle(trans('core/base::forms.edit_item', ['name' => $jobType->name]));

        return JobTypeForm::createFromModel($jobType)->renderForm();
    }

    public function update(JobType $jobType, JobTypeRequest $request)
    {
        if ($request->input('is_default')) {
            JobType::query()->where('id', '!=', $jobType->getKey())->update(['is_default' => 0]);
        }

        $jobType->fill($request->input());
        $jobType->save();

        event(new UpdatedContentEvent(JOB_TYPE_MODULE_SCREEN_NAME, $request, $jobType));

        return $this
            ->httpResponse()
            ->setPreviousUrl(route('job-types.index'))
            ->withUpdatedSuccessMessage();
    }

    public function destroy(JobType $jobType)
    {
        return DeleteResourceAction::make($jobType);
    }
}
