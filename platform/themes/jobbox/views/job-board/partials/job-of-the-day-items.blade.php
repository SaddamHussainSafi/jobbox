@if (count($jobs) > 0)
    {!! Theme::partial('loading') !!}

    @foreach ($jobs as $job)
        @if ($style === 'style-1')
            <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-12">
                <div @class(['card-grid-2 hover-up items', 'featured-job-item' => $job->is_featured])>
                    <div class="card-grid-2-image-left job-item">
                        @if ($job->is_featured)
                            <span class="flash"></span>
                        @endif
                        <div class="image-box">
                            <img src="{{ $job->company_logo_thumb }}" alt="{{ $job->company->name }}">
                        </div>
                        <div class="right-info">
                            @if (! $job->hide_company)
                                <a class="name-job" title="{{ $job->company_name }}" href="{{ $job->company_url }}">{{ $job->company_name }}</a>
                            @endif
                            <span class="location-small">{{ $job->location }}</span>
                        </div>
                    </div>
                    <div class="card-block-info">
                        <div class="h6 fw-bold text-truncate">
                            <a href="{{ $job->url }}" title="{{ $job->name }}">{{ $job->name }}</a>
                        </div>
                        <div class="mt-5">
                            @if($job->jobTypes->isNotEmpty())
                                <span class="card-briefcase">
                                    @foreach($job->jobTypes as $jobType)
                                        {{ $jobType->name }}@if (!$loop->last), @endif
                                    @endforeach
                                </span>
                            @endif
                            <span class="card-time">{{ $job->created_at->diffForHumans() }}</span></div>
                        <p class="font-sm color-text-paragraph job-description mt-15" title="{{ $job->description }}">{{ $job->description }}</p>
                        <div class="mt-15">
                            @if($job->tags->isNotEmpty())
                                @foreach($job->tags->take(10) as $tag)
                                    <a class="btn btn-grey-small mr-5 mb-2" href="{{ $tag->url }}">{{ $tag->name }}</a>
                                @endforeach
                            @endif
                        </div>
                        <div class="card-2-bottom mt-15">
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
        @elseif($style === 'style-3')
            <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-12">
                <div class="card-grid-2 grid-bd-16 hover-up item-grid @if ($job->is_featured) featured-job-item @endif">
                    <div class="card-grid-2-image">
                        @if ($job->jobTypes->isNotEmpty())
                            <span class="lbl-hot bg-green">
                                @if($job->jobTypes)
                                    @foreach($job->jobTypes as $jobType)
                                        <span>{{ $jobType->name }}</span>@if (!$loop->last), @endif
                                    @endforeach
                                @endif
                            </span>
                        @endif
                        <div class="image-box">
                            <figure>
                                <img src="{{ $job->company_logo_thumb }}" alt="{{ $job->name }}">
                            </figure>
                        </div>
                    </div>
                    <div class="card-block-info">
                        <div class="h6 font-bold">
                            <a href="{{ $job->url }}" class="name-job" title="{{ $job->name }}">{{ $job->name }}</a>
                        </div>
                        <div class="mt-5">
                            <span class="card-location mr-15">{{ $job->location }}</span>
                            <span class="card-time">{{ $job->created_at->diffForHumans() }}</span>
                        </div>
                        <div class="card-2-bottom mt-20">
                            <div class="row">
                                @if ($job->skills->isNotEmpty())
                                    <div class="col-12 mb-2">
                                        @foreach ($job->skills as $skill)
                                            <span class="btn btn-tags-sm mr-5">{{ $skill->name }}</span>&nbsp;
                                        @endforeach
                                    </div>
                                @endif
                                <div class="col-12">
                                    {!! Theme::partial('salary', compact('job')) !!}
                                </div>
                            </div>
                        </div>
                        <p class="font-sm color-text-paragraph job-description mt-20">{{ $job->description }}</p>
                    </div>
                </div>
            </div>
        @else
             @php($jobs->loadMissing('metadata'))
            <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-12">
                <div class="card-grid-2 grid-bd-16 hover-up item-grid @if ($job->is_featured) featured-job-item @endif">
                    <div class="card-grid-2-image">
                        @if ($job->jobTypes->isNotEmpty())
                            <span class="lbl-hot bg-green">
                                @foreach($job->jobTypes as $jobType)
                                    <span>{{ $jobType->name }}</span>@if (!$loop->last), @endif
                                @endforeach
                            </span>
                        @endif
                        <div class="image-box">
                            <figure>
                                <img src="{{ $job->getMetaData('featured_image', true) ? RvMedia::getImageUrl($job->getMetaData('featured_image', true)) : $job->company_logo_thumb }}" alt="{{ $job->name }}">
                            </figure>
                        </div>
                    </div>
                    <div class="card-block-info">
                        <div class="h6 fw-bold">
                            <a href="{{ $job->url }}" class="name-job" title="{{ $job->name }}">{{ $job->name }}</a>
                        </div>
                        <div class="mt-5">
                            <span class="card-location mr-15">{{ $job->location }}</span>
                            <span class="card-time">{{ $job->created_at->diffForHumans() }}</span>
                        </div>
                        <div class="card-2-bottom mt-20">
                            <div class="row">
                                @if($job->skills->isNotEmpty())
                                    <div class="col-12 mb-2">
                                        @foreach ($job->skills as $skill)
                                            <span class="btn btn-tags-sm mr-5">{{ $skill->name }}</span>&nbsp;
                                        @endforeach
                                    </div>
                                @endif
                                <div class="col-12">
                                   {!! Theme::partial('salary', compact('job')) !!}
                                </div>
                            </div>
                        </div>
                        <p class="font-sm color-text-paragraph job-description mt-20">{{ $job->description }}</p>
                    </div>
                </div>
            </div>
        @endif
    @endforeach
@else
    <div class="col-12">
        <p class="text-center">{{ __('No data available') }}</p>
    </div>
@endif
