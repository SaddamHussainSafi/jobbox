@if ($locations->count() > 0)
    @switch($style)
        @case('style-2')
            <section class="section-box mt-50">
                <div class="container">
                    <div class="text-start">
                        @if ($title)
                            <h2 class="section-title mb-10 wow animate__animated animate__fadeInUp">{{ $title }}</h2>
                        @endif

                        @if ($description)
                            <p class="font-lg color-text-paragraph-2 wow animate__animated animate__fadeInUp">{{ $description }}</p>
                        @endif
                    </div>
                    <div class="container">
                        <div class="row mt-50">
                            @foreach($locations->chunk(3) as $locations)
                                @foreach($locations as $location)
                                    @php
                                        $jobListUrl = $location instanceof \Botble\Location\Models\City ? route('public.jobs-by-city', $location->slug) : route('public.jobs-by-state', $location->slug);
                                    @endphp
                                    <div class="col-xl-{{ 3 + $loop->index }} col-lg-{{ 3 + $loop->index }} col-md-5 col-sm-12 col-12">
                                        <div class="card-image-top hover-up">
                                            <a href="{{ $jobListUrl }}" aria-label="{{ $location->name }}">
                                                <div class="image" style="background-image: url({{ $location->image ? RvMedia::getImageUrl($location->image) : Theme::asset()->url('imgs/page/homepage1/location1.png')}});"></div>
                                            </a>
                                            <div class="informations">
                                                <a href="{{ $jobListUrl }}">
                                                    <div class="h5 fw-bold">{{ ($location->name) }}, {{ $location->country->name }}</div>
                                                </a>
                                                <div class="row">
                                                    <div class="col-lg-6 col-6">
                                                    <span class="text-14 color-text-paragraph-2">
                                                        @if($location->companies_count > 1)
                                                            {{ __(':count companies', ['count' => $location->companies_count]) }}
                                                        @elseif($location->companies_count == 1)
                                                            {{ __(':count company', ['count' => $location->companies_count]) }}
                                                        @else
                                                            {{ __('No company') }}
                                                        @endif
                                                    </span>
                                                    </div>
                                                    <div class="col-lg-6 col-6 text-end">
                                                    <span class="color-text-paragraph-2 text-14">
                                                        @if($location->jobs_count > 1)
                                                            {{ __(':count jobs', ['count' => $location->jobs_count]) }}
                                                        @elseif($location->jobs_count == 1)
                                                            {{ __(':count job', ['count' => $location->jobs_count]) }}
                                                        @else
                                                            {{ __('No jobs') }}
                                                        @endif
                                                    </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @endforeach
                        </div>
                    </div>
                </div>
            </section>
            @break
        @default
            <section class="section-box mt-50 job-by-location">
                <div class="container">
                    <div class="text-center">
                        @if ($title)
                            <h2 class="section-title mb-10 wow animate__animated animate__fadeInUp">{{ $title }}</h2>
                        @endif

                        @if ($description)
                            <p class="font-lg color-text-paragraph-2 wow animate__animated animate__fadeInUp">{{ $description }}</p>
                        @endif
                    </div>
                </div>
                <div class="container">
                    <div class="row mt-50">
                        @php
                            $gridClasses = [[3, 4, 5, 4, 5, 3], [5, 7, 7, 5, 7, 5]]
                        @endphp
                        @foreach($locations as $location)
                            @php
                                $jobListUrl = $location instanceof \Botble\Location\Models\City ? route('public.jobs-by-city', $location->slug) : route('public.jobs-by-state', $location->slug);
                            @endphp
                            <div class="col-xl-{{ $gridClasses[0][$loop->index] }} col-lg-{{ $gridClasses[0][$loop->index] }} col-md-{{ $gridClasses[1][$loop->index] }} col-sm-12 col-12">
                                <div class="card-image-top hover-up">
                                    <a href="{{ $jobListUrl }}" aria-label="{{ $location->name }}">
                                        <div class="image" style="background-image: url({{ $location->image ? RvMedia::getImageUrl($location->image) : Theme::asset()->url('imgs/page/homepage1/location1.png')}});"></div>
                                    </a>
                                    <div class="informations">
                                        <a href="{{ $jobListUrl }}">
                                            <div class="h5 fw-bold">{{ ($location->name) }}, {{ $location->country->name }}</div>
                                        </a>
                                        <div class="row">
                                            <div class="col-lg-6 col-6">
                                            <span class="text-14 color-text-paragraph-2">
                                                @if($location->companies_count > 1)
                                                    {{ __(':count companies', ['count' => $location->companies_count]) }}
                                                @elseif($location->companies_count == 1)
                                                    {{ __(':count company', ['count' => $location->companies_count]) }}
                                                @else
                                                    {{ __('No company') }}
                                                @endif
                                            </span>
                                            </div>
                                            <div class="col-lg-6 col-6 text-end">
                                            <span class="color-text-paragraph-2 text-14">
                                                @if($location->jobs_count > 1)
                                                    {{ __(':count jobs', ['count' => $location->jobs_count]) }}
                                                @elseif($location->jobs_count == 1)
                                                    {{ __(':count job', ['count' => $location->jobs_count]) }}
                                                @else
                                                    {{ __('No jobs') }}
                                                @endif
                                            </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </section>
            @break
    @endswitch
@endif
