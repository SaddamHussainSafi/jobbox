<div class="content-page ">
    <div class="box-filters-job">
        <div class="row ">
            <div class="col-xl-6 col-lg-5">
                <span class="text-small text-showing font-weight-bold">
                    {{
                        __('Showing :from – :to of :total job(s)', [
                            'from' => $companies->firstItem(),
                            'to' => $companies->lastItem(),
                            'total' => $companies->total(),
                        ])
                    }}
                </span>
            </div>
            <div class="col-xl-6 col-lg-7 text-lg-end mt-sm-15">
                <div class="display-flex2">
                    <div class="box-border mr-10">
                        <span class="text-sort_by">{{ __('Show') }}:</span>
                        <div class="dropdown dropdown-sort">
                            <button class="btn dropdown-toggle" id="dropdownSort" type="button" data-bs-toggle="dropdown" aria-expanded="false" data-bs-display="static">
                                <span>{{ $companies->perPage() }}</span>
                                <i class="fi-rr-angle-small-down"></i>
                            </button>
                            <ul class="dropdown-menu js-dropdown-clickable dropdown-menu-light" aria-labelledby="dropdownSort">
                                @foreach(JobBoardHelper::getPerPageParams() as $page)
                                    <li>
                                        <a
                                            @class(['dropdown-item per-page-item', 'active' => BaseHelper::stringify(request()->query('per_page')) == $page])
                                            data-per-page="{{ $page }}"
                                            href="#"
                                        >
                                            {{ $page }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    <div class="box-border">
                        @include(Theme::getThemeNamespace('views.job-board.partials.sort-by-dropdown'))
                    </div>
                    <div class="box-view-type">
                        @switch(BaseHelper::stringify(request()->query('layout')))
                            @case('grid')
                                <a class="view-type layout-company" href="#" data-layout="list">
                                    <img src="{{ Theme::asset()->url('imgs/template/icons/icon-list.svg') }}" alt="{{ __('List layout') }}">
                                </a>
                                <a class="view-type layout-company" href="#" data-layout="grid">
                                    <img src="{{ Theme::asset()->url('imgs/template/icons/icon-grid-hover.svg') }}" alt="{{ __('Grid layout') }}">
                                </a>
                                @break
                            @default
                                <a class="view-type layout-company" href="#" data-layout="list">
                                    <img src="{{ Theme::asset()->url('imgs/template/icons/icon-list.svg') }}" alt="{{ __('List layout') }}">
                                </a>
                                <a class="view-type layout-company" href="#" data-layout="grid">
                                    <img src="{{ Theme::asset()->url('imgs/template/icons/icon-grid.svg') }}" alt="{{ __('Grid layout') }}">
                                </a>
                                @break
                        @endswitch
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row display-list">
        @php($template = BaseHelper::stringify(request()->query('layout')) === 'grid' ? 'grid' : 'list')

        @forelse($companies as $company)
            @include(Theme::getThemeNamespace('views.job-board.partials.company-' . $template), ['company' => $company])
        @empty
            <p class="text-center">{{ __('No data available') }}</p>
        @endforelse
    </div>
</div>
{!! $companies->withQueryString()->links(Theme::getThemeNamespace('partials.pagination')) !!}


