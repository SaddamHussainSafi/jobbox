@php($isEnabledAnimation = theme_option('enabled_animation_when_loading_page', 'yes') === 'yes')

@switch($shortcode->style)
    @case('style-2')
        <div class="bg-homepage1"></div>
        <section class="section-box">
            <div class="banner-hero hero-2" @if($shortcode->background_image) style="background-image: url({{ RvMedia::getImageUrl($shortcode->background_image) }}) !important;" @endif>
                <div class="banner-inner">
                    <div class="block-banner">
                        <h1 class="text-42 color-white wow animate__animated animate__fadeInUp">
                            {!! BaseHelper::clean(str_replace($shortcode->highlight_text, '<span class="color-green">' . $shortcode->highlight_text . '</span>', $shortcode->title)) !!}
                        </h1>
                        <div class="font-lg font-regular color-white mt-20 wow animate__animated animate__fadeInUp" data-wow-delay=".1s">
                            {!! BaseHelper::clean($shortcode->description) !!}
                        </div>
                        {!! Theme::partial('job-search-box', ['trendingKeywords' => $shortcode->trending_keywords]) !!}
                    </div>
                    <div class="mt-60">
                        <div class="row">
                            @for($i = 1; $i <= 4 ; $i++)
                                @if($shortcode->{'counter_title_' . $i})
                                <div class="col-lg-3 col-sm-3 col-6 text-center mb-20">
                                    <div class="d-inline-block text-start">
                                        <h4 class="color-white">
                                            <span class="count">
                                                {!! BaseHelper::clean($shortcode->{'counter_number_' . $i}) !!}
                                            </span>
                                        </h4>
                                        <p class="font-sm color-text-mutted">{{ $shortcode->{'counter_title_' . $i} }}</p>
                                    </div>
                                </div>
                                @endif
                            @endfor
                        </div>
                    </div>
                </div>
            </div>
            <div class="container">
                <div class="list-brands mt-40 mb-30">
                    <div class="box-swiper">
                        <div class="swiper-container swiper-group-9 swiper">
                            <div class="swiper-wrapper">
                                @foreach($featuredCompanies as $company)
                                    <div class="swiper-slide">
                                        <a href="{{ $company->url }}">
                                            <img src="{{ RvMedia::getImageUrl($company->logo_thumb) }}" alt="{{ $company->name }}">
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        @break

    @case('style-3')
        <section class="section-box">
            <div class="banner-hero hero-2 hero-3" @if($shortcode->background_image) style="background-image: url({{ RvMedia::getImageUrl($shortcode->background_image) }}) !important;" @endif>
                <div class="banner-inner">
                    <div class="block-banner">
                        <h1 class="text-42 color-white wow animate__animated animate__fadeInUp">
                            {!! BaseHelper::clean(str_replace($shortcode->highlight_text, '<span class="color-green">'. $shortcode->highlight_text .'</span>', $shortcode->title)) !!}
                        </h1>
                        <div class="font-lg font-regular color-white mt-20 wow animate__animated animate__fadeInUp" data-wow-delay=".1s">
                            {!! BaseHelper::clean($shortcode->description) !!}
                        </div>

                        {!! Theme::partial('job-search-box', ['trendingKeywords' => $shortcode->trending_keywords]) !!}
                    </div>
                </div>
                <div class="container mt-60">
                    <div class="box-swiper mt-50">
                        <div class="swiper-container swiper-group-5 swiper">
                            <div class="swiper-wrapper pb-25 pt-5">
                                @foreach($categories as $category)
                                    <div class="swiper-slide hover-up">
                                        <a href="{{ $category->url }}">
                                            <div class="item-logo">
                                                <div class="image-left">
                                                    @if ($iconImage = $category->getMetaData('icon_image', true))
                                                        <img src="{{ RvMedia::getImageUrl($iconImage) }}" alt="{{ $category->name }}">
                                                    @elseif ($icon = $category->getMetaData('icon', true))
                                                        <i class="{{ $icon }}"></i>
                                                    @endif
                                                </div>
                                                <div class="text-info-right">
                                                    <h4>{!! BaseHelper::clean($category->name) !!}</h4>
                                                    <p class="font-xs">
                                                        {!! BaseHelper::clean(__(':count <span>Jobs Available</span>', ['count' => $category->jobs_count])) !!}
                                                    </p>
                                                </div>
                                            </div>
                                        </a>
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

    @case('style-4')
        <section class="section-box mb-70">
            <div class="banner-hero hero-1 banner-homepage6" @if($shortcode->background_image) style="background-image: url({{ RvMedia::getImageUrl($shortcode->background_image) }}) !important;" @endif>
                <div class="banner-inner py-4">
                    <div class="row">
                        <div class="col-xl-12 col-lg-12">
                            <div class="block-banner text-center pb-40 pt-40">
                                <h1 class="heading-banner wow animate__ animate__fadeInUp animated">
                                    {!! BaseHelper::clean(str_replace($shortcode->highlight_text, '<span class="color-brand-2">' . $shortcode->highlight_text . '</span>', $shortcode->title)) !!}
                                </h1>
                                <p class="font-lg color-text-paragraph mt-20">{!! BaseHelper::clean($shortcode->description) !!}</p>
                            </div>
                        </div>
                    </div>
                    <div class="box-search-2">
                        <div class="block-banner form-none-shadow">
                            {!! Theme::partial('job-search-box', ['style' => $shortcode->style, 'trendingKeywords' => $shortcode->trending_keywords]) !!}
                        </div>
                    </div>
                </div>
            </div>
        </section>
        @break

    @case('style-5')
        <section class="section-box mb-70">
            <div class="banner-hero hero-1 banner-homepage5" @if($shortcode->background_image) style="background-image: url({{ RvMedia::getImageUrl($shortcode->background_image) }}) !important;" @endif>
                <div class="banner-inner">
                    <div class="row">
                        <div class="col-xl-7 col-lg-12">
                            <div class="block-banner">
                                <h1 class="heading-banner wow animate__animated animate__fadeInUp">
                                    {!! BaseHelper::clean($shortcode->title) !!}
                                </h1>
                                <div class="banner-description mt-20 wow animate__animated animate__fadeInUp" data-wow-delay=".1s">
                                    {!! BaseHelper::clean($shortcode->description) !!}
                                </div>
                                <div class="mt-30">
                                    @if($shortcode->primary_button_url && $shortcode->primary_button_label)
                                        <a href="{{ $shortcode->primary_button_url }}" class="btn btn-default mr-15">{{ $shortcode->primary_button_label }}</a>
                                    @endif
                                    @if($shortcode->secondary_button_url && $shortcode->secondary_button_label)
                                        <a href="{{ $shortcode->secondary_button_url }}" class="btn btn-border-brand-2">{{ $shortcode->secondary_button_label }}</a>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-5 col-lg-12 d-none d-xl-block col-md-6">
                            <div class="banner-imgs">
                                @for($i = 1; $i <= 6; $i++)
                                    <div class="banner-{{ $i }} shape-1">
                                        <img class="img-responsive" alt="{{ $i }}" src="{{ RvMedia::getImageUrl($shortcode->{'banner_image_' . $i}) }}">
                                    </div>
                                @endfor
                            </div>
                        </div>
                    </div>
                    <div class="box-search-2">
                        <div class="block-banner">
                            {!! Theme::partial('job-search-box', ['trendingKeywords' => $shortcode->trending_keywords]) !!}
                        </div>
                    </div>
                </div>
            </div>
        </section>
        @break

    @default
        <div class="bg-homepage1" @if($shortcode->background_image) style="background-image: url({{ RvMedia::getImageUrl($shortcode->background_image) }}) !important;" @endif></div>
        <section class="section-box">
            <div class="banner-hero hero-1">
                <div class="banner-inner">
                    <div class="row">
                        <div class="col-xl-8 col-lg-12">
                            <div class="block-banner">
                                <h1 class="heading-banner wow animate__animated animate__fadeInUp">
                                    {!! BaseHelper::clean(str_replace($shortcode->highlight_text, '<span class="color-brand-2">' . $shortcode->highlight_text . '</span>', $shortcode->title)) !!}
                                </h1>
                                <div class="banner-description mt-20 wow animate__animated animate__fadeInUp" data-wow-delay=".1s">
                                    {!! BaseHelper::clean($shortcode->description) !!}
                                </div>
                                {!! Theme::partial('job-search-box', ['trendingKeywords' => $shortcode->trending_keywords]) !!}
                            </div>
                        </div>
                        <div class="col-xl-4 col-lg-12 d-none d-xl-block col-md-6">
                            <div class="banner-imgs">
                                @if($url = $shortcode->banner_image_1)
                                    <div @class(['block-1', 'shape-1' => $isEnabledAnimation])>
                                        <img class="img-responsive" alt="{{ $shortcode->banner_image_1 }}" src="{{ RvMedia::getImageUrl($url) }}">
                                    </div>
                                @endif
                                @if($url = $shortcode->banner_image_2)
                                    <div @class(['block-2', 'shape-2' => $isEnabledAnimation])>
                                        <img class="img-responsive" alt="{{ $shortcode->banner_image_2 }}" src="{{ RvMedia::getImageUrl($url) }}">
                                    </div>
                                @endif
                                @if($url = $shortcode->icon_top_banner)
                                    <div @class(['block-3', 'shape-3' => $isEnabledAnimation])>
                                        <img class="img-responsive" alt="{{ $shortcode->icon_top_banner }}" src="{{ RvMedia::getImageUrl($url) }}">
                                    </div>
                                @endif
                                @if($url = $shortcode->icon_bottom_banner)
                                    <div @class(['block-4', 'shape-4' => $isEnabledAnimation])>
                                        <img class="img-responsive" alt="{{ $shortcode->icon_bottom_banner }}" src="{{ RvMedia::getImageUrl($url) }}">
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        @break
@endswitch
