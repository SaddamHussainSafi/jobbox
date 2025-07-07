<?php

namespace Botble\JobBoard\Http\Controllers;

use Botble\Base\Events\BeforeEditContentEvent;
use Botble\Base\Events\CreatedContentEvent;
use Botble\Base\Events\UpdatedContentEvent;
use Botble\Base\Http\Actions\DeleteResourceAction;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Supports\Breadcrumb;
use Botble\JobBoard\Forms\DegreeLevelForm;
use Botble\JobBoard\Http\Requests\DegreeLevelRequest;
use Botble\JobBoard\Models\DegreeLevel;
use Botble\JobBoard\Tables\DegreeLevelTable;
use Illuminate\Http\Request;

class DegreeLevelController extends BaseController
{
    protected function breadcrumb(): Breadcrumb
    {
        return parent::breadcrumb()
            ->add(trans('plugins/job-board::job-board.name'))
            ->add(trans('plugins/job-board::degree-level.name'), route('degree-levels.index'));
    }

    public function index(DegreeLevelTable $table)
    {
        $this->pageTitle(trans('plugins/job-board::degree-level.name'));

        return $table->renderTable();
    }

    public function create()
    {
        $this->pageTitle(trans('plugins/job-board::degree-level.create'));

        return DegreeLevelForm::create()->renderForm();
    }

    public function store(DegreeLevelRequest $request)
    {
        if ($request->input('is_default')) {
            DegreeLevel::query()->where('id', '>', 0)->update(['is_default' => 0]);
        }

        $degreeLevel = DegreeLevel::query()->create($request->input());

        event(new CreatedContentEvent(DEGREE_LEVEL_MODULE_SCREEN_NAME, $request, $degreeLevel));

        return $this
            ->httpResponse()
            ->setPreviousUrl(route('degree-levels.index'))
            ->setNextUrl(route('degree-levels.edit', $degreeLevel->id))
            ->withCreatedSuccessMessage();
    }

    public function edit(DegreeLevel $degreeLevel, Request $request)
    {
        event(new BeforeEditContentEvent($request, $degreeLevel));

        $this->pageTitle(trans('core/base::forms.edit_item', ['name' => $degreeLevel->name]));

        return DegreeLevelForm::createFromModel($degreeLevel)->renderForm();
    }

    public function update(DegreeLevel $degreeLevel, DegreeLevelRequest $request)
    {
        if ($request->input('is_default')) {
            DegreeLevel::query()->where('id', '!=', $degreeLevel->getKey())->update(['is_default' => 0]);
        }

        $degreeLevel->fill($request->input());
        $degreeLevel->save();

        event(new UpdatedContentEvent(DEGREE_LEVEL_MODULE_SCREEN_NAME, $request, $degreeLevel));

        return $this
            ->httpResponse()
            ->setPreviousUrl(route('degree-levels.index'))
            ->withUpdatedSuccessMessage();
    }

    public function destroy(DegreeLevel $degreeLevel)
    {
        return DeleteResourceAction::make($degreeLevel);
    }
}
