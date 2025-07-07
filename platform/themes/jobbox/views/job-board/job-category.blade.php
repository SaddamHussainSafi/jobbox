@php
    Theme::asset()->container('footer')->usePath()->add('no-ui-slider', 'js/noUISlider.js');

    if (theme_option('show_map_on_jobs_page', 'yes') === 'yes') {
        Theme::asset()->usePath()->add('leaflet-css', 'plugins/leaflet/leaflet.css');
        Theme::asset()->container('footer')->usePath()->add('leaflet-js', 'plugins/leaflet/leaflet.js');
        Theme::asset()->container('footer')->usePath()->add('leaflet-markercluster-js', 'plugins/leaflet/leaflet.markercluster-src.js');
    }

    Theme::set('pageTitle', $category->name);
    Theme::set('pageDescription', $category->description);
@endphp

{!! Theme::partial('breadcrumbs') !!}
<section class="section-box mt-30">
    <div class="container">
        <div class="row flex-row-reverse row-filter justify-content-center">
            <div class="col-lg-9 col-md-12 col-sm-12 row col-12 float-right jobs-listing">
                @include(Theme::getThemeNamespace('views.job-board.partials.job-items'), ['jobs' => $jobs])
            </div>

            @include(Theme::getThemeNamespace('views.job-board.partials.filters'), [
                 'jobCategories' => $jobCategories,
                 'jobCount' => $jobs->count() ?? 0,
                 'maxSalaryRange' => theme_option('job_board_max_salary_filter', 100000),
                 'jobTypes' => $jobTypes,
                 'jobExperiences' => $jobExperiences,
                 'jobSkills' => $jobSkills,
            ])
        </div>
    </div>
</section>
@if(theme_option('show_map_on_jobs_page', 'yes') === 'yes')
    <script id="traffic-popup-map-template" type="text/x-jquery-tmpl">
        @include(Theme::getThemeNamespace('views.job-board.partials.map'))
    </script>
@endif

