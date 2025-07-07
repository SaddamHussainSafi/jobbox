<div class="col-12 jobs-listing">
    <div class="card-grid-2 hover-up">
        @if($job->is_featured)
            <span class="flash"></span>
        @endif
        <div class="row">
            <div class="@if($job->tags->isNotEmpty()) col-lg-6 col-md-6 @else col-lg-12 col-md-12 @endif col-sm-12">
                <div class="card-grid-2-image-left">
                    <div class="image-box"><img src="{{ $company->logo_thumb }}" alt="{{ $company->name }}"></div>
                    <div class="right-info">
                        <a class="name-job" href="{{ $job->url }}">{{ $job->name }}</a>
                        <span class="location-small">{{ $job->location }}</span>
                    </div>
                </div>
            </div>

            @if($job->tags->isNotEmpty())
                <div class="col-lg-6 text-start text-md-end pr-60 col-md-6 col-sm-12">
                    <div class="pl-15 mb-15 mt-30">
                        @foreach($job->tags->take(5) as $tag)
                            <a class="btn btn-grey-small mr-5 mb-2" href="{{ $tag->url }}">{{ $tag->name }}</a>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
        <div class="card-block-info">
            <div class="mt-5">
                @if ($job->jobTypes->isNotEmpty())
                    <span class="card-briefcase">
                        @foreach($job->jobTypes as $jobType)
                            {{ $jobType->name }}
                            @if (!$loop->last)
                                ,
                            @endif
                        @endforeach
                    </span>
                @endif
                <span class="card-time">
                    <span>{{ $job->created_at->diffForHumans() }}</span>
                </span>
            </div>
            <p class="font-sm color-text-paragraph mt-10">{{ $job->description }}</p>
            <div class="card-2-bottom mt-20">
                <div class="row">
                    <div class="col-lg-7 col-7">
                        {!! Theme::partial('salary', compact('job')) !!}
                    </div>
                    <div class="col-lg-5 col-5 text-end">
                        {!! Theme::partial('apply-button', compact('job')) !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
