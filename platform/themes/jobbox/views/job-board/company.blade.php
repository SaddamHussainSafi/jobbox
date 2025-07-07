@php
    Theme::asset()->usePath()->add('leaflet-css', 'plugins/leaflet/leaflet.css');
    Theme::asset()->container('footer')->usePath()->add('leaflet-js', 'plugins/leaflet/leaflet.js');
    Theme::asset()->container('footer')->usePath()->add('markercluster-src-js', 'plugins/leaflet/leaflet.markercluster-src.js');
    Theme::asset()->container('footer')->usePath()->add('slider-js', 'js/company-detail.js');
    Theme::asset()->usePath()->add('css-bar-rating', 'plugins/jquery-bar-rating/themes/css-stars.css');
    Theme::asset()->container('footer')->usePath()->add('jquery-bar-rating-js', 'plugins/jquery-bar-rating/jquery.barrating.min.js');
    Theme::set('pageTitle', $company->name);

    $coverImage = '';

    if ($company->cover_image_url) {
        $coverImage = $company->cover_image_url;
    }
@endphp

<section class="section-box-2 company-detail">
    @if (is_plugin_active('ads'))
        {!! apply_filters('ads_render', null, 'company_before', ['class' => 'my-2 text-center']) !!}
    @endif

    <div class="container">
        <div class="banner-hero banner-image-single">
            <div class="wrap-cover-image">
                @if ($coverImage)
                    <img src="{{ $coverImage }}" alt="{{ $company->name }}">
                @elseif(theme_option('default_company_cover_image'))
                    <img src="{{ RvMedia::getImageUrl(theme_option('default_company_cover_image')) }}" alt="{{ $company->name }}">
                @else
                    <img src="{{ Theme::asset()->url('imgs/backgrounds/cover-image-default.png') }}" alt="{{ $company->name }}">
                @endif
            </div>
        </div>
        <div class="box-company-profile">
            <div class="image-company">
                <img src="{{ $company->logo_thumb }}" class="img-fluid" alt="{{ $company->name }}">
            </div>
            <div class="row mt-30">
                <div class="col-lg-8 col-md-12">
                    <h5 class="f-18">
                        {{ $company->name }}
                        <span class="card-location font-regular ml-20">{{ implode(', ', array_filter([$company->state_name, $company->country_name])) }}</span>
                    </h5>
                    <p class="mt-5 font-md color-text-paragraph-2 mb-15">{{ $company->description }}</p>
                </div>
                <div class="col-lg-4 col-md-12 text-lg-end">
                @if ($company->phone && (! JobBoardHelper::isCompanyInformationHiddenForGuests() || auth('account')->check()))
                    <a class="btn btn-call-icon btn-apply btn-apply-big" href="tel:{{ $company->phone }}">
                        {{ __('Contact Us') }}
                    </a>
                @endif
                </div>
            </div>
        </div>
        <div class="border-bottom pt-10 pb-10"></div>
    </div>
