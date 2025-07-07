<div class="mb-3">
    <label class="form-label">{{ __('Title') }}</label>
    <input type="text" name="title" value="{{ Arr::get($attributes, 'title') }}" class="form-control" placeholder="{{ __('Title') }}">
</div>

<div class="mb-3">
    <label class="form-label">{{ __('Description') }}</label>
    <input type="text" name="description" value="{{ Arr::get($attributes, 'description') }}" class="form-control" placeholder="{{ __('Description') }}">
</div>

@php($random = Str::random(20))

<div class="mb-3">
    <label class="form-label" for="style_{{ $random }}">{{ __('Style') }}</label>
    {!! Form::customSelect('style', [
            'style-1' => __('Style 1'),
            'style-2' => __('Style 2'),
            'style-3' => __('Style 3'),
        ], Arr::get($attributes, 'style'), ['id' => 'style_' . $random]) !!}
</div>
