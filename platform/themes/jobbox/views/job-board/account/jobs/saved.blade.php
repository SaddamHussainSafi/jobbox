<!-- START BOOKMARKS -->
@php
    Theme::set('pageTitle', __('Saved Jobs'));
    $defaultCompanyLogo = theme_option('default_company_logo', true);
@endphp

{!! Theme::partial('breadcrumbs') !!}
<section class="section mt-50">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <div>
                    <h6 class="fs-16 mb-0">{{ SeoHelper::getTitle() }}</h6>
                </div>
            </div><!--end col-->
            <div class="col-lg-6">
                <form action="{{ URL::current() }}" method="GET">
                    <div class="candidate-list-widgets">
                        <div class="row">
                            <div class="col-lg-5">
                                <div class="selection-widget mb-3 select-style mt-3 mt-lg-0">
                                    <select class="form-control select-active" data-trigger name="order_by" id="choices-single-filter-order_by" aria-label="Default select example">
                                        <option value="">{{ __('Default') }}</option>
                                        <option value="newest">{{ __('Newest') }}</option>
                                        <option value="oldest">{{ __('Oldest') }}</option>
                                        <option value="random">{{ __('Random') }}</option>
                                    </select>
                                </div>
                            </div><!--end col-->
                            <div class="col-lg-5">
                                <div class="selection-widget mb-3 select-style mt-3 mt-lg-0">
                                    <select class="form-control select-active" data-trigger name="category" id="choices-candidate-page" aria-label="Default select example">
                                        <option value="">{{ __('All') }}</option>
                                        @foreach (app(Botble\JobBoard\Repositories\Interfaces\CategoryInterface::class)->getCategories() as $category)
                                            <option value="{{ $category->id }}" @if (request()->input('category') == $category->id) selected @endif>{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div><!--end col-->
                            <div class="col-lg-2">
                                <button class="btn btn-primary w-100 btn-search-filter">
                                    <span class="fi-rr-search"></span>
                                </button>
                            </div>
                        </div><!--end row-->
                    </div><!--end candidate-list-widgets-->
                </form>

            </div><!--end col-->
        </div><!--end row-->
        <div class="row">
            <div class="col-lg-12">
                @forelse ($jobs as $job)
                    <div class="job-box card mt-4">
                        <div class="card-body p-4">
                            <div class="row">
                                    <div class="col-lg-1">
                                        @php
                                            $companyLogo = $job->company->logo;
                                        @endphp

                                        @if (!$job->hide_company && ($companyLogo || $defaultCompanyLogo))
                                            <a href="{{ $job->company->url }}">
                                                {{ RvMedia::image($companyLogo ?: $defaultCompanyLogo, $job->company->name, attributes: ['class' => 'img-fluid rounded-3']) }}
                                            </a>
                                        @elseif (theme_option('logo'))
                                            {{ Theme::getLogoImage(['class' => 'img-fluid rounded-3'], 'logo') }}
                                        @endif
                                    </div><!--end col-->
                                <div class="col-lg-9">
                                    <div class="mt-3 mt-lg-0">
                                        <h5 class="fs-17 mb-1">
                                            <a href="{{ $job->url }}" class="text-dark">{{ $job->name }}</a>
                                            <small class="text-muted fw-normal">({{ $job->jobExperience->name }})</small>
                                        </h5>
                                        <ul class="list-inline mb-0">
                                            @if (!$job->hide_company)
                                                <li class="list-inline-item">
                                                    <p class="text-muted fs-14 mb-0">{{ $job->company->name }}</p>
                                                </li>
                                            @endif
                                            <li class="list-inline-item">
                                                <p class="text-muted fs-14 mb-0">
                                                    <i class="mdi mdi-map-marker"></i>
                                                    <span>{{ $job->full_address }}</span>
                                                </p>
                                            </li>
                                            <li class="list-inline-item">
                                                <p class="text-muted fs-14 mb-0">
                                                    <i class="uil uil-wallet"></i>
                                                    <span>{{ $job->salary_text }}</span>
                                                </p>
                                            </li>
                                        </ul>
                                        @if ($job->jobTypes->count())
                                            <div class="mt-2">
                                                @foreach($job->jobTypes as $jobType)
                                                    <span class="badge bg-soft-danger mt-1">{{ $jobType->name }}@if (!$loop->last), @endif</span>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                </div><!--end col-->
                                <div class="col-lg-2 align-self-center">
                                    <ul class="list-inline mt-3 mb-0">
                                        <li class="list-inline-item" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('View Detail') }}">
                                            <a href="{{ $job->url }}" class="avatar-sm bg-soft-success d-inline-block text-center rounded-circle fs-18">
                                                <i class="fi-rr-eye"></i>
                                            </a>
                                        </li>
                                        <li class="list-inline-item" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('Delete') }}">
                                            <a href="#" onclick="event.preventDefault(); document.getElementById('bookmark-form-{{ $job->id }}').submit();"
                                                data-bs-toggle="modal" data-bs-target="#deleteModal"  class="avatar-sm bg-soft-danger d-inline-block text-center rounded-circle fs-18">
                                                <i class="fi-rr-trash"></i>
                                            </a>
                                            <form id="bookmark-form-{{ $job->id }}" action="{{ route('public.account.jobs.saved.action') }}" method="POST" style="display: none;">
                                                @csrf
                                                <input type="hidden" name="job_id" value="{{ $job->id }}">
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            </div><!--end row-->
                        </div>
                    </div><!--end job-box-->

                @empty
                    <div class="alert alert-warning my-2">
                        {{ __('No job found') }}
                    </div>
                @endforelse
            </div><!--end col-->
        </div><!--end row-->

        <div class="row">
            <div class="col-lg-12 mt-4 pt-2">
                {!! $jobs->withQueryString()->links(Theme::getThemeNamespace('partials.pagination')) !!}
            </div><!--end col-->
        </div><!--end row-->
    </div><!--end container-->
</section>
<!-- START BOOKMARKS -->
