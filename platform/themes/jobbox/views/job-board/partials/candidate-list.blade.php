@if($candidates->total())
    @foreach($candidates as $candidate)
        <div class="col-xl-3 col-lg-4 col-md-6">
            <div class="card-grid-2 hover-up">
                <div class="card-grid-2-image-left">
                    <div @class(['card-grid-2-image-rd', 'online' => $candidate->available_for_hiring])>
                        <a href="{{ $candidate->url }}">
                            <figure>
                                <img alt="{{ $candidate->name }}" src="{{ $candidate->avatar_thumb_url }}">
                            </figure>
                        </a>
                    </div>
                    <div class="card-profile pt-10">
                        <a href="{{ $candidate->url }}">
                            <h5>{{ $candidate->name }}</h5>
                        </a>
                        <span class="font-xs color-text-mutted text-truncate">{{ $candidate->description }}</span>
                    </div>
                </div>
                <div class="card-block-info">
                    <p class="font-xs color-text-paragraph-2">{{ Str::limit(strip_tags(BaseHelper::clean($candidate->bio))) }}</p>
                    <div class="employers-info align-items-center justify-content-center mt-15">
                        <div class="row justify-content-between align-items-center">
                            <div class="col-md-6">
                                <span class="d-flex align-items-center">
                                    <i class="fi-rr-marker mr-5 ml-0"></i>
                                    <span class="font-sm color-text-mutted text-truncate">
                                        {{ $candidate->state_name ? $candidate->state_name . ',' : null }} {{ $candidate->country->code }}
                                    </span>
                                </span>
                            </div>
                            @if(JobBoardHelper::isEnabledReview())
                                <div class="col-md-6">
                                    <div class="mt-5">
                                        {!! Theme::partial('rating-star', ['star' => round($candidate->reviews_avg_star)]) !!}
                                        <span class="font-xs color-text-mutted ml-5">
                                            <span>(</span>
                                            <span>{{ $candidate->reviews_count }}</span>
                                            <span>)</span>
                                        </span>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

    {{ $candidates->withQueryString()->links(Theme::getThemeNamespace('partials.pagination')) }}
@else
    <p class="text-center text-muted">{{ __('No candidates!') }}</p>
@endif
