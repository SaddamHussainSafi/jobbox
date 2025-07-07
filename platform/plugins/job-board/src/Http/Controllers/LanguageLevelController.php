<?php

namespace Botble\JobBoard\Http\Controllers;

use Botble\Base\Events\BeforeEditContentEvent;
use Botble\Base\Events\CreatedContentEvent;
use Botble\Base\Events\UpdatedContentEvent;
use Botble\Base\Http\Actions\DeleteResourceAction;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Supports\Breadcrumb;
use Botble\JobBoard\Forms\LanguageLevelForm;
use Botble\JobBoard\Http\Requests\LanguageLevelRequest;
use Botble\JobBoard\Models\LanguageLevel;
use Botble\JobBoard\Tables\LanguageLevelTable;
use Illuminate\Http\Request;

class LanguageLevelController extends BaseController
{
    protected function breadcrumb(): Breadcrumb
    {
        return parent::breadcrumb()
            ->add(trans('plugins/job-board::job-board.name'))
            ->add(trans('plugins/job-board::language-level.name'), route('language-levels.index'));
    }

    public function index(LanguageLevelTable $table)
    {
        $this->pageTitle(trans('plugins/job-board::language-level.name'));

        return $table->renderTable();
    }

    public function create()
    {
        $this->pageTitle(trans('plugins/job-board::language-level.create'));

        return LanguageLevelForm::create()->renderForm();
    }

    public function store(LanguageLevelRequest $request)
    {
        if ($request->input('is_default')) {
            LanguageLevel::query()->where('id', '>', 0)->update(['is_default' => 0]);
        }

        $languageLevel = LanguageLevel::query()->create($request->input());

        event(new CreatedContentEvent(LANGUAGE_LEVEL_MODULE_SCREEN_NAME, $request, $languageLevel));

        return $this
            ->httpResponse()
            ->setPreviousUrl(route('language-levels.index'))
            ->setNextUrl(route('language-levels.edit', $languageLevel->id))
            ->withCreatedSuccessMessage();
    }

    public function edit(LanguageLevel $languageLevel, Request $request)
    {
        event(new BeforeEditContentEvent($request, $languageLevel));

        $this->pageTitle(trans('core/base::forms.edit_item', ['name' => $languageLevel->name]));

        return LanguageLevelForm::createFromModel($languageLevel)->renderForm();
    }

    public function update(LanguageLevel $languageLevel, LanguageLevelRequest $request)
    {
        if ($request->input('is_default')) {
            LanguageLevel::query()->where('id', '!=', $languageLevel->getKey())->update(['is_default' => 0]);
        }

        $languageLevel->fill($request->input());
        $languageLevel->save();

        event(new UpdatedContentEvent(LANGUAGE_LEVEL_MODULE_SCREEN_NAME, $request, $languageLevel));

        return $this
            ->httpResponse()
            ->setPreviousUrl(route('language-levels.index'))
            ->withUpdatedSuccessMessage();
    }

    public function destroy(LanguageLevel $languageLevel)
    {
        return DeleteResourceAction::make($languageLevel);
    }
}
