<section class="pt-100 login-register">
    <div class="container">
        <div class="row login-register-cover">
            <div class="col-lg-4 col-md-6 col-sm-12 mx-auto">
                <div class="text-center">
                    <p class="font-sm text-brand-2">{{ __('Register') }}</p>
                    <h2 class="mt-10 mb-5 text-brand-1">{{ __("Let's Get Started") }}</h2>
                    <p class="font-sm text-muted mb-30">{{ __('Sign Up and get access to all the features.') }}</p>
                </div>
                {!!
                    $form
                        ->formClass('login-register text-start mt-20 auth-form')
                        ->when(setting('job_board_enabled_register_as_employer', 1), function ($form) {
                            return $form
                                ->modify('is_employer', 'html', ['html' => sprintf('<div class="mb-3 position-relative"><label class="cb-container">
                                    <input type="checkbox" name="is_employer" value="1">
                                    <span class="text-small">%s</span>
                                    <span class="checkmark"></span>
                                </label></div>', __('Is Employer?'))], true);
                        })
                         ->modify('agree_terms_and_policy', 'html', ['html' => sprintf('<div class="mb-3 position-relative"><label class="cb-container">
                                    <input type="checkbox" name="agree_terms_and_policy" value="1">
                                    <span class="text-small">%s</span>
                                    <span class="checkmark"></span>
                                </label></div>', __('Agree our terms and policy'))], true)
                        ->renderForm()
                !!}
            </div>
            <div class="img-1 d-none d-lg-block">
                <img class="shape-1" src="{{ RvMedia::getImageUrl(theme_option('auth_background_image_1')) }}" alt="{{ theme_option('site_name') }}">
            </div>
            <div class="img-2">
                <img src="{{ RvMedia::getImageUrl(theme_option('auth_background_image_2')) }}" alt="{{ theme_option('site_name') }}">
            </div>
        </div>
    </div>
</section>
