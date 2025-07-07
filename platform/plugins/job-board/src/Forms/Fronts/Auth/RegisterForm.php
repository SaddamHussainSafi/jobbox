<?php

namespace Botble\JobBoard\Forms\Fronts\Auth;

use Botble\Base\Facades\Html;
use Botble\Base\Forms\FieldOptions\CheckboxFieldOption;
use Botble\Base\Forms\Fields\EmailField;
use Botble\Base\Forms\Fields\HtmlField;
use Botble\Base\Forms\Fields\OnOffCheckboxField;
use Botble\Base\Forms\Fields\PasswordField;
use Botble\Base\Forms\Fields\PhoneNumberField;
use Botble\Base\Forms\Fields\TextField;
use Botble\Base\Forms\FormAbstract;
use Botble\JobBoard\Forms\Fronts\Auth\FieldOptions\EmailFieldOption;
use Botble\JobBoard\Forms\Fronts\Auth\FieldOptions\TextFieldOption;
use Botble\JobBoard\Http\Requests\Fronts\Auth\RegisterRequest;
use Botble\JobBoard\Models\Account;

class RegisterForm extends AuthForm
{
    public function setup(): void
    {
        parent::setup();

        $this
            ->setUrl(route('public.account.register.post'))
            ->setValidatorClass(RegisterRequest::class)
            ->add(
                'first_name',
                TextField::class,
                TextFieldOption::make()
                    ->label(__('First name'))
                    ->placeholder(__('First name'))
                    ->icon('ti ti-user')
            )
            ->add(
                'last_name',
                TextField::class,
                TextFieldOption::make()
                    ->label(__('Last name'))
                    ->placeholder(__('Last name'))
                    ->icon('ti ti-user')
            )
            ->add(
                'email',
                EmailField::class,
                EmailFieldOption::make()
                    ->label(__('Email'))
                    ->placeholder(__('Email address'))
                    ->icon('ti ti-mail')
            )
            ->add(
                'phone',
                PhoneNumberField::class,
                TextFieldOption::make()
                    ->label(__('Phone (optional)'))
                    ->placeholder(__('Phone number'))
                    ->icon('ti ti-phone')
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
            ->when(setting('job_board_enabled_register_as_employer', 1), function (FormAbstract $form): void {
                $form->add(
                    'is_employer',
                    OnOffCheckboxField::class,
                    CheckboxFieldOption::make()
                        ->label(__('Is employer?'))
                );
            })
            ->add(
                'agree_terms_and_policy',
                OnOffCheckboxField::class,
                CheckboxFieldOption::make()
                    ->when(
                        $privacyPolicyUrl = theme_option('term_and_privacy_policy_url'),
                        function (CheckboxFieldOption $fieldOption, string $url): void {
                            $fieldOption->label(__('I agree to the :link', ['link' => Html::link($url, __('Terms and Privacy Policy'), attributes: ['class' => 'text-decoration-underline', 'target' => '_blank'])]));
                        }
                    )
                    ->when(! $privacyPolicyUrl, function (CheckboxFieldOption $fieldOption): void {
                        $fieldOption->label(__('I agree to the Terms and Privacy Policy'));
                    })
            )
            ->submitButton(__('Register'), 'ti ti-arrow-narrow-right')
            ->add('login', HtmlField::class, [
                'html' => sprintf(
                    '<div class="mt-3 text-center">%s <a href="%s" class="text-decoration-underline">%s</a></div>',
                    __('Already have an account?'),
                    route('public.account.login'),
                    __('Sign In')
                ),
            ])
            ->add('filters', HtmlField::class, [
                'html' => apply_filters(BASE_FILTER_AFTER_LOGIN_OR_REGISTER_FORM, null, Account::class),
            ]);
    }
}
