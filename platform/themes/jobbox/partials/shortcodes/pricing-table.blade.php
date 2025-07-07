<section class="section-box mt-90 mb-50">
    <div class="container">
        <h2 class="text-center mb-15">
            {!! BaseHelper::clean($shortcode->title) !!}
        </h2>
        <div class="font-lg color-text-paragraph-2 text-center">
            {!! BaseHelper::clean($shortcode->subtitle) !!}
        </div>
        <div class="max-width-price">
            <div class="block-pricing mt-70">
                <div class="row">
                    @foreach ($packages as $package)
                        <div class="col-xl-4 col-lg-6 col-md-6">
                            <div class="box-pricing-item">
                                <h3>{{ $package->name }}</h3>
                                <div class="box-info-price">
                                    <span class="text-price color-brand-2">{{ format_price($package->price) }}</span>
                                </div>
                                <div class="border-bottom mb-30"></div>
                                <ul class="list-package-feature">
                                    @if ($package->number_of_listings )
                                        <li>
                                            <svg width="28" height="28" viewBox="0 0 28 28" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <circle opacity="0.1" cx="14" cy="14" r="14" fill="{{ theme_option('primary_color', '#3C65F5') }}"/>
                                                <path d="M19 10.5L11.5 18L8.5 15" stroke="{{ theme_option('primary_color', '#3C65F5') }}" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                            </svg>
                                            {{ __(':number Listings', ['number' => $package->number_of_listings]) }}
                                        </li>
                                    @endif

                                    @if ($package->account_limit === 1)
                                        <li>
                                            <svg width="28" height="28" viewBox="0 0 28 28" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <circle opacity="0.1" cx="14" cy="14" r="14" fill="{{ theme_option('primary_color', '#3C65F5') }}"/>
                                                <path d="M19 10.5L11.5 18L8.5 15" stroke="{{ theme_option('primary_color', '#3C65F5') }}" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                            </svg>

                                            {{ __('Limited purchase by account') }}
                                        </li>
                                    @elseif ($package->account_limit === null)
                                        <li>
                                            <svg width="28" height="28" viewBox="0 0 28 28" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <circle opacity="0.1" cx="14" cy="14" r="14" fill="{{ theme_option('primary_color', '#3C65F5') }}"/>
                                                <path d="M19 10.5L11.5 18L8.5 15" stroke="{{ theme_option('primary_color', '#3C65F5') }}" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                            </svg>
                                            {{ __('Unlimited purchase by account') }}
                                        </li>
                                    @endif
                                </ul>
                                <div>
                                    <a class="btn btn-border" href="{{ auth('account')->check() ? route('public.account.packages') : route('public.account.login') }}">{{ __('Choose plan') }}</a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>
