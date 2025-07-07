<h1>{{ SeoHelper::getTitle() }}</h1>

@forelse ($jobs as $job)
    <p>{{ $job->name }}</p>
@empty
    <p>{{ __('No applied jobs found.') }}</p>
@endforelse

{!! $jobs->withQueryString()->links() !!}
