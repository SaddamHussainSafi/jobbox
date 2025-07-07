<?php

namespace Botble\JobBoard\Http\Controllers;

use Botble\Base\Events\BeforeEditContentEvent;
use Botble\Base\Events\CreatedContentEvent;
use Botble\Base\Events\UpdatedContentEvent;
use Botble\Base\Http\Actions\DeleteResourceAction;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Supports\Breadcrumb;
use Botble\JobBoard\Forms\JobSkillForm;
use Botble\JobBoard\Http\Requests\JobSkillRequest;
use Botble\JobBoard\Models\JobSkill;
use Botble\JobBoard\Tables\JobSkillTable;
use Illuminate\Http\Request;

class JobSkillController extends BaseController
{
    protected function breadcrumb(): Breadcrumb
    {
        return parent::breadcrumb()
            ->add(trans('plugins/job-board::job-board.name'))
            ->add(trans('plugins/job-board::job-skill.name'), route('job-skills.index'));
    }

    public function index(JobSkillTable $table)
    {
        $this->pageTitle(trans('plugins/job-board::job-skill.name'));

        return $table->renderTable();
    }

    public function create()
    {
        $this->pageTitle(trans('plugins/job-board::job-skill.create'));

        return JobSkillForm::create()->renderForm();
    }

    public function store(JobSkillRequest $request)
    {
        if ($request->input('is_default')) {
            JobSkill::query()->where('id', '>', 0)->update(['is_default' => 0]);
        }

        $jobSkill = JobSkill::query()->create($request->input());

        event(new CreatedContentEvent(JOB_SKILL_MODULE_SCREEN_NAME, $request, $jobSkill));

        return $this
            ->httpResponse()
            ->setPreviousUrl(route('job-skills.index'))
            ->setNextUrl(route('job-skills.edit', $jobSkill->id))
            ->withCreatedSuccessMessage();
    }

    public function edit(JobSkill $jobSkill, Request $request)
    {
        event(new BeforeEditContentEvent($request, $jobSkill));

        $this->pageTitle(trans('core/base::forms.edit_item', ['name' => $jobSkill->name]));

        return JobSkillForm::createFromModel($jobSkill)->renderForm();
    }

    public function update(JobSkill $jobSkill, JobSkillRequest $request)
    {
        if ($request->input('is_default')) {
            JobSkill::query()->where('id', '!=', $jobSkill->getKey())->update(['is_default' => 0]);
        }

        $jobSkill->fill($request->input());
        $jobSkill->save();

        event(new UpdatedContentEvent(JOB_SKILL_MODULE_SCREEN_NAME, $request, $jobSkill));

        return $this
            ->httpResponse()
            ->setPreviousUrl(route('job-skills.index'))
            ->withUpdatedSuccessMessage();
    }

    public function destroy(JobSkill $jobSkill)
    {
        return DeleteResourceAction::make($jobSkill);
    }

    public function getAllSkills()
    {
        return JobSkill::query()->pluck('name')->all();
    }
}
