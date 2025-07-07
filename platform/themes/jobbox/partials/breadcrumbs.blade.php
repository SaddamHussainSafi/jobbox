<section class="section-box">
    <div
        @class(['breadcrumb-cover', 'bg-img-about' => Theme::get('pageCoverImage')])
        style="background-image: url({{ RvMedia::getImageUrl(Theme::get('pageCoverImage') ?: theme_option('background_breadcrumb'), null, false, RvMedia::getDefaultImage()) }})"
    >
        <div class="container">
            <div class="row">
                <div class="col-lg-6">
                    <h2 class="mb-10">{{ Theme::get('pageTitle') }}</h2>
                    @if (Theme::get('pageDescription'))
                        <p class="font-lg color-text-paragraph-2">{{ Theme::get('pageDescription') }}</p>
                    @endif
                </div>
                <div class="col-lg-6 text-md-end">
                    <ul class="breadcrumbs @if (Theme::get('pageDescription')) mt-40 @endif">
                        @foreach (Theme::breadcrumb()->getCrumbs() as $crumb)
                            @if ($loop->first)
                                <li>
                                    <a href="{{ $crumb['url'] }}">
                                        <span class="fi-rr-home icon-home"></span>
                                        {!! BaseHelper::clean($crumb['label']) !!}
                                    </a>
                                </li>
                            @elseif (! $loop->last)
                                <li>
                                    <a href="{{ $crumb['url'] }}">{!! BaseHelper::clean($crumb['label']) !!}</a>
                                </li>
                            @else
                                <li>{!! BaseHelper::clean($crumb['label']) !!}</li>
                            @endif
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>
