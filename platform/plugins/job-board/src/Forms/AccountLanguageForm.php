<?php

namespace Botble\JobBoard\Forms;

use Botble\Base\Forms\FieldOptions\CheckboxFieldOption;
use Botble\Base\Forms\FieldOptions\SelectFieldOption;
use Botble\Base\Forms\Fields\CheckboxField;
use Botble\Base\Forms\Fields\SelectField;
use Botble\Base\Forms\FormAbstract;
use Botble\Base\Supports\Language;
use Botble\JobBoard\Http\Requests\AccountLanguageRequest;
use Botble\JobBoard\Models\AccountLanguage;
use Botble\JobBoard\Models\LanguageLevel;

class AccountLanguageForm extends FormAbstract
{
    public function setup(): void
    {
        $languages = Language::getLocales();
        $levels = LanguageLevel::query()->pluck('name', 'id')->all();

        $this
            ->contentOnly()
            ->model(AccountLanguage::class)
            ->setUrl(route('accounts.languages.create'))
            ->setValidatorClass(AccountLanguageRequest::class)
            ->add(
                'language',
                SelectField::class,
                SelectFieldOption::make()
                    ->label(__('Language'))
                    ->searchable()
                    ->choices($languages),
            )
            ->add(
                'language_level_id',
                SelectField::class,
                SelectFieldOption::make()
                    ->label(__('Level'))
                    ->choices($levels),
            )
            ->add(
                'is_native',
                CheckboxField::class,
                CheckboxFieldOption::make()
                    ->label(__('Is native?'))
                    ->defaultValue(false)
                    ->helperText(__('Check this if you are a native speaker of this language.')),
            );
    }
}
