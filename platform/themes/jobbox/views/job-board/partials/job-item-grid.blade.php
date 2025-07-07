<div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 col-12 jobs-item job-grid">
    <div class="card-grid-2 hover-up @if ($job->is_featured) featured-job-item @endif">
        <div class="card-grid-2-image-left">
            @if($job->is_featured)
                <span class="flash"></span>
            @endif
            <div class="image-box">
                <img src="{{ RvMedia::getImageUrl($job->company_logo_thumb) }}" alt="{{ $job->name }}">
            </div>
            <div class="right-info">
                <a class="name-job" href="{{ $job->company_url ?: 'javascript:void(0);' }}">
                    {{ $job->company_name ?: $job->name }}
                </a>
                <span class="location-small">
                    {{ $job->location }}
                </span>
            </div>
        </div>
        <div class="card-block-info">
            <h6 class="text-truncate">
                <a href="{{ $job->url }}" title="{{ $job->name }}">{{ $job->name }}</a>
            </h6>
            <div class="mt-5">
                <span class="card-briefcase">
                    @if($job->jobTypes->isNotEmpty())
                        @foreach($job->jobTypes as $jobType)
                            {{ $jobType->name }}@if (!$loop->last), @endif
                        @endforeach
                    @endif
                </span>
                <span class="card-time">{{ $job->created_at->diffForHumans() }}</span>
            </div>
            <p class="font-sm color-text-paragraph mt-15 job-description">{{ $job->description }}</p>
            <div class="mt-30">
                @if($job->tags->isNotEmpty())
                    @foreach($job->tags->take(10) as $tag)
                        <a class="btn btn-grey-small mr-5 mb-2" href="{{ $tag->url }}">{{ $tag->name }}</a>
                    @endforeach
                @endif
            </div>
            <div class="card-2-bottom mt-30">
                <div class="row">
                    <div class="col-12 salary-information">
                        {!! Theme::partial('salary', compact('job')) !!}
                    </div>
                    <div class="col-12 mt-3">
                        {!! Theme::partial('apply-button', compact('job')) !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
