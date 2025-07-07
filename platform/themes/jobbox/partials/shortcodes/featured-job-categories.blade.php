@switch($shortcode->style)
    @case('style-2')
        <section class="section-box mt-110 bg-cat" @if($shortcode->background_image) style="background-image: url({{ RvMedia::getImageUrl($shortcode->background_image) }});" @endif>
            <div class="section-box wow animate__animated animate__fadeIn">
                <div class="container">
                    <div class="text-center">
                        <h2 class="section-title mb-10 wow animate__animated animate__fadeInUp">
                            {!! BaseHelper::clean($shortcode->title) !!}
                        </h2>
                        <p class="font-lg color-text-paragraph-2 wow animate__animated animate__fadeInUp">
                            {!! BaseHelper::clean($shortcode->subtitle) !!}
                        </p>
                    </div>
                    <div class="box-swiper mt-50">
                        <div class="swiper-container swiper-group-5 swiper">
                            <div class="swiper-wrapper pb-70 pt-5">
                                @foreach($categories->chunk(2) as $jobs)
                                    <div class="swiper-slide hover-up">
                                        @foreach($jobs as $job)
                                            <a href="{{ $job->url }}">
                                                <div class="item-logo">
                                                    <div class="image-left">
                                                        @if ($iconImage = $job->getMetaData('icon_image', true))
                                                            <img src="{{ RvMedia::getImageUrl($iconImage) }}" alt="{{ $job->name }}">
                                                        @elseif ($icon = $job->getMetaData('icon', true))
                                                            <i class="{{ $icon }}"></i>
                                                        @endif
                                                    </div>
                                                    <div class="text-info-right">
                                                        <div class="h5" title="{{ $job->name }}">{!! BaseHelper::clean(Str::limit($job->name, 20) ) !!}</div>
                                                        <p class="font-xs">
                                                            @if($job->active_jobs_count > 1)
                                                                {!! BaseHelper::clean(__(':number <span> Jobs Available </span>', ['number' => $job->active_jobs_count ])) !!}
                                                            @elseif($job->active_jobs_count == 1)
                                                                {!! BaseHelper::clean(__(':number <span> Job Available </span>', ['number' => $job->active_jobs_count ])) !!}
                                                            @else
                                                                {!! BaseHelper::clean(__('No <span> Job Available </span>')) !!}
                                                            @endif
                                                        </p>
                                                    </div>
                                                </div>
                                            </a>
                                        @endforeach
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="swiper-button-next"></div>
                        <div class="swiper-button-prev"></div>
                    </div>
                </div>
            </div>
        </section>
        @break
    @default
        <section class="section-box mt-80" @if($shortcode->background_image) style="background-image: url({{ RvMedia::getImageUrl($shortcode->background_image) }});" @endif>
            <div class="section-box wow animate__animated animate__fadeIn">
                <div class="container">
                    <div class="text-center">
                        <h2 class="section-title mb-10 wow animate__animated animate__fadeInUp">
                            {!! BaseHelper::clean($shortcode->title) !!}
                        </h2>
                        <p class="font-lg color-text-paragraph-2 wow animate__animated animate__fadeInUp">
                            {!! BaseHelper::clean($shortcode->subtitle) !!}
                        </p>
                    </div>
                    <div class="box-swiper mt-50">
                        <div class="swiper-container swiper-group-5 swiper">
                            <div class="swiper-wrapper pb-70 pt-5">
                                @foreach($categories->chunk(2) as $jobs)
                                    <div class="swiper-slide hover-up">
                                        @foreach($jobs as $job)
                                            <a href="{{ $job->url }}">
                                                <div class="item-logo">
                                                    <div class="image-left">
                                                        @if ($iconImage = $job->getMetaData('icon_image', true))
                                                            <img src="{{ RvMedia::getImageUrl($iconImage) }}" alt="{{ $job->name }}">
                                                        @elseif ($icon = $job->getMetaData('icon', true))
                                                            <i class="{{ $icon }}"></i>
                                                        @endif
                                                    </div>
                                                    <div class="text-info-right">
                                                        <div class="h6">{{ $job->name }}</div>
                                                        <p class="font-xs">
                                                            @if($job->active_jobs_count > 1)
                                                                {!! BaseHelper::clean(__(':number <span> Jobs Available </span>', ['number' => $job->active_jobs_count ])) !!}
                                                            @elseif($job->active_jobs_count == 1)
                                                                {!! BaseHelper::clean(__(':number <span> Job Available </span>', ['number' => $job->active_jobs_count ])) !!}
                                                            @else
                                                                {!! BaseHelper::clean(__('No <span> Job Available </span>')) !!}
                                                            @endif
                                                        </p>
                                                    </div>
                                                </div>
                                            </a>
                                        @endforeach
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="swiper-button-next"></div>
                        <div class="swiper-button-prev"></div>
                    </div>
                </div>
            </div>
        </section>
        @break
@endswitch
