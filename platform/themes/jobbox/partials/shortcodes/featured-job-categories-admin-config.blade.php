<div class="mb-3">
    <label class="form-label">{{ __('Title') }}</label>
    <input type="text" name="title" value="{{ Arr::get($attributes, 'title') }}" class="form-control" placeholder="{{ __('Title') }}">
</div>

<div class="mb-3">
    <label class="form-label">{{ __('Subtitle') }}</label>
    <input type="text" name="subtitle" value="{{ Arr::get($attributes, 'subtitle') }}" class="form-control" placeholder="{{ __('Subtitle') }}">
</div>

<div class="mb-3">
    <label class="form-label">{{ __('Limit feature category') }}</label>
    <input type="number" name="limit_category" value="{{ Arr::get($attributes, 'limit_category') }}" class="form-control" placeholder="{{ __('Limit feature category') }}">
</div>

<div class="mb-3">
    <label class="form-label">{{ __('Background image') }}</label>
    {!! Form::mediaImage('background_image', Arr::get($attributes, 'background_image')) !!}
</div>

@php($random = Str::random(20))

<div class="mb-3">
    <label class="form-label" for="style_{{ $random }}">{{ __('Style') }}</label>
    {!!
        Form::customSelect('style', [
            'style-1' => __('Style 1'),
            'style-2' => __('Style 2'),
        ], Arr::get($attributes, 'style'), ['id' => 'style_' . $random])
    !!}
</div>
