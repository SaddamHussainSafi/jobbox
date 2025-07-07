@if (is_plugin_active('job-board'))
    @php
        if ($categoryIds = request()->input('job_categories', [])) {
            $categories = Botble\JobBoard\Models\Category::query()
                ->whereIn('id', (array) $categoryIds)
                ->pluck('name')
                ->all();
        } else {
            $categories = [];
        }
    @endphp
    <div class="form-find position-relative mt-40 wow animate__animated animate__fadeIn" data-wow-delay=".2s">
        {!! Form::open(['url' => JobBoardHelper::getJobsPageURL(), 'method' => 'GET', 'data-quick-search-url' => route('public.ajax.quick-search-jobs')]) !!}
            @if (isset($style))
                <input class="form-input input-keysearch mr-10" name="keyword" type="text" value="{{ BaseHelper::stringify(request()->query('keyword')) }}" placeholder="{{ __('Your keyword...') }}">
            @endif

            @if (is_plugin_active('job-board'))
                <div class="box-industry">
                    <select
                        class="form-input mr-10 select-active input-industry job-category" name="job_categories[]">
                        <option value="">{{ $categories ? implode(', ', $categories) : __('Industry') }}</option>
                    </select>
                </div>
            @endif

            @if (! theme_option('hide_job_location_filter') && is_plugin_active('location'))
                <select class="form-input mr-10 select-location" name="location" data-location-type="{{ theme_option('job_location_filter_by', 'state') }}">
                    <option value="">{{ __('Location') }}</option>
                </select>
            @endif

            @if (!isset($style))
                <input class="form-input input-keysearch mr-10" name="keyword" value="{{ BaseHelper::stringify(request()->query('keyword')) }}" type="text" placeholder="{{ __('Your keyword...') }}">
            @endif

            <div class="search-btn-group">
                <button class="btn btn-default btn-find font-sm">{{ __('Search') }}</button>
                <button type="button" class="btn btn-default font-sm btn-advanced-filter">{{ __('Advanced Filter') }}</button>
            </div>
        {!! Form::close() !!}
    </div>
    @if ($trendingKeywords)
        <div class="list-tags-banner mt-60 wow animate__animated animate__fadeInUp" data-wow-delay=".3s">
            <strong>{{ __('Popular Searches:') }}</strong>
            @if ($keywords = array_map('trim', array_filter(explode(',', $trendingKeywords))))
                @foreach ($keywords as $item)
                    <a href="{{ JobBoardHelper::getJobsPageURL() . '?keyword=' . $item }}">{{ $item }}</a>{{ ! $loop->last ? ',' : '' }}
                @endforeach
            @endif
        </div>
    @endif
@endif
