<?php

namespace Botble\JobBoard\Http\Controllers;

use Botble\Base\Events\BeforeEditContentEvent;
use Botble\Base\Events\CreatedContentEvent;
use Botble\Base\Events\UpdatedContentEvent;
use Botble\Base\Http\Actions\DeleteResourceAction;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Supports\Breadcrumb;
use Botble\JobBoard\Forms\JobShiftForm;
use Botble\JobBoard\Http\Requests\JobShiftRequest;
use Botble\JobBoard\Models\JobShift;
use Botble\JobBoard\Tables\JobShiftTable;
use Illuminate\Http\Request;

class JobShiftController extends BaseController
{
    protected function breadcrumb(): Breadcrumb
    {
        return parent::breadcrumb()
            ->add(trans('plugins/job-board::job-board.name'))
            ->add(trans('plugins/job-board::job-shift.name'), route('job-shifts.index'));
    }

    public function index(JobShiftTable $table)
    {
        $this->pageTitle(trans('plugins/job-board::job-shift.name'));

        return $table->renderTable();
    }

    public function create()
    {
        $this->pageTitle(trans('plugins/job-board::job-shift.create'));

        return JobShiftForm::create()->renderForm();
    }

    public function store(JobShiftRequest $request)
    {
        if ($request->input('is_default')) {
            JobShift::query()->where('id', '>', 0)->update(['is_default' => 0]);
        }

        $jobShift = JobShift::query()->create($request->input());

        event(new CreatedContentEvent(JOB_SHIFT_MODULE_SCREEN_NAME, $request, $jobShift));

        return $this
            ->httpResponse()
            ->setPreviousUrl(route('job-shifts.index'))
            ->setNextUrl(route('job-shifts.edit', $jobShift->id))
            ->withCreatedSuccessMessage();
    }

    public function edit(JobShift $jobShift, Request $request)
    {
        event(new BeforeEditContentEvent($request, $jobShift));

        $this->pageTitle(trans('core/base::forms.edit_item', ['name' => $jobShift->name]));

        return JobShiftForm::createFromModel($jobShift)->renderForm();
    }

    public function update(JobShift $jobShift, JobShiftRequest $request)
    {
        if ($request->input('is_default')) {
            JobShift::query()->where('id', '!=', $jobShift->getKey())->update(['is_default' => 0]);
        }

        $jobShift->fill($request->input());
        $jobShift->save();

        event(new UpdatedContentEvent(JOB_SHIFT_MODULE_SCREEN_NAME, $request, $jobShift));

        return $this
            ->httpResponse()
            ->setPreviousUrl(route('job-shifts.index'))
            ->withUpdatedSuccessMessage();
    }

    public function destroy(JobShift $jobShift)
    {
        return DeleteResourceAction::make($jobShift);
    }
}
