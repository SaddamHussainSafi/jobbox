@switch($shortcode->style)
    @case('style-3')
        <section class="section-box mt-70 top-candidates">
            <div class="container">
                <div class="text-center">
                    <h2 class="section-title mb-10 wow animate__animated animate__fadeInUp">
                        {!! BaseHelper::clean($shortcode->title) !!}
                    </h2>
                    <p class="font-lg color-text-paragraph-2 wow animate__animated animate__fadeInUp">
                        {!! BaseHelper::clean($shortcode->description) !!}
                    </p>
                </div>
                <div class="mt-50">
                    <div class="row">
                        @foreach($candidates as $candidate)
                            <div class="col-xl-3 col-lg-4 col-md-6">
                                <div class="card-grid-2 hover-up">
                                    <div class="card-grid-2-image-left">
                                        <div class="card-grid-2-image-rd online">
                                            <a href="{{ $candidate->url }}">
                                                <figure>
                                                    <img alt="{{ $candidate->name }}" src="{{ $candidate->avatar_thumb_url }}">
                                                </figure>
                                            </a>
                                        </div>
                                        <div class="card-profile pt-10">
                                            <a href="{{ $candidate->url }}">
                                                <h5 class="candidate-name" title="{{ $candidate->name }}">{{ $candidate->name }}</h5>
                                            </a>
                                            <span class="font-xs color-text-mutted candidate-description" title="{{ $candidate->description }}">{{ $candidate->description }}</span>
                                            @if(JobBoardHelper::isEnabledReview())
                                                <div class="rate-reviews-small pt-5">
                                                    {!! Theme::partial('rating-star', ['star' => round($candidate->reviews_avg_star)]) !!}
                                                    <span class="ml-10 color-text-mutted font-xs">
                                                        ({{ $candidate->reviews_count }})
                                                    </span>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="card-block-info">
                                        <p class="font-xs color-text-paragraph-2 candidate-bio">
                                            {!! Str::limit(BaseHelper::clean($candidate->bio), 80) !!}
                                        </p>
                                        <div class="employers-info align-items-center justify-content-center mt-15">
                                            <div class="row">
                                                <div class="col-12">
                                                    <span class="d-flex align-items-center">
                                                        <i class="fi-rr-marker mr-5 ml-0"></i>
                                                        <span class="font-sm color-text-mutted text-truncate">
                                                            {{ $candidate->address }}
                                                        </span>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    @if ($candidatePageUrl = JobBoardHelper::getJobCandidatesPageURL())
                        <div class="row mt-40 mb-60">
                            <div class="col-lg-12">
                                <div class="text-center">
                                    <a class="btn btn-brand-1 btn-icon-load mt--30 hover-up" href="{{ $candidatePageUrl }}">
                                        {{ __('View More') }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </section>
    @break
    @case('style-5')
        <section class="section-box mt-70 top-candidates">
            <div class="container">
                <div class="text-start">
                    <h2 class="section-title mb-10 wow animate__animated animate__fadeInUp">
                        {!! BaseHelper::clean($shortcode->title) !!}
                    </h2>
                    <p class="font-lg color-text-paragraph-2 wow animate__animated animate__fadeInUp">
                        {!! BaseHelper::clean($shortcode->description) !!}
                    </p>
                </div>
                <div class="mt-50 card-bg-white">
                    <div class="row">
                        @foreach($candidates as $candidate)
                            <div class="col-xl-3 col-lg-4 col-md-6">
                                <div class="card-grid-2 hover-up">
                                    <div class="card-grid-2-image-left">
                                        <div class="card-grid-2-image-rd online">
                                            <a href="{{ $candidate->url }}">
                                                <figure>
                                                    <img alt="{{ $candidate->name }}" src="{{ $candidate->avatar_thumb_url }}">
                                                </figure>
                                            </a>
                                        </div>
                                        <div class="card-profile pt-10">
                                            <a href="{{ $candidate->url }}">
                                                <h5 class="candidate-name" title="{{ $candidate->name }}">{{ $candidate->name }}</h5>
                                            </a>
                                            <span class="font-xs color-text-mutted candidate-description" title="{{ $candidate->description }}">{{ $candidate->description }}</span>
                                            @if(JobBoardHelper::isEnabledReview())
                                                <div class="rate-reviews-small pt-5">
                                                    {!! Theme::partial('rating-star', ['star' => round($candidate->reviews_avg_star)]) !!}
                                                    <span class="ml-10 color-text-mutted font-xs">({{ $candidate->reviews_count }})</span>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="card-block-info">
                                        <p class="font-xs color-text-paragraph-2 candidate-bio">
                                            {!! Str::limit(BaseHelper::clean($candidate->bio), 80) !!}
                                        </p>
                                        <div class="employers-info align-items-center justify-content-center mt-15">
                                            <div class="row">
                                                <div class="col-12">
                                                    <span class="d-flex align-items-center">
                                                        <i class="fi-rr-marker mr-5 ml-0"></i>
                                                        <span class="font-sm color-text-mutted text-truncate">{{ $candidate->address }}</span>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    @if($candidatePageURL = JobBoardHelper::getJobCandidatesPageURL())
                        <div class="row mt-40 mb-60">
                            <div class="col-lg-12">
                                <div class="text-center">
                                    <a class="btn btn-brand-1 mt--30 hover-up" href="{{ $candidatePageURL }}">{{ __('View More') }}</a>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </section>
    @break
@endswitch
