@php
    Theme::asset()->usePath()->add('leaflet-css', 'plugins/leaflet/leaflet.css');
    Theme::asset()->container('footer')->usePath()->add('leaflet-js', 'plugins/leaflet/leaflet.js');
@endphp

<section class="section-box-2">
    @if (is_plugin_active('ads'))
        {!! apply_filters('ads_render', null, 'job_before', ['class' => 'my-2 text-center']) !!}
    @endif

    <div class="container">
        <div class="banner-hero banner-image-single">
           <div class="wrap-cover-image">
               @if(! $job->hide_company && $company->id && $company->cover_image_url)
                   <img src="{{ $company->cover_image_url }}" alt="{{ $company->name }}">
               @elseif (theme_option('default_company_cover_image'))
                   <img src="{{ RvMedia::getImageUrl(theme_option('default_company_cover_image')) }}" alt="{{ $company->name }}">
               @else
                   <img src="{{ Theme::asset()->url('imgs/backgrounds/cover-image-default.png') }}" alt="{{ $company->name }}">
               @endif
           </div>
        </div>
        <div class="mt-10 d-flex align-items-center job-header-content">
            <div>
                <h1 class="h3">{{ $job->name }}
                    @if ($job->canShowSavedJob() && auth('account')->check())
                        <span @class(['ml-5', 'job-bookmark-saved', 'save-job-active' => $job->is_saved != 0])>
                            <a class="job-bookmark-action align-middle" href="{{ route('public.account.jobs.saved.action') }}" data-job-id="{{ $job->id }}">
                                <div class="d-inline-block favorite-icon">
                                    <span class="fi-rr-heart"></span>
                                </div>
                            </a>
                        </span>
                    @endif
                </h1>
                <div class="mt-0 mb-15">
                    @foreach($job->jobTypes as $jobType)
                        <span class="card-briefcase">{{ $jobType->name }}</span>
                    @endforeach
                    <span class="card-time">{{ $job->created_at->diffForHumans() }}</span>
                </div>
            </div>
            @php($classButtonApply = 'btn btn-apply-icon btn-apply btn-apply-big hover-up ml-auto')
            {!! Theme::partial('apply-button', [
                'job' => $job,
                'class' => $classButtonApply,
                'wrapClass' => 'ms-auto'
             ]) !!}
        </div>
        <div class="border-bottom pt-10 pb-10"></div>
    </div>
</section>

