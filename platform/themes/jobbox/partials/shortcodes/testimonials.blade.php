@switch($shortcode->style)
    @case('style-2')
        <section class="section-box mt-0">
            <div class="section-box wow animate__animated animate__fadeIn">
                <div class="container">
                    <div class="text-center">
                        <h2 class="section-title mb-10 wow animate__animated animate__fadeInUp">
                            {!! BaseHelper::clean($shortcode->title) !!}
                        </h2>
                        <p class="font-lg color-text-paragraph-2 wow animate__animated animate__fadeInUp">
                            {!! BaseHelper::clean($shortcode->description) !!}
                        </p>
                    </div>
                    <div class="box-swiper mt-50">
                        <div class="swiper-container swiper-group-4-border swiper" id="testimonial-slider-2">
                            <div class="swiper-wrapper pb-70 pt-5">
                                @foreach($testimonials as $testimonial)
                                    <div class="swiper-slide hover-up">
                                        <div class="card-review-1">
                                            <div class="image-review"> <img src="{{ RvMedia::getImageUrl($testimonial->image, 'thumb', false, RvMedia::getDefaultImage()) }}" alt="{{ $testimonial->name }}"></div>
                                            <div class="review-info">
                                                <div class="review-name">
                                                    <h5>{{ $testimonial->name }}</h5><span class="font-xs">{{ $testimonial->company }}</span>
                                                </div>
                                                <div class="review-comment">{!! $testimonial->content !!}</div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <div class="swiper-pagination swiper-pagination-style-border"></div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        @break
    @default
        <section class="section mt-50">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-6">
                        <div class="section-title text-center mb-4 pb-2">
                            <h2 class="text-center mb-15 wow animate__animated animate__fadeInUp">
                                {!! BaseHelper::clean($shortcode->title) !!}
                            </h2>
                            <div class="font-lg color-text-paragraph-2 text-center wow animate__animated animate__fadeInUp">
                                {!! BaseHelper::clean($shortcode->description) !!}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mt-50 justify-content-center">
                    <div class="col-lg-12">
                        <div class="swiper pb-5" id="testimonial-slider">
                            <div class="swiper-wrapper pb-70 pt-5">
                                @foreach($testimonials as $testimonial)
                                    <div class="swiper-slide swiper-group-3">
                                        <div class="card-grid-6 hover-up">
                                            <div class="card-text-desc mt-10">
                                                <p class="font-md color-text-paragraph">{!! $testimonial->content !!}</p>
                                            </div>
                                            <div class="card-image">
                                                <div class="image">
                                                    <figure><img alt="{{ $testimonial->name }}" src="{{ RvMedia::getImageUrl($testimonial->image, 'thumb', false, RvMedia::getDefaultImage()) }}" /></figure>
                                                </div>
                                                <div class="card-profile">
                                                    <h6>{{ $testimonial->name }}</h6><span>{{ $testimonial->company }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @break
@endswitch

