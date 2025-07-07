<?php

namespace Botble\JobBoard\Forms\Fronts\Auth;

use Botble\Base\Forms\Fields\EmailField;
use Botble\Base\Forms\Fields\HtmlField;
use Botble\JobBoard\Forms\Fronts\Auth\FieldOptions\EmailFieldOption;
use Botble\JobBoard\Http\Requests\Fronts\Auth\ForgotPasswordRequest;

class ForgotPasswordForm extends AuthForm
{
    public function setup(): void
    {
        parent::setup();

        $this
            ->setUrl(route('public.account.password.email'))
            ->setValidatorClass(ForgotPasswordRequest::class)
            ->add(
                'email',
                EmailField::class,
                EmailFieldOption::make()
                    ->label(__('Email'))
                    ->placeholder(__('Email address'))
                    ->icon('ti ti-mail')
            )
            ->submitButton(__('Send Password Reset Link'))
            ->add('back_to_login', HtmlField::class, [
                'html' => sprintf(
                    '<div class="mt-3 text-center"><a href="%s" class="text-decoration-underline">%s</a></div>',
                    route('public.account.login'),
                    __('Back to login page')
                ),
            ]);
    }
}