<section class="section-box mt-50 job-detail-section">
    <div class="container">
        <div class="row">
            <div @class(['col-md-12 col-sm-12 col-12', 'col-lg-8' => (! $job->hide_company && $company->id)])>
                <div class="job-overview">
                    <h2 class="border-bottom pb-15 mb-30 h5">{{ __('Employment Information') }}</h2>
                    <div class="row">
                        @if ($uniqueId = $job->unique_id)
                            <div class="col-12 d-flex mt-15">
                                <div class="sidebar-icon-item">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-id">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M3 4m0 3a3 3 0 0 1 3 -3h12a3 3 0 0 1 3 3v10a3 3 0 0 1 -3 3h-12a3 3 0 0 1 -3 -3z" />
                                        <path d="M9 10m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                                        <path d="M15 8l2 0" />
                                        <path d="M15 12l2 0" />
                                        <path d="M7 16l10 0" />
                                    </svg>
                                </div>
                                <div class="sidebar-text-info ml-10">
                                    <span class="text-description joblevel-icon mb-10" style="line-height: 28px !important;">{{ __('Identify') }}</span>
                                    <strong class="small-heading">{!! BaseHelper::clean($uniqueId) !!}</strong>
                                </div>
                            </div>
                        @endif


                        @if($job->categories->isNotEmpty())
                            <div class="col-md-12 d-flex mt-15">
                                <div class="sidebar-icon-item">
                                    <img src="{{ Theme::asset()->url('/imgs/page/job-single/industry.svg') }}" alt="{{ __('Industry') }}">
                                </div>
                                <div class="sidebar-text-info ml-10">
                                    <span class="text-description industry-icon mb-10">{{ __('Industry') }}</span>
                                    <span class="small-heading">
                                        @foreach ($job->categories as $category)
                                            <a href="{{ $category->url }}">{{ $category->name }}</a> @if (! $loop->last) / @endif
                                        @endforeach
                                    </span>
                                </div>
                            </div>
                        @endif

                        @if($job->careerLevel->name)
                            <div class="col-md-6 d-flex mt-15">
                                <div class="sidebar-icon-item">
                                    <img src="{{ Theme::asset()->url('imgs/page/job-single/job-level.svg') }}" alt="{{ __('Job Level') }}">
                                </div>
                                <div class="sidebar-text-info ml-10">
                                    <span class="text-description joblevel-icon mb-10">{{ __('Job Level') }}</span>
                                    <strong class="small-heading">{{ $job->careerLevel->name }}</strong>
                                </div>
                            </div>
                        @endif

                        @if($job->number_of_positions)
                            <div class="col-md-6 d-flex mt-15">
                                <div class="sidebar-icon-item">
                                    <img src="{{ Theme::asset()->url('imgs/page/job-single/job-level.svg') }}" alt="{{ __('Open positions') }}">
                                </div>
                                <div class="sidebar-text-info ml-10">
                                    <span class="text-description joblevel-icon mb-10">{{ __('Open Positions') }}</span>
                                    <strong class="small-heading">{{ $job->number_of_positions }}</strong>
                                </div>
                            </div>
                        @endif

                        @if($job->salary_from || $job->salary_to)
                            <div class="col-md-6 d-flex mt-15">
                                <div class="sidebar-icon-item">
                                    <img src="{{ Theme::asset()->url('imgs/page/job-single/salary.svg') }}" alt="{{ __('Salary') }}">
                                </div>
                                <div class="sidebar-text-info ml-10">
                                    <span class="text-description salary-icon mb-10">{{ __('Salary') }}</span>
                                    @if (! JobBoardHelper::isSalaryHiddenForGuests())
                                        <strong class="small-heading text-primary fw-bold">{{ $job->salary_text }}</strong>
                                    @else
                                        <a class="job-hidden-job-for-guest-text mb-10" href="{{ route('public.account.login') }}">
                                            {{ __('Sign in to view salary') }}
                                        </a>
                                    @endif
                                </div>
                            </div>
                        @endif

                        @if($job->jobExperience->name)
                            <div class="col-md-6 d-flex mt-15">
                                <div class="sidebar-icon-item">
                                    <img src="{{ Theme::asset()->url('imgs/page/job-single/experience.svg') }}" alt="{{ __('Experience') }}">
                                </div>
                                <div class="sidebar-text-info ml-10">
                                    <span class="text-description experience-icon mb-10">{{ __('Experience') }}</span>
                                    <strong class="small-heading">{{ $job->jobExperience->name }}</strong>
                                </div>
                            </div>
                        @endif

                        @if($job->jobTypes->isNotEmpty())
                            <div class="col-md-6 d-flex mt-15">
                                <div class="sidebar-icon-item">
                                    <img src="{{ Theme::asset()->url('imgs/page/job-single/job-type.svg') }}" alt="{{ __('Job Type') }}">
                                </div>
                                <div class="sidebar-text-info ml-10">
                                    <span class="text-description jobtype-icon mb-10">{{ __('Job Type') }}</span>
                                    <strong class="small-heading">
                                        @foreach($job->jobTypes as $jobType)
                                            {{ $jobType->name }} @if(!$loop->last), @endif
                                        @endforeach
                                    </strong>
                                </div>
                            </div>
                        @endif

                        @if($job->full_address)
                            <div class="col-md-6 d-flex mt-15">
                                <div class="sidebar-icon-item">
                                    <img src="{{ Theme::asset()->url('imgs/page/job-single/location.svg') }}" alt="{{ __('Location') }}">
                                </div>
                                <div class="sidebar-text-info ml-10">
                                    <span class="text-description mb-10">{{ __('Location') }}</span>
                                    <strong class="small-heading">
                                        {{ $job->full_address }}
                                        {{ (JobBoardHelper::isZipCodeEnabled() && $job->zip_code) ? ', ' . $job->zip_code : '' }}
                                    </strong>
                                </div>
                            </div>
                        @endif

                        @if ($job->start_date)
                            <div class="col-md-6 d-flex mt-15">
                                <div class="sidebar-icon-item">
                                    <img src="{{ Theme::asset()->url('imgs/page/job-single/deadline.svg') }}" alt="{{ __('Start date') }}">
                                </div>
                                <div class="sidebar-text-info ml-10">
                                    <span class="text-description mb-10">{{ __('Start date') }}</span>
                                    <strong class="small-heading">{{ $job->start_date->translatedFormat('d M, Y') }}</strong>
                                </div>
                            </div>
                        @endif

                        @if ($job->application_closing_date)
                                <div class="col-md-6 d-flex mt-15">
                                    <div class="sidebar-icon-item">
                                        <img src="{{ Theme::asset()->url('imgs/page/job-single/deadline.svg') }}" alt="{{ __('Apply Before') }}">
                                    </div>
                                <div class="sidebar-text-info ml-10">
                                    <span class="text-description mb-10">{{ __('Apply Before') }}</span>
                                    <strong class="small-heading text-danger">{{ $job->application_closing_date->translatedFormat('d M, Y') }}</strong>
                                </div>
                            </div>
                        @endif

                        @if($job->is_freelance)
                            <div class="col-md-6 d-flex mt-15">
                                <div class="sidebar-icon-item">
                                    <img src="{{ Theme::asset()->url('imgs/page/job-single/job-type.svg') }}" alt="{{ __('Type') }}">
                                </div>
                                <div class="sidebar-text-info ml-10">
                                    <span class="text-description mb-10">{{ __('Type') }}</span>
                                    <strong class="small-heading">
                                        {{ __('Freelance') }}
                                    </strong>
                                </div>
                            </div>
                        @endif

                        @foreach($job->customFields as $customField)
                            <div class="col-md-6 d-flex mt-15">
                                <div class="sidebar-icon-item">
                                    <img src="{{ Theme::asset()->url('imgs/page/job-single/updated.svg') }}" alt="{{ $customField->name }}">
                                </div>
                                <div class="sidebar-text-info ml-10">
                                    <span class="text-description mb-10">{{ $customField->name }}</span>
                                    <strong class="small-heading">{{ $customField->value }}</strong>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="content-single">
                    <div class="ck-content">
                        {!! BaseHelper::clean($job->content) !!}
                    </div>
                </div>

                @if ($job->skills->isNotEmpty())
                    <div class="mt-4">
                        <h5 class="mb-3">{{ __('Skills') }}</h5>
                        <div class="job-details-desc">
                            <div class="mt-4">
                                @foreach ($job->skills as $skill)
                                    <span class="badge bg-primary me-1">{{ $skill->name }}</span>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif

                @if ($job->tags->isNotEmpty())
                    <div class="mt-4">
                        <h5 class="mb-3">{{ __('Tags') }}</h5>
                        <div class="job-details-desc">
                            <div class="mt-4">
                                @foreach ($job->tags as $tag)
                                    <a href="{{ $tag->url }}">
                                        <span class="badge bg-primary me-1">{{ $tag->name }}</span>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif

                @if ($job->latitude && $job->longitude)
                    <div class="mt-4">
                        <h6 class="fs-16 mb-3">{{ __('Job location') }}</h6>
                        <div class="job-board-street-map-container">
                            <div
                                class="job-board-street-map"
                                data-popup-id="#street-map-popup-template"
                                data-center="{{ json_encode([$job->latitude, $job->longitude]) }}"
                                data-map-icon="{{ ! JobBoardHelper::isSalaryHiddenForGuests() ? $job->salary_text : $job->name }}"
                            ></div>
                        </div>
                        <div class="d-none" id="street-map-popup-template">
                            <div>
                                <table width="100%">
                                    <tr>
                                        <td width="60" class="image-company">
                                            <div>
                                                <img src="{{ $job->company_logo_thumb }}" width="40" alt="{{ $job->hide_company ? $job->company_name : $job->name }}">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="infomarker">
                                                @if ($job->has_company)
                                                    <h5>
                                                        <a href="{{ $company->url }}" target="_blank">{{ $company->name }}</a>
                                                    </h5>
                                                @endif
                                                <div class="text-info">
                                                    <strong>{{ $job->name }}</strong>
                                                </div>
                                                <div class="text-info">
                                                    <i class="mdi mdi-account"></i>
                                                    <span>{{ __(':number Vacancy', ['number' => $job->number_of_positions])}}</span>
                                                    @if ($job->jobTypes->isNotEmpty())
                                                        <span>-</span>
                                                        @foreach($job->jobTypes as $jobType)
                                                            <span>{{ $jobType->name }}@if (! $loop->last), @endif</span>
                                                        @endforeach
                                                    @endif
                                                </div>
                                                @if ($job->full_address)
                                                    <div class="text-muted">
                                                        <i class="uil uil-map"></i>
                                                        <span>{{ $job->full_address }}</span>
                                                    </div>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="single-apply-jobs">
                    <div class="row align-items-center">
                        <div class="col-md-5">
                            @php($classButtonApplyBottom = 'btn btn-default mr-15')
                            {!! Theme::partial('apply-button', [
                                'job' => $job,
                                'class' => $classButtonApplyBottom
                            ]) !!}
                        </div>

                        @include(Theme::getThemeNamespace('views.job-board.partials.share'), ['job' => $job])
                    </div>
                </div>
            </div>
            @if(! $job->hide_company && $company->id)
                <div class="col-lg-4 col-md-12 col-sm-12 col-12 pl-40 pl-lg-15 mt-lg-30">
                    <div class="sidebar-border">
                        <div class="sidebar-heading">
                            <div class="avatar-sidebar">
                                <figure>
                                    <a href="{{ $company->url }}"><img alt="{{ $company->name }}" src="{{ $company->logo_thumb }}"></a>
                                </figure>
                                <div class="sidebar-info">
                                    <a href="{{ $company->url }}"><span class="sidebar-company">{{ $company->name }}</span></a>
                                    <a class="link-underline mt-15" href="{{ $company->url }}">
                                        {{ $company->jobs_count == 1 ? __(':jobs Open Job', ['jobs' => 1]) : __(':jobs Open Jobs', ['jobs' => $company->jobs_count]) }}
                                    </a>
                                </div>
                            </div>
                        </div>
                        @if(! JobBoardHelper::isCompanyInformationHiddenForGuests() || auth('account')->check())
                            <div class="sidebar-list-job">
                                <ul class="ul-disc">
                                    @if ($company->address)
                                        <li>{{ $company->address }}</li>
                                    @endif
                                    @if ($company->website)
                                        <li>{{ __('Website') }}: {{ rtrim($company->website, '/') }}</li>
                                    @endif
                                    @if ($company->phone)
                                        <li>{{ __('Phone') }}: {{ $company->phone }}</li>
                                    @endif
                                </ul>
                            </div>
                        @endif
                    </div>
                    @if($companyJobs->isNotEmpty())
                        <div class="sidebar-border">
                            <h6 class="f-18">{{ __('Similar jobs') }}</h6>
                            <div class="sidebar-list-job">
                                <ul>
                                    @foreach ($companyJobs as $companyJob)
                                        <li>
                                            <div class="card-list-4 wow animate__ animate__fadeIn hover-up animated" style="visibility: visible; animation-name: fadeIn;">
                                                <div class="image">
                                                    <a href="{{ $companyJob->company->url }}">
                                                        <img src="{{ $companyJob->company->logo_thumb }}" width="50" alt="{{ $companyJob->company->name }}">
                                                    </a>
                                                </div>
                                                <div class="info-text">
                                                    <h5 class="font-md font-bold color-brand-1">
                                                        <a href="{{ $companyJob->url }}">{{ $companyJob->name }}</a>
                                                    </h5>
                                                    <div class="d-flex align-items-center gap-3 font-xs color-text-mutted mt-0">
                                                        <span class="fi-icon"><i class="fi-rr-briefcase"></i>
                                                            @if($job->jobTypes->isNotEmpty())
                                                                @foreach($job->jobTypes as $jobType)
                                                                    {{ $jobType->name }}
                                                                    @if (!$loop->last)
                                                                        ,
                                                                    @endif
                                                                @endforeach
                                                            @endif
                                                        </span>
                                                        <span class="fi-icon"><i class="fi-rr-clock"></i>
                                                            <time datetime="{{ $companyJob->created_at->format('Y/m/d') }}">
                                                                {{ $companyJob->created_at->translatedFormat('M d, Y') }}
                                                            </time>
                                                        </span>
                                                    </div>
                                                    <div class="mt-6">
                                                        <div class="similar-jobs-content">
                                                            <div class="d-flex align-items-center job-information">
                                                                <div class="job-location">
                                                                    <span class="card-location">{{ $companyJob->location }}</span>
                                                                </div>
                                                                <div class="job-salary">
                                                                    <h6 class="card-price text-nowrap mb-0">
                                                                        {!! Theme::partial('salary', ['job' => $companyJob]) !!}
                                                                    </h6>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    @endif
                </div>
            @endif
        </div>
    </div>

    @if (is_plugin_active('ads'))
        {!! apply_filters('ads_render', null, 'job_after', ['class' => 'my-2 text-center']) !!}
    @endif
</section>