</section>
<section class="section-box mt-50 company-detail-job-list">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 col-md-12 col-sm-12 col-12">
                <div class="content-single">
                    <div class="tab-content">
                        <div class="tab-pane fade show active" id="tab-about" role="tabpanel" aria-labelledby="tab-about">
                            <h4>{{ __('Welcome to :company_name', ['company_name' => $company->name]) }}</h4>
                            <div class="ck-content">
                                {!! BaseHelper::clean($company->content) !!}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="box-related-job content-page box-list-jobs">
                    @include(Theme::getThemeNamespace('views.job-board.partials.company-job-items'), ['jobs' => $jobs])
                </div>

                @if(JobBoardHelper::isEnabledReview())
                    <div class="mt-4 pt-3 position-relative review-listing" @style(['display: none' => $company->reviews_count < 1])>
                        <h6 class="fs-17 fw-semibold mb-3">{{ __(":company's Reviews", ['company' => $company->name]) }}</h6>
                        <div class="spinner-overflow"></div>
                        <div class="half-circle-spinner" style="display: none;position: absolute;top: 70%;left: 50%;">
                            <div class="circle circle-1"></div>
                            <div class="circle circle-2"></div>
                        </div>
                        <div class="review-list">
                            @include(Theme::getThemeNamespace('views.job-board.partials.review-load'), ['reviews' => $company->reviews])
                        </div>
                    </div>

                    @include(Theme::getThemeNamespace('views.job-board.partials.review-form'), [
                        'reviewable' => $company,
                        'canReview' => $canReview,
                    ])
                @endif
            </div>
            <div class="col-lg-4 col-md-12 col-sm-12 col-12 pl-40 pl-lg-15 mt-lg-30">
                <div class="sidebar-border">
                    <div class="sidebar-heading">
                        <div class="avatar-sidebar">
                            <div class="sidebar-info pl-0">
                                <span class="sidebar-company mb-2">{{ $company->name }}</span>
                                @if(JobBoardHelper::isEnabledReview())
                                    {!! Theme::partial('rating-star', ['star' => round($company->reviews_avg_star)]) !!}
                                    <span class="font-xs color-text-mutted ml-10">
                                        <span>(</span>
                                        <span>{{ $company->reviews_count }}</span>
                                        <span>)</span>
                                    </span>
                                @endif
                                <span class="card-location">{{ $company->location }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="sidebar-list-job">
                        @if($company->latitude && $company->longitude)
                            <div class="box-map job-board-street-map-container">
                                <div class="job-board-street-map"
                                     data-popup-id="#street-map-popup-template"
                                     data-center="{{ json_encode([$company->latitude, $company->longitude]) }}"
                                     data-map-icon="{{ $company->name }}"
                                     style="height: 150px"
                                ></div>
                            </div>
                            <div class="d-none" id="street-map-popup-template">
                                <div>
                                    <table width="100%">
                                        <tr>
                                            <td width="40" class="image-company-sidebar">
                                                <div>
                                                    <img src="{{ $company->logo_thumb }}" width="40" alt="{{ $company->name }}">
                                                </div>
                                            </td>
                                            <td>
                                                <div class="infomarker">
                                                    <h5>
                                                        <a href="{{ $company->url }}" target="_blank">{{ $company->name }}</a>
                                                    </h5>
                                                    <div class="text-info">
                                                        <i class="mdi mdi-account"></i>
                                                        <span>{{ __(':number Employees', ['number' => $company->number_of_employees])}}</span>
                                                    </div>
                                                    @if ($company->full_address)
                                                        <div class="text-muted">
                                                            <i class="uil uil-map"></i>
                                                            <span>{{ $company->full_address }}</span>
                                                        </div>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        @endif
                    </div>
                    <div class="sidebar-list-job">
                        <ul>
                            <li>
                                <div class="sidebar-icon-item">
                                    <i class="fi-rr-clock"></i>
                                </div>
                                <div class="sidebar-text-info">
                                    <span class="text-description">{{ __('Year founded') }}</span>
                                    <strong class="small-heading">{{ $company->year_founded }}</strong>
                                </div>
                            </li>
                            @if($company->website)
                                <li>
                                    <div class="sidebar-icon-item"><i class="fi-rr-globe"></i></div>
                                    <div class="sidebar-text-info">
                                        <span class="text-description">{{ __('Website') }}
                                        </span>
                                        <a href="{{ $company->website }}">
                                            <strong class="small-heading">{{ rtrim($company->website, '/') }}</strong>
                                        </a>
                                    </div>
                                </li>
                            @endif
                            @if(count($jobs) > 0)
                                <li>
                                    <div class="sidebar-icon-item">
                                        <i class="fi-rr-time-fast"></i>
                                    </div>
                                    <div class="sidebar-text-info">
                                        <span class="text-description">{{ __('Last Jobs Posted') }}</span>
                                        <strong class="small-heading">{{ $jobs->first()->created_at->diffForHumans() }}</strong>
                                    </div>
                                </li>
                            @endif
                        </ul>
                    </div>
                    <div class="sidebar-list-job">
                        @if(! JobBoardHelper::isCompanyInformationHiddenForGuests() || auth('account')->check())
                            <ul class="ul-disc">
                                @if ($uniqueId = $company->unique_id)
                                    <li>{{ __('ID: :id', ['id' => $uniqueId]) }}</li>
                                @endif

                                @if ($company->address)
                                    <li>{{ $company->address }}</li>
                                @endif
                                @if($company->phone)
                                    <li>{{ __('Phone: :phone', ['phone' => $company->phone]) }}</li>
                                @endif
                                @if($company->email)
                                    <li>{{ __('Email: :email', ['email' => $company->email]) }}</li>
                                @endif

                            </ul>
                            <div class="text-center mt-30">
                                @if($company->facebook)
                                    <a href="{{ $company->facebook }}" class="share-facebook social-share-link"></a>
                                @endif
                                @if($company->twitter)
                                    <a href="{{ $company->twitter }}" class="share-twitter social-share-link"></a>
                                @endif
                                @if($company->linkedin)
                                    <a href="{{ $company->linkedin }}" class="share-linkedin social-share-link"></a>
                                @endif
                                @if($company->instagram)
                                    <a href="{{ $company->instagram }}" class="share-instagram social-share-link"></a>
                                @endif
                            </div>

                            <div class="mt-30">
                                @if($company->email)
                                    <a class="btn btn-send-message" href="mailto:{{ $company->email }}">{{ __('Send Message') }}</a>
                                @endif
                            </div>
                        @else
                            <a class="alert-label-for-guest text-muted" href="{{ route('public.account.login') }}">{{ __('Please log in to view company information') }}</a>
                       @endif
                    </div>
                </div>
                @if (is_plugin_active('ads'))
                    {!! apply_filters('ads_render', null, 'company_sidebar_before', ['class' => 'my-2 text-center']) !!}
                @endif

                {!! dynamic_sidebar('company_sidebar') !!}

                @if (is_plugin_active('ads'))
                    {!! apply_filters('ads_render', null, 'company_sidebar_after', ['class' => 'my-2 text-center']) !!}
                @endif
            </div>
        </div>
    </div>

    {!! Form::open(['url' => $company->url, 'method' => 'GET', 'id' => 'job-pagination-form']) !!}
    <input type="hidden" name="page" value="{{ BaseHelper::stringify(request()->query('page')) ?: 1 }}">
    {!! Form::close() !!}

    @if (is_plugin_active('ads'))
        {!! apply_filters('ads_render', null, 'company_after', ['class' => 'my-2 text-center']) !!}
    @endif
</section>
