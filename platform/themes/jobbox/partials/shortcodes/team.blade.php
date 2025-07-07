<div class="container mt-50">
    <div class="text-center">
        <h6 class="f-18 color-text-mutted text-uppercase">{!! BaseHelper::clean($shortcode->subtitle) !!}</h6>
        <h2 class="section-title mb-10 wow animate__ animate__fadeInUp animated" style="visibility: visible; animation-name: fadeInUp;">
            {!! BaseHelper::clean($shortcode->title) !!}
        </h2>
        <p class="font-sm color-text-paragraph w-lg-50 mx-auto wow animate__ animate__fadeInUp animated" style="visibility: visible; animation-name: fadeInUp;">
            {!! BaseHelper::clean($shortcode->description) !!}
        </p>
    </div>
    <div class="row mt-70">
        @foreach($teams as $team)
            <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12 mb-md-30">
            <div class="card-grid-4 text-center hover-up">
                <div class="image-top-feature">
                    <figure>
                        <img alt="{{ $team->name }}" src="{{ RvMedia::getImageUrl($team->photo, null, false, RvMedia::getDefaultImage()) }}">
                    </figure>
                </div>
                <div class="card-grid-4-info">
                    <h5 class="mt-10">{{ $team->name }}</h5>
                    <p class="font-xs color-text-paragraph-2 mt-5 mb-5">{{ $team->title }}</p>
                    <span class="card-location">{{ $team->location }}</span>
                    @if ($socials = $team->socials)
                        <div class="text-center mt-30">
                            @foreach(['facebook', 'twitter', 'instagram'] as $social)
                                @if($url = Arr::get($socials, $social))
                                    <a class="share-{{ $social }} social-share-link" href="{{ $url }}"></a>
                                @endif
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
