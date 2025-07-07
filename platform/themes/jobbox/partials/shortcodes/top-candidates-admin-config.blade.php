@php($random = Str::random(20))

<div class="mb-3">
    <label class="form-label">{{ __('Title') }}</label>
    <input type="text" name="title" value="{{ Arr::get($attributes, 'title') }}" class="form-control" placeholder="{{ __('Title') }}">
</div>

<div class="mb-3">
    <label class="form-label">{{ __('Description') }}</label>
    <input type="text" name="description" value="{{ Arr::get($attributes, 'description') }}" class="form-control" placeholder="{{ __('Description') }}">
</div>

<div class="mb-3">
    <label class="form-label">{{ __('Limit Candidates') }}</label>
    <small>{{ __('Number of featured candidates to show') }}</small>
    <input type="number" name="limit" value="{{ Arr::get($attributes, 'limit', 8) }}" class="form-control" placeholder="{{ __('Number of candidates') }}">
</div>

<div class="mb-3">
    <label class="form-label" for="style_{{ $random }}">{{ __('Style') }}</label>
    {!!
        Form::customSelect('style', [
            'style-3' => __('Style 3'),
        ], Arr::get($attributes, 'style'), ['id' => 'style_' . $random])
    !!}
</div>
