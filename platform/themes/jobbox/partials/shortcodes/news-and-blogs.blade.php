@if ($posts->count() > 0)
    @php
        $title = $shortcode->title;
        $subtitle = $shortcode->subtitle;
    @endphp

    @switch($shortcode->style)
        @case('style-2')
            <section class="section-box mt-50 mb-50">
                <div class="container">
                    <div class="text-start">
                        @if ($title)
                            <h2 class="section-title mb-10 wow animate__animated animate__fadeInUp">
                                {!! BaseHelper::clean($title) !!}
                            </h2>
                        @endif

                        @if ($subtitle)
                            <p class="font-lg color-text-paragraph-2 wow animate__animated animate__fadeInUp">
                                {!! BaseHelper::clean($subtitle) !!}
                            </p>
                        @endif
                    </div>
                </div>
                <div class="container">
                    <div class="mt-50">
                        <div class="box-swiper style-nav-top">
                            <div class="swiper-container swiper-group-3 swiper">
                                <div class="swiper-wrapper pb-70 pt-5">
                                    @foreach($posts as $post)
                                        <div class="swiper-slide">
                                            <div class="card-grid-3 hover-up wow animate__animated animate__fadeIn">
                                                <div class="text-center card-grid-3-image">
                                                    <a href="{{ $post->url }}">
                                                        <figure>
                                                            <img alt="{{ $post->name }}" src="{{ RvMedia::getImageUrl($post->image, 'featured', false, RvMedia::getDefaultImage()) }}">
                                                        </figure>
                                                    </a>
                                                </div>
                                                <div class="card-block-info">
                                                    <div class="tags mb-15">
                                                        @if($post->tags)
                                                            @foreach ($post->tags as $tag)
                                                                <a class="btn btn-tag" href="{{ $tag->url }}">{{ $tag->name }}</a>&nbsp;
                                                            @endforeach
                                                        @endif
                                                    </div>
                                                    <h5 class="post-title"><a href="{{ $post->url }}">{{ $post->name }}</a></h5>
                                                    <p class="mt-10 color-text-paragraph font-sm post-description">{{ $post->description }}</p>
                                                    <div class="card-2-bottom mt-20">
                                                        <div class="row">
                                                            <div class="col-lg-6 col-6">
                                                                @php
                                                                    $author = $post->author;
                                                                    $isDisplayAuthor = (! theme_option('hide_blog_post_author') && $author->getKey());
                                                                @endphp

                                                                <div class="d-flex">
                                                                    @if ($isDisplayAuthor)
                                                                        <img class="img-rounded" src="{{ $author->avatar_url }}" alt="{{ $author->name }}">
                                                                    @endif

                                                                    <div class="info-right-img">
                                                                        @if ($isDisplayAuthor)
                                                                            <span class="font-sm font-bold color-brand-1 op-70">{{ $author->name }}</span>
                                                                            <br>
                                                                        @endif
                                                                        <span class="font-xs color-text-paragraph-2">{{ $post->created_at->translatedFormat('M d, Y') }}</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div @class(['col-lg-6 text-md-end col-6', 'pt-15' => $isDisplayAuthor])>
                                                                <span class="color-text-paragraph-2 font-xs">
                                                                @php
                                                                    $timeReading = $post->getMetaData('time_to_read', true) ?:  number_format(strlen(strip_tags($post->content)) / 300);
                                                                @endphp

                                                                @if($timeReading > 1)
                                                                    {{ __(':time mins to read', ['time' => $timeReading]) }}
                                                                @elseif($timeReading === 1)
                                                                    {{ __(':time min to read', ['time' => $timeReading]) }}
                                                                @endif
                                                            </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            <div class="swiper-button-next"></div>
                            <div class="swiper-button-prev"></div>
                        </div>
                        <div class="text-center">
                            <a class="btn btn-brand-1 mt--30 hover-up view-more-posts" href="{{ get_blog_page_url() }}">
                                {{ __('View more') }}
                            </a>
                        </div>
                    </div>
                </div>
            </section>
            @break
        @default
            <section class="section-box mt-50 mb-50 news-or-blogs">
                <div class="container">
                    <div class="text-center">
                        @if ($title)
                            <h2 class="section-title mb-10 wow animate__animated animate__fadeInUp">{!! BaseHelper::clean($title) !!}</h2>
                        @endif

                        @if($subtitle)
                            <p class="font-lg color-text-paragraph-2 wow animate__animated animate__fadeInUp">{!! BaseHelper::clean($subtitle) !!}</p>
                        @endif
                    </div>
                </div>
                <div class="container">
                    <div class="mt-50">
                        <div class="box-swiper style-nav-top">
                            <div class="swiper-container swiper-group-3 swiper">
                                <div class="swiper-wrapper pb-70 pt-5">
                                    @foreach($posts as $post)
                                        <div class="swiper-slide">
                                            <div class="card-grid-3 hover-up wow animate__animated animate__fadeIn">
                                                <div class="text-center card-grid-3-image">
                                                    <a href="{{ $post->url }}">
                                                        <figure>
                                                            <img alt="{{ $post->name }}" src="{{ RvMedia::getImageUrl($post->image, 'featured', false, RvMedia::getDefaultImage()) }}">
                                                        </figure>
                                                    </a>
                                                </div>
                                                <div class="card-block-info">
                                                    <div class="tags mb-15">
                                                        @if ($post->tags)
                                                            @foreach ($post->tags as $tag)
                                                                <a class="btn btn-tag" href="{{ $tag->url }}">{{ $tag->name }}</a>&nbsp;
                                                            @endforeach
                                                        @endif
                                                    </div>
                                                    <h5><a href="{{ $post->url }}">{{ $post->name }}</a></h5>
                                                    <p class="mt-10 color-text-paragraph font-sm post-description">{{ $post->description }}</p>
                                                    <div class="card-2-bottom mt-20">
                                                        <div class="row">
                                                            <div class="col-lg-6 col-6">
                                                                @php
                                                                    $author = $post->author;
                                                                    $isDisplayAuthor = (! theme_option('hide_blog_post_author') && $author->getKey());
                                                                @endphp

                                                                <div class="d-flex">
                                                                    @if ($isDisplayAuthor)
                                                                        <img class="img-rounded" src="{{ $author->avatar_url }}" alt="{{ $author->name }}">
                                                                    @endif
                                                                    <div class="info-right-img">
                                                                        @if ($isDisplayAuthor)
                                                                            <span class="font-sm font-bold color-brand-1 op-70">{{ $author->name }}</span>
                                                                            <br>
                                                                        @endif

                                                                        <span class="font-xs color-text-paragraph-2">{{ $post->created_at->translatedFormat('M d, Y') }}</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div @class(['col-lg-6 text-md-end col-6', 'pt-15' => $isDisplayAuthor])>
                                                                <span class="color-text-paragraph-2 font-xs">
                                                                    @php
                                                                        $timeReading = $post->getMetaData('time_to_read', true) ?:  number_format(strlen(strip_tags($post->content)) / 300);
                                                                    @endphp

                                                                    @if($timeReading > 1)
                                                                        {{ __(':time mins to read', ['time' => $timeReading]) }}
                                                                    @elseif($timeReading === 1)
                                                                        {{ __(':time min to read', ['time' => $timeReading]) }}
                                                                    @endif
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            <div class="swiper-button-next"></div>
                            <div class="swiper-button-prev"></div>
                            <div class="text-center">
                                <a class="btn btn-brand-1 mt--30 hover-up view-more-posts" href="{{ get_blog_page_url() }}">
                                    {{ __('View more') }}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            @break
    @endswitch
@endif
