<div class="mb-3">
    <label class="form-label">{{ __('Title') }}</label>
    <input type="text" name="title" value="{{ Arr::get($attributes, 'title') }}" class="form-control" placeholder="{{ __('Title') }}">
</div>

<div class="mb-3">
    <label class="form-label">{{ __('Subtitle') }}</label>
    <input type="text" name="subtitle" value="{{ Arr::get($attributes, 'subtitle') }}" class="form-control" placeholder="{{ __('Subtitle') }}">
</div>

<div class="mb-3">
    <label class="form-label">{{ __('Job category') }}</label>
    <input name="job_categories" data-list="{{ json_encode($categories) }}" class="form-control list-tagify" value="{{ Arr::get($attributes, 'job_categories') }}" placeholder="{{ __('Select categories from the list') }}">
</div>

<div class="mb-3">
    <label class="form-label">{{ __('Number of jobs on each category') }}</label>
    <input class="form-control" type="number" name="limit" value="{{ Arr::get($attributes, 'limit', 8) }}" placeholder="{{ __('Default: 8') }}">
</div>

<div class="mb-3">
    <label class="form-label" for="style_{{ $random = Str::random(20) }}">{{ __('Style') }}</label>
    {!! Form::customSelect('style', [
            'style-1' => __('Style 1'),
            'style-2' => __('Style 2'),
            'style-3' => __('Style 3'),
        ], Arr::get($attributes, 'style'), ['id' => 'style_' . $random]) !!}
</div>
