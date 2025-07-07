<section class="section-box mt-80">
    <div class="container">
        <div class="box-info-contact">
            <div class="row">
                <div class="col-lg-3 col-md-6 col-sm-12">
                    <a href="{{ $shortcode->url }}">
                        <img src="{{ RvMedia::getImageUrl($shortcode->logo_company) }}" alt="{{ $shortcode->company_name }}">
                    </a>
                    <div class="font-sm color-text-paragraph">{{ $shortcode->company_address }}
                        <br>
                        {{ __('Phone :phone', ['phone' => $shortcode->company_phone]) }}
                        <br>
                        {{ __('Email :email', ['email' => $shortcode->company_email]) }}
                    </div>
                    <a class="text-uppercase color-brand-2 link-map" href="https://maps.google.com/?q={{ $shortcode->company_address }}">{{ __('View map') }}</a>
                </div>
                <div class="col-1"></div>
                <div class="col-lg-8 col-md-6 sm-12">
                    <div class="row pl-15">
                        @for($i = 0; $i <= 6; $i++)
                            <div class="col-lg-4 col-md-6 col-sm-12 ">
                                <h6>{{ $shortcode->{'branch_company_name_' . $i} }}</h6>
                                <p class="font-sm color-text-paragraph mb-20">
                                    {{ $shortcode->{'branch_company_address_' . $i} }}
                                </p>
                            </div>
                        @endfor
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
