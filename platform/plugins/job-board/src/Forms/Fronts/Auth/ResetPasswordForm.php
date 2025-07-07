<?php

namespace Botble\JobBoard\Forms\Fronts\Auth;

use Botble\Base\Forms\Fields\EmailField;
use Botble\Base\Forms\Fields\HtmlField;
use Botble\Base\Forms\Fields\PasswordField;
use Botble\JobBoard\Forms\Fronts\Auth\FieldOptions\EmailFieldOption;
use Botble\JobBoard\Forms\Fronts\Auth\FieldOptions\TextFieldOption;
use Botble\JobBoard\Http\Requests\Fronts\Auth\ResetPasswordRequest;

class ResetPasswordForm extends AuthForm
{
    public function setup(): void
    {
        parent::setup();

        $this
            ->setUrl(route('public.account.password.reset.update'))
            ->setValidatorClass(ResetPasswordRequest::class)
            ->add(
                'token',
                'hidden',
                TextFieldOption::make()
                    ->value($this->request->route('token'))
            )
            ->add(
                'email',
                EmailField::class,
                EmailFieldOption::make()
                    ->label(__('Email address'))
                    ->value($this->request->email)
                    ->icon('ti ti-mail')
            )
            ->add(
                'password',
                PasswordField::class,
                TextFieldOption::make()
                    ->label(__('Password'))
                    ->placeholder(__('Password'))
                    ->icon('ti ti-lock')
            )
            ->add(
                'password_confirmation',
                PasswordField::class,
                TextFieldOption::make()
                    ->label(__('Password confirmation'))
                    ->placeholder(__('Password confirmation'))
                    ->icon('ti ti-lock')
            )
            ->submitButton(__('Reset Password'))
            ->add('back_to_login', HtmlField::class, [
                'html' => sprintf(
                    '<div class="mt-3 text-center"><a href="%s" class="text-decoration-underline">%s</a></div>',
                    route('public.account.login'),
                    __('Back to login page')
                ),
            ]);
    }
}
