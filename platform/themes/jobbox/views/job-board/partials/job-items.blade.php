@php
    $layout = BaseHelper::stringify(request()->query('layout'));
    if (! in_array($layout, ['list', 'grid', 'map'])) {
        $layout = 'list';
    }
    $isMapActive = (theme_option('show_map_on_jobs_page', 'yes') === 'yes' && $layout === 'map') && $jobs->isNotEmpty();
    $template = $isMapActive ? 'map' : $layout;
@endphp

<div class="content-page job-content-section">
    <div class="box-filters-job">
        <div class="row">
            <div class="col-xl-6 col-lg-5 jobs-listing-container">
                <span class="text-small text-showing showing-of-results">
                    @if ($jobs->total() > 0)
                        {{ __('Showing :from-:to of :total job(s)', [
                            'from' => $jobs->firstItem(),
                            'to' => $jobs->lastItem(),
                            'total' => $jobs->total(),
                        ]) }}
                    @endif
                </span>
            </div>
            <div class="col-xl-6 col-lg-7 text-lg-end mt-sm-15">
                <div class="display-flex2">
                    <div class="box-border btn-advanced-filter">
                        <svg  xmlns="http://www.w3.org/2000/svg"  width="16"  height="16"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-filter"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 4h16v2.172a2 2 0 0 1 -.586 1.414l-4.414 4.414v7l-6 2v-8.5l-4.48 -4.928a2 2 0 0 1 -.52 -1.345v-2.227z" /></svg>
                    </div>
                    <div class="box-border mr-10">
                        <span class="text-sort_by">{{ __('Show') }}:</span>
                        <div class="dropdown dropdown-sort">
                            <button class="btn dropdown-toggle" id="dropdownSort" type="button" data-bs-toggle="dropdown" aria-expanded="false" data-bs-display="static">
                                <span>{{ $jobs->perPage() }}</span>
                                <i class="fi-rr-angle-small-down"></i>
                            </button>
                            <ul class="dropdown-menu js-dropdown-clickable dropdown-menu-light" aria-labelledby="dropdownSort">
                                <li>
                                    @foreach($perPages ?? JobBoardHelper::getPerPageParams() as $value)
                                        <a class="dropdown-item per-page-item" href="#" data-per-page="{{ $value }}">{{ $value }}</a>
                                    @endforeach
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="box-border">
                        @include(Theme::getThemeNamespace('views.job-board.partials.sort-by-dropdown'))
                    </div>
                    <div class="box-view-type">
                        <a class="view-type layout-job" href="#" data-layout="list">
                            <img src="{{ Theme::asset()->url('imgs/template/icons/icon-list.svg') }}" alt="{{ __('List layout') }}">
                        </a>
                        <a class="view-type layout-job" href="#" data-layout="grid">
                            <img src="{{ Theme::asset()->url('imgs/template/icons/icon-grid.svg') }}" alt="{{ __('Grid layout') }}">
                        </a>
                        @if (theme_option('show_map_on_jobs_page', 'yes') === 'yes' && $jobs->isNotEmpty())
                            <a @class(['view-type layout-job map', 'active' => $layout === 'map']) href="#" data-layout="map">
                                <img src="{{ Theme::asset()->url('imgs/template/map/map' . ($layout === 'map' ? '-active' : null) . '.png') }}" alt="{{ __('Map layout') }}">
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row showing-of-results">
        {!! Theme::partial('loading') !!}

        @forelse($jobs as $job)
            @include(Theme::getThemeNamespace('views.job-board.partials.job-item-' . $template), ['job' => $job])
        @empty
            @include(Theme::getThemeNamespace('views.job-board.partials.job-item-empty'))
        @endforelse

        @if($isMapActive)
            <div class="col-12">
                <div class="col-lg-12 jobs-list-sidebar job-map-section d-lg-block">
                    <div class="right-map h-100">
                        <div class="position-sticky sticky-top">
                            <div class="w-100 bg-light" style="height: 100vh; width:100%">
                                <div class="jobs-list-map h-100" data-center="{{ json_encode(JobBoardHelper::getMapCenterLatLng()) }}"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

@if(! $isMapActive)
    {!! $jobs->withQueryString()->links(Theme::getThemeNamespace('partials.pagination')) !!}
@endif
