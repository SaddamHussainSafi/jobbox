@switch($shortcode->style)
    @case('style-2')
        <section class="section-box mt-30 job-of-the-day">
            <div class="container">
                <div class="text-start">
                    <h2 class="section-title mb-10 wow animate__animated animate__fadeInUp">
                        {!! BaseHelper::clean($shortcode->title) !!}
                    </h2>
                    <p class="font-lg color-text-paragraph-2 wow animate__animated animate__fadeInUp">
                        {!! BaseHelper::clean($shortcode->subtitle) !!}
                    </p>
                    @if (count($categories))
                        <div class="list-tabs mt-40">
                        <div class="nav nav-tabs" role="tablist">
                            @foreach($categories->loadMissing('metadata') as $category)
                                <div role="tab">
                                    <a
                                        @class(['active' => $loop->first, 'category-item'])
                                        id="nav-tab-job-{{ $category->id }}"
                                        href="#tab-job-{{ $category->id }}"
                                        data-url="{{ route('public.ajax.jobs-by-category', $category->getKey()) }}?limit={{ (int)$shortcode->limit ?: 8 }}"
                                        data-style="{{ $shortcode->style }}"
                                    >
                                        @if($iconImage = $category->getMetaData('icon_image', true))
                                            <img src="{{ RvMedia::getImageUrl($iconImage) }}" alt="{{ $category->name }}">
                                        @elseif($icon = $category->getMetaData('icon', true))
                                            <i class="{{ $icon }}"></i>
                                        @endif
                                        {{ $category->name }}
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
                @if (count($categories))
                    <div class="mt-50">
                        <div class="tab-content" id="myTabContent-1">
                            <div
                                @class(['tab-pane fade show active'])
                                id="tab-job-{{ $firstCategoryId = $categories->first()->id }}"
                                aria-labelledby="tab-job-{{ $firstCategoryId }}"
                            >
                            <div class="row job-of-the-day-list">
                                @include(Theme::getThemeNamespace('views.job-board.partials.job-of-the-day-items'), [
                                    'jobs' => $jobs,
                                    'style' => $shortcode->style
                                ])
                            </div>
                        </div>
                    </div>
                @endif
                </div>
            </div>
        </section>
        @break
    @case('style-3')
        <section class="section-box mt-70 job-of-the-day">
            <div class="container">
                <div class="text-center">
                    <h2 class="section-title mb-10 wow animate__animated animate__fadeInUp">
                        {!! BaseHelper::clean($shortcode->title) !!}
                    </h2>
                    <p class="font-lg color-text-paragraph-2 wow animate__animated animate__fadeInUp">
                        {!! BaseHelper::clean($shortcode->subtitle) !!}
                    </p>
                    @if (count($categories))
                        <div class="list-tabs mt-40">
                            <div class="nav nav-tabs" role="tablist">
                                @foreach($categories as $category)
                                    <div role="tab">
                                        <a
                                            @class(['active' => $loop->first, 'category-item'])
                                            id="nav-tab-job-{{ $category->id }}"
                                            href="#tab-job-{{ $category->id }}"
                                            data-style="{{ $shortcode->style }}"
                                            data-url="{{ route('public.ajax.jobs-by-category', $category->getKey()) }}?limit={{ (int)$shortcode->limit ?: 8 }}"
                                        >
                                            <img src="{{ RvMedia::getImageUrl($category->getMetadata('icon_image', true)) }}" alt="{{ $category->name }}">
                                            {{ $category->name }}
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
                @if (count($categories))
                    <div class="mt-50">
                        <div class="tab-content" id="myTabContent-1">
                            <div
                                @class(['tab-pane fade show active'])
                                id="tab-job-{{ $firstCategoryId = $categories->first()->id }}"
                                data-style="{{ $shortcode->style }}"
                                aria-labelledby="tab-job-{{ $firstCategoryId }}"
                            >
                            <div class="row job-of-the-day-list">
                                @include(Theme::getThemeNamespace('views.job-board.partials.job-of-the-day-items'), [
                                    'jobs' => $jobs,
                                    'style' => $shortcode->style
                                ])
                            </div>
                        </div>
                    </div>
                @endif
            </div>
            </div>
        </section>
        @break
    @default
        <section class="section-box mt-50 job-of-the-day">
            <div class="container">
                <div class="text-center">
                    <h2 class="section-title mb-10 wow animate__animated animate__fadeInUp">
                        {!! BaseHelper::clean($shortcode->title) !!}
                    </h2>
                    <p class="font-lg color-text-paragraph-2 wow animate__animated animate__fadeInUp">
                        {!! BaseHelper::clean($shortcode->subtitle) !!}
                    </p>
                    @if (count($categories))
                        <div class="list-tabs mt-40">
                            <div class="nav nav-tabs" role="tablist">
                                @foreach($categories->loadMissing('metadata') as $category)
                                    <div role="tab">
                                        <a
                                            @class(['active' => $loop->first, 'category-item'])
                                            id="nav-tab-job-{{ $category->id }}"
                                            href="#tab-job-{{ $category->id }}"
                                            data-url="{{ route('public.ajax.jobs-by-category', $category->getKey()) }}?limit={{ (int)$shortcode->limit ?: 8 }}"
                                            data-style="{{ $shortcode->style }}"
                                        >
                                            @if($iconImage = $category->getMetaData('icon_image', true))
                                                <img src="{{ RvMedia::getImageUrl($iconImage) }}" alt="{{ $category->name }}">
                                            @elseif($icon = $category->getMetaData('icon', true))
                                                <i class="{{ $icon }}"></i>
                                            @endif
                                            {{ $category->name }}
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
                @if (count($categories))
                    <div class="mt-70">
                        <div class="tab-content" id="myTabContent-1">
                            <div
                                @class(['tab-pane fade show active'])
                                id="tab-job-{{ $firstCategoryId = $categories->first()->id }}"
                                aria-labelledby="tab-job-{{ $firstCategoryId }}"
                            >
                                <div class="row job-of-the-day-list">
                                    @include(Theme::getThemeNamespace('views.job-board.partials.job-of-the-day-items'), [
                                        'jobs' => $jobs,
                                        'style' => $shortcode->style,
                                    ])
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </section>
    @break
@endswitch
