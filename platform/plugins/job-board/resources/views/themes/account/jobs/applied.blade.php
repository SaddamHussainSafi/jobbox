<h1>{{ SeoHelper::getTitle() }}</h1>

@forelse ($applications as $application)
    <p>{{ $application->job->name }}</p>
@empty
    <p>{{ __('No applied jobs found.') }}</p>
@endforelse

{!! $applications->withQueryString()->links() !!}
