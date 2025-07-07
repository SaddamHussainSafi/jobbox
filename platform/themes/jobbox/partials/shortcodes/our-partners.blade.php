<div class="section-box mt-70">
    <div class="container">
        <div class="box-trust">
            <div class="row">
                <div class="left-trust col-lg-2 col-md-3 col-sm-3 col-3">
                    <h4 class="color-text-paragraph-2">{!! BaseHelper::clean($shortcode->title) !!}</h4>
                </div>
                <div class="right-logos col-lg-10 col-md-9 col-sm-9 col-9">
                    <div class="box-swiper">
                        <div class="swiper-container swiper-group-7 swiper">
                            <div class="swiper-wrapper">
                                @for($i = 1; $i <= 10; $i++)
                                    @if($shortcode->{'image_' . $i} && $shortcode->{'url_' . $i} && $shortcode->{'name_' . $i})
                                        <div class="swiper-slide">
                                            <a href="{{ $shortcode->{'url_' . $i} }}">
                                                <img src="{{ RvMedia::getImageUrl($shortcode->{'image_' . $i}) }}" alt="{{ $shortcode->{'name_' . $i} }}">
                                            </a>
                                        </div>
                                    @endif
                                @endfor
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
