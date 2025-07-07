@php
    Theme::asset()->container('footer')->usePath()->add('candidates-filter', 'js/candidates-filter.js');
@endphp

<div class="container candidates-list">
    @if (is_plugin_active('ads'))
        {!! apply_filters('ads_render', null, 'candidate_list_before', ['class' => 'my-2 text-center']) !!}
    @endif

    <section class="section-box-2">
        <div class="container">
            <div class="banner-hero banner-company">
                <div class="block-banner text-center">
                    <h3 class="wow animate__animated animate__fadeInUp">{!! BaseHelper::clean($shortcode->title) !!}</h3>
                    <div class="font-sm color-text-paragraph-2 mt-10 wow animate__animated animate__fadeInUp" data-wow-delay=".1s">{!! BaseHelper::clean($shortcode->description) !!}</div>
                    <div class="box-list-character">
                        <ul>
                            @foreach(range('a', 'z') as $char)
                                <li>
                                    <a href="javascript:void(0)" class="keyword @if(request()->query('keyword') == $char) active @endif" data-keyword="{{ $char }}">{{ $char }}</a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <form action="{{ route('public.ajax.candidates') }}" class="candidate-filter-form">
        <input type="hidden" name="keyword" value="{{ BaseHelper::stringify(request()->query('keyword')) }}">
        <input type="hidden" name="per_page" value="{{ BaseHelper::stringify(request()->query('per_page', 12)) }}">
        <input type="hidden" name="sort_by" value="{{ BaseHelper::stringify(request()->query('sort_by', 'newest')) }}">
        <input type="hidden" name="page" value="{{ BaseHelper::stringify(request()->query('page', 1)) }}">
    </form>
    <section class="mt-30">
        <div class="container position-relative">
            <div class="content-page">
                {!! Theme::partial('loading') !!}
                <div class="box-filters-job">
                    <div class="row">
                        <div class="col-xl-6 col-lg-5">
                            <span class="text-small text-showing">
                                {{ __('Showing :from-:to of :total candidate(s)', [
                                    'from' => $candidates->firstItem() ?: 0,
                                    'to' => $candidates->lastItem() ?: 0,
                                    'total' => $candidates->total(),
                                ]) }}
                            </span>
                        </div>
                        <div class="col-xl-6 col-lg-7 text-lg-end mt-sm-15">
                            <div class="display-flex2">
                                <div class="box-border mr-10">
                                    <span class="text-sort_by">{{ __('Show') }}:</span>
                                    <div class="dropdown dropdown-sort">
                                        <button class="btn dropdown-toggle" id="dropdownSort" type="button" data-bs-toggle="dropdown" aria-expanded="false" data-bs-display="static">
                                            <span>{{ $candidates->perPage() }}</span>
                                            <i class="fi-rr-angle-small-down"></i>
                                        </button>
                                        <ul class="dropdown-menu js-dropdown-clickable dropdown-menu-light" aria-labelledby="dropdownSort">
                                            @foreach(JobBoardHelper::getPerPageParams() as $perPage)
                                                <li>
                                                    <a class="dropdown-item per-page" data-per-page="{{ $perPage }}">{{ $perPage }}</a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                                <div class="box-border">
                                    @include(Theme::getThemeNamespace('views.job-board.partials.sort-by-dropdown'))
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row candidate-list">
                    @include(Theme::getThemeNamespace('views.job-board.partials.candidate-list'))
                </div>
            </div>
        </div>
    </section>

    @if (is_plugin_active('ads'))
        {!! apply_filters('ads_render', null, 'candidate_list_after', ['class' => 'my-2 text-center']) !!}
    @endif
</div>
