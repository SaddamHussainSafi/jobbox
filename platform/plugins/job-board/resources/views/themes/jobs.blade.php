<h2>{{ __('Jobs') }}</h2>

<ul>
    @foreach ($jobs as $job)
        <li><a href="{{ $job->url }}">{{ $job->name }}</a></li>
    @endforeach
</ul>
