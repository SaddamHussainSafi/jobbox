<section class="section-box mt-50">
    <div class="post-loop-grid">
        <div class="container">
            <div class="text-center">
                <h6 class="f-18 color-text-mutted text-uppercase">{!! BaseHelper::clean($shortcode->subtitle) !!}</h6>
                <h2 class="section-title mb-10 wow animate__ animate__fadeInUp animated" style="visibility: visible; animation-name: fadeInUp;">
                    {!! BaseHelper::clean($shortcode->title) !!}
                </h2>
                <p class="font-sm color-text-paragraph wow animate__ animate__fadeInUp w-lg-50 mx-auto animated" style="visibility: visible; animation-name: fadeInUp;">
                    {!! BaseHelper::clean($shortcode->description) !!}
                </p>
            </div>
            <div class="row mt-70">
                <div class="col-lg-6 col-md-12 col-sm-12">
                    <img src="{{ RvMedia::getImageUrl($shortcode->image) }}" alt="{{__('Company Image')}}">
                </div>
                <div class="col-lg-6 col-md-12 col-sm-12">
                    <h3 class="mt-15">{!! BaseHelper::clean($shortcode->title_box) !!}</h3>
                    <div class="mt-20">
                        <p class="font-md color-text-paragraph mt-20">{!! BaseHelper::clean($shortcode->description_box) !!}</p>
                    </div>
                    @if ($shortcode->url && $shortcode->text_button_box)
                        <div class="mt-30">
                            <a class="btn btn-brand-1" href="{{ $shortcode->url }}">
                                {!! BaseHelper::clean($shortcode->text_button_box) !!}
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
