<h2>{{ $tag->name }}</h2>

@foreach ($jobs as $job)
    <p><a href="{{ $job->url }}">{{ $job->name }}</a></p>
@endforeach
