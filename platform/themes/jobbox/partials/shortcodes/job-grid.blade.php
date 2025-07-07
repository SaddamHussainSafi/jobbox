@switch($shortcode->style)
    @case('style-2')
        <section class="section-box mt-50 mb-30 bg-border-3 pt-100 pb-100">
            <div class="container">
                <div class="row">
                    <div class="col-lg-6">
                        <img class="bdrd-10" src="{{ RvMedia::getImageUrl($shortcode->image) }}" alt="jobBox">
                    </div>
                    <div class="col-lg-6">
                        <div class="pl-30">
                            <h5 class="color-brand-2 mb-15 mt-15">{!! BaseHelper::clean($shortcode->subtitle) !!}</h5>
                            <h2 class="color-brand-1 mt-0 mb-15">{!! BaseHelper::clean($shortcode->title) !!}</h2>
                            <p class="font-lg color-text-paragraph-2">{!! BaseHelper::clean($shortcode->description) !!}</p>
                            @if($shortcode->button_text && $shortcode->button_url)
                                <div class="mt-20">
                                    <a href="{{ $shortcode->button_url }}" class="btn btn-default">{{ $shortcode->button_text }}</a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @break
    @default
        <section class="section-box overflow-visible mt-100 mb-100">
            <div class="container">
                <div class="row">
                    <div class="col-lg-6 col-sm-12">
                        <div class="box-image-job">
                            <img class="img-job-1" alt="{{ $shortcode->image_job_1 }}" src="{{ RvMedia::getImageUrl($shortcode->image_job_1) }}">
                            <img class="img-job-2" alt="{{ $shortcode->image_job_2 }}" src="{{ RvMedia::getImageUrl($shortcode->image_job_2) }}">
                            <figure class="wow animate__animated animate__fadeIn">
                                <img alt="{{ $shortcode->image }}" src="{{ RvMedia::getImageUrl($shortcode->image) }}">
                            </figure>
                        </div>
                    </div>
                    <div class="col-lg-6 col-sm-12">
                        <div class="content-job-inner">
                            <span class="color-text-mutted text-32">{!! BaseHelper::clean($shortcode->subtitle) !!}</span>
                            <h2 class="text-52 wow animate__animated animate__fadeInUp">{!! BaseHelper::clean(str_replace($shortcode->highlight_text, '<span class="color-brand-2">' . $shortcode->high_light_title_text . '</span>', $shortcode->title)) !!}</h2>
                            <div class="mt-40 pr-50 text-md-lh28 wow animate__animated animate__fadeInUp">{!! BaseHelper::clean($shortcode->description) !!}</div>
                            <div class="mt-40">
                                <div class="wow animate__animated animate__fadeInUp">
                                    <a class="btn btn-default" href="{{ BaseHelper::clean($shortcode->button_url) }}">{!! BaseHelper::clean($shortcode->button_text) !!}</a>
                                    <a class="btn btn-link" href="{{ BaseHelper::clean($shortcode->link_text_url) }}">{!! BaseHelper::clean($shortcode->link_text) !!}</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @break
@endswitch
