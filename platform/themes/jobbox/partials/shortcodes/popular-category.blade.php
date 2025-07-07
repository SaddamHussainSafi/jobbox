@switch($shortcode->style)
    @case('style-5')
        <section class="section-box mt-50 mb-30 bg-brand-2 pt-60 pb-60">
            <div class="container">
                <div class="row">
                    <div class="col-xl-5">
                        <div class="pt-70">
                            <h2 class="color-white mb-20">{!! BaseHelper::clean($shortcode->title) !!}</h2>
                            <p class="color-white mb-30">{!! BaseHelper::clean($shortcode->subtitle) !!}</p>
                            @if($categoriesPageURL = JobBoardHelper::getJobCategoriesPageURL())
                                <div class="mt-20">
                                    <a class="btn btn-brand-1 btn-icon-more hover-up" href="{{ $categoriesPageURL }}">{{ __('Explore') }}</a>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="col-xl-7">
                        <div class="box-swiper mt-50 layout-brand-1">
                            <div class="swiper-container swiper-group-3-explore mh-none swiper">
                                <div class="swiper-wrapper pb-70 pt-5">
                                    @foreach($categories->loadMissing('metadata') as $category)
                                        <div class="swiper-slide hover-up">
                                            <div class="card-grid-5 card-category hover-up" style="background-image: url('{{ RvMedia::getImageUrl($category->getMetaData('job_category_image', true)) ?: Theme::asset()->url('imgs/page/homepage2/img-big1.png') }}')">
                                                <a href="{{ $category->url }}">
                                                    <div class="box-cover-img">
                                                        <div class="content-bottom">
                                                            <h6 class="color-white mb-5">{{ $category->name }}</h6>
                                                            <p class="color-white font-xs">
                                                                {!! __('<span>:count</span> <span>Jobs Available</span>', ['count' => $category->active_jobs_count]) !!}
                                                            </p>
                                                        </div>
                                                    </div>
                                                </a>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            <div class="swiper-button-next swiper-button-next-1"></div>
                            <div class="swiper-button-prev swiper-button-prev-1"> </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @break
    @default
        <section class="section-box mt-50">
            <div class="section-box wow animate__animated animate__fadeIn">
                <div class="container">
                    <div class="text-start">
                        <h2 class="section-title mb-10 wow animate__animated animate__fadeInUp">{!! BaseHelper::clean($shortcode->title) !!}</h2>
                        <p class="font-lg color-text-paragraph-2 wow animate__animated animate__fadeInUp">{!! BaseHelper::clean($shortcode->subtitle) !!}</p>
                    </div>
                    <div class="box-swiper mt-50">
                        <div class="swiper-container swiper-group-6 mh-none swiper">
                            <div class="swiper-wrapper pb-70 pt-5">
                                @foreach($categories->loadMissing('metadata') as $category)
                                    <div class="swiper-slide hover-up">
                                        <div class="card-grid-5 card-category hover-up" style="background-image: url('{{ MetaBox::getMetaData($category, 'job_category_image', true) ? RvMedia::getImageUrl(MetaBox::getMetaData($category, 'job_category_image', true)) : Theme::asset()->url('imgs/page/homepage2/img-big1.png') }}')">
                                            <a href="{{ $category->url }}">
                                                <div class="box-cover-img">
                                                    <div class="content-bottom">
                                                        <h6 class="color-white mb-5">{{ $category->name }}</h6>
                                                        <p class="color-white font-xs">
                                                            <span>{{ $category->active_jobs_count }}</span>
                                                            <span>{{ __('Jobs Available') }}</span>
                                                        </p>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="swiper-button-next swiper-button-next-1"></div>
                        <div class="swiper-button-prev swiper-button-prev-1"></div>
                    </div>
                </div>
            </div>
        </section>
    @break
@endswitch
