<?php

namespace Botble\JobBoard\Http\Controllers;

use Botble\Base\Events\BeforeEditContentEvent;
use Botble\Base\Events\CreatedContentEvent;
use Botble\Base\Events\UpdatedContentEvent;
use Botble\Base\Http\Actions\DeleteResourceAction;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Supports\Breadcrumb;
use Botble\JobBoard\Forms\DegreeTypeForm;
use Botble\JobBoard\Http\Requests\DegreeTypeRequest;
use Botble\JobBoard\Models\DegreeType;
use Botble\JobBoard\Tables\DegreeTypeTable;
use Illuminate\Http\Request;

class DegreeTypeController extends BaseController
{
    protected function breadcrumb(): Breadcrumb
    {
        return parent::breadcrumb()
            ->add(trans('plugins/job-board::job-board.name'))
            ->add(trans('plugins/job-board::degree-type.name'), route('degree-types.index'));
    }

    public function index(DegreeTypeTable $table)
    {
        $this->pageTitle(trans('plugins/job-board::degree-type.name'));

        return $table->renderTable();
    }

    public function create()
    {
        $this->pageTitle(trans('plugins/job-board::degree-type.create'));

        return DegreeTypeForm::create()->renderForm();
    }

    public function store(DegreeTypeRequest $request)
    {
        if ($request->input('is_default')) {
            DegreeType::query()->where('id', '>', 0)->update(['is_default' => 0]);
        }

        $degreeType = DegreeType::query()->create($request->input());

        event(new CreatedContentEvent(DEGREE_TYPE_MODULE_SCREEN_NAME, $request, $degreeType));

        return $this
            ->httpResponse()
            ->setPreviousUrl(route('degree-types.index'))
            ->setNextUrl(route('degree-types.edit', $degreeType->id))
            ->withCreatedSuccessMessage();
    }

    public function edit(DegreeType $degreeType, Request $request)
    {
        event(new BeforeEditContentEvent($request, $degreeType));

        $this->pageTitle(trans('core/base::forms.edit_item', ['name' => $degreeType->name]));

        return DegreeTypeForm::createFromModel($degreeType)->renderForm();
    }

    public function update(DegreeType $degreeType, DegreeTypeRequest $request)
    {
        if ($request->input('is_default')) {
            DegreeType::query()->where('id', '!=', $degreeType->getKey())->update(['is_default' => 0]);
        }

        $degreeType->fill($request->input());
        $degreeType->save();

        event(new UpdatedContentEvent(DEGREE_TYPE_MODULE_SCREEN_NAME, $request, $degreeType));

        return $this
            ->httpResponse()
            ->setPreviousUrl(route('degree-types.index'))
            ->withUpdatedSuccessMessage();
    }

    public function destroy(DegreeType $degreeType)
    {
        return DeleteResourceAction::make($degreeType);
    }
}
