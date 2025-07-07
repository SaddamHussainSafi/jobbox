<section class="pt-100 login-register">
    <div class="container">
        <div class="row login-register-cover">
            <div class="col-lg-4 col-md-6 col-sm-12 mx-auto">
                <div class="text-center">
                    <p class="font-sm text-brand-2">{{ __('Forgot Password?') }}</p>
                    <h2 class="mt-10 mb-5 text-brand-1">{{ __('Reset your password.') }}</h2>
                    <p class="font-sm text-muted mb-30">{{ __('Enter your email and instructions will be sent to you!') }}</p>
                </div>
                {!!
                    $form
                        ->formClass('login-register text-start mt-20 auth-form')
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
