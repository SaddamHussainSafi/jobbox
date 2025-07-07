<?php

namespace Botble\JobBoard\Http\Controllers;

use Botble\Base\Events\BeforeEditContentEvent;
use Botble\Base\Events\CreatedContentEvent;
use Botble\Base\Events\UpdatedContentEvent;
use Botble\Base\Http\Actions\DeleteResourceAction;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Supports\Breadcrumb;
use Botble\JobBoard\Forms\FunctionalAreaForm;
use Botble\JobBoard\Http\Requests\FunctionalAreaRequest;
use Botble\JobBoard\Models\FunctionalArea;
use Botble\JobBoard\Tables\FunctionalAreaTable;
use Illuminate\Http\Request;

class FunctionalAreaController extends BaseController
{
    protected function breadcrumb(): Breadcrumb
    {
        return parent::breadcrumb()
            ->add(trans('plugins/job-board::job-board.name'))
            ->add(trans('plugins/job-board::functional-area.name'), route('functional-areas.index'));
    }

    public function index(FunctionalAreaTable $table)
    {
        $this->pageTitle(trans('plugins/job-board::functional-area.name'));

        return $table->renderTable();
    }

    public function create()
    {
        $this->pageTitle(trans('plugins/job-board::functional-area.create'));

        return FunctionalAreaForm::create()->renderForm();
    }

    public function store(FunctionalAreaRequest $request)
    {
        if ($request->input('is_default')) {
            FunctionalArea::query()->where('id', '>', 0)->update(['is_default' => 0]);
        }

        $functionalArea = FunctionalArea::query()->create($request->input());

        event(new CreatedContentEvent(FUNCTIONAL_AREA_MODULE_SCREEN_NAME, $request, $functionalArea));

        return $this
            ->httpResponse()
            ->setPreviousUrl(route('functional-areas.index'))
            ->setNextUrl(route('functional-areas.edit', $functionalArea->id))
            ->withCreatedSuccessMessage();
    }

    public function edit(FunctionalArea $functionalArea, Request $request)
    {
        event(new BeforeEditContentEvent($request, $functionalArea));

        $this->pageTitle(trans('core/base::forms.edit_item', ['name' => $functionalArea->name]));

        return FunctionalAreaForm::createFromModel($functionalArea)->renderForm();
    }

    public function update(FunctionalArea $functionalArea, FunctionalAreaRequest $request)
    {
        if ($request->input('is_default')) {
            FunctionalArea::query()->where('id', '!=', $functionalArea->getKey())->update(['is_default' => 0]);
        }

        $functionalArea->fill($request->input());
        $functionalArea->save();

        event(new UpdatedContentEvent(FUNCTIONAL_AREA_MODULE_SCREEN_NAME, $request, $functionalArea));

        return $this
            ->httpResponse()
            ->setPreviousUrl(route('functional-areas.index'))
            ->withUpdatedSuccessMessage();
    }

    public function destroy(FunctionalArea $functionalArea)
    {
        return DeleteResourceAction::make($functionalArea);
    }
}
