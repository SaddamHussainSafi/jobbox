<div class="col-xl-3 col-lg-3 col-md-4 col-sm-6 col-12 company-grid">
    <div class="card-grid-1 hover-up wow animate__animated animate__fadeIn">
        @if($company->is_featured)
            <span class="flash"></span>
        @endif
        <div class="image-box">
            <a href="{{ $company->url }}">
                <img src="{{ RvMedia::getImageUrl($company->logo) }}" alt="{{ $company->name }}">
            </a>
        </div>
        <div class="info-text mt-10">
            <h5 class="font-bold"><a href="{{ $company->url }}">{{ $company->name }}</a></h5>
            @if(JobBoardHelper::isEnabledReview())
                <div class="mt-5">
                    {!! Theme::partial('rating-star', ['star' => round($company->reviews_avg_star)]) !!}
                    <span class="font-xs color-text-mutted ml-10">
                    <span>(</span>
                    <span>{{ $company->reviews_count }}</span>
                    <span>)</span>
                </span>
                </div>
            @endif
            <span class="card-location">{{ $company->location }}</span>
            <div class="mt-30">
                <a class="btn btn-grey-big" href="{{ $company->url }}">
                    {!! Theme::partial('job-count', compact('company')) !!}
                </a>
            </div>
        </div>
    </div>
</div>
