<?php

namespace Botble\JobBoard\Http\Controllers;

use Botble\Base\Events\BeforeEditContentEvent;
use Botble\Base\Events\CreatedContentEvent;
use Botble\Base\Events\UpdatedContentEvent;
use Botble\Base\Http\Actions\DeleteResourceAction;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Supports\Breadcrumb;
use Botble\JobBoard\Forms\CareerLevelForm;
use Botble\JobBoard\Http\Requests\CareerLevelRequest;
use Botble\JobBoard\Models\CareerLevel;
use Botble\JobBoard\Tables\CareerLevelTable;
use Illuminate\Http\Request;

class CareerLevelController extends BaseController
{
    protected function breadcrumb(): Breadcrumb
    {
        return parent::breadcrumb()
            ->add(trans('plugins/job-board::job-board.name'))
            ->add(trans('plugins/job-board::career-level.name'), route('career-levels.index'));
    }

    public function index(CareerLevelTable $table)
    {
        $this->pageTitle(trans('plugins/job-board::career-level.name'));

        return $table->renderTable();
    }

    public function create()
    {
        $this->pageTitle(trans('plugins/job-board::career-level.create'));

        return CareerLevelForm::create()->renderForm();
    }

    public function store(CareerLevelRequest $request)
    {
        if ($request->input('is_default')) {
            CareerLevel::query()->where('id', '>', 0)->update(['is_default' => 0]);
        }

        $careerLevel = CareerLevel::query()->create($request->input());

        event(new CreatedContentEvent(CAREER_LEVEL_MODULE_SCREEN_NAME, $request, $careerLevel));

        return $this
            ->httpResponse()
            ->setPreviousUrl(route('career-levels.index'))
            ->setNextUrl(route('career-levels.edit', $careerLevel->id))
            ->withCreatedSuccessMessage();
    }

    public function edit(CareerLevel $careerLevel, Request $request)
    {
        event(new BeforeEditContentEvent($request, $careerLevel));

        $this->pageTitle(trans('core/base::forms.edit_item', ['name' => $careerLevel->name]));

        return CareerLevelForm::createFromModel($careerLevel)->renderForm();
    }

    public function update(CareerLevel $careerLevel, CareerLevelRequest $request)
    {
        if ($request->input('is_default')) {
            CareerLevel::query()->where('id', '!=', $careerLevel->getKey())->update(['is_default' => 0]);
        }

        $careerLevel->fill($request->input());
        $careerLevel->save();

        event(new UpdatedContentEvent(CAREER_LEVEL_MODULE_SCREEN_NAME, $request, $careerLevel));

        return $this
            ->httpResponse()
            ->setPreviousUrl(route('career-levels.index'))
            ->withUpdatedSuccessMessage();
    }

    public function destroy(CareerLevel $careerLevel)
    {
        return DeleteResourceAction::make($careerLevel);
    }
}
