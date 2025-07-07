<?php

namespace Botble\JobBoard\Forms\Fronts;

class AccountLanguageForm extends \Botble\JobBoard\Forms\AccountLanguageForm
{
    public function setup(): void
    {
        parent::setup();

        $this
            ->setFormOption('id', 'account-language-form')
            ->setUrl(route('public.account.languages.store'));
    }
}
