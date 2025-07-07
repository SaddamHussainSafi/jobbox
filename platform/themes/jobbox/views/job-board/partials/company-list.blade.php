<div class="col-md-6 col-xl-4 col-12 company-list-item">
    <div class="card-grid-2 hover-up">
        @if($company->is_featured)
            <span class="flash"></span>
        @endif
        <div class="row">
            <div class="col-12">
                <div class="card-grid-2-image-left">
                    <div class="image-box"><img src="{{ RvMedia::getImageUrl($company->logo_thumb) }}"
                                                alt="{{ $company->name }}"></div>
                    <div class="right-info"><a class="name-job"
                                               href="{{ $company->url }}">{{ $company->name }}</a><span
                            class="location-small">{{ implode(', ', array_filter([$company->state_name, $company->country_name])) }}</span></div>
                </div>
            </div>
        </div>
        <div class="card-block-info">
            <h4 class="text-truncate"><a href="{{ $company->url }}" title="{{ $company->name }}">{{ $company->name }}</a></h4>
            <div class="card-2-bottom mt-20">
                <div class="row">
                    <div class="col-lg-7 col-7">
                        @if(JobBoardHelper::isEnabledReview())
                            <div class="mt-5">
                                {!! Theme::partial('rating-star', ['star' => round($company->reviews_avg_star)]) !!}
                                <span class="font-xs color-text-mutted ml-5">
                                    <span>(</span>
                                    <span>{{ $company->reviews_count }}</span>
                                    <span>)</span>
                                </span>
                            </div>
                        @endif
                    </div>
                    <div class="col-lg-5 col-5 text-end">
                        <div class="text-start text-md-end">
                            <a class="btn btn-apply-now" href="{{ $company->url }}">
                                {!! Theme::partial('job-count', compact('company')) !!}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
