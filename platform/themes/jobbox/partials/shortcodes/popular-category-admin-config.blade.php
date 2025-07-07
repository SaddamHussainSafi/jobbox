@php($random = Str::random(20))

<div class="mb-3">
    <label class="form-label">{{ __('Title') }}</label>
    <input type="text" name="title" value="{{ Arr::get($attributes, 'title') }}" class="form-control" placeholder="{{ __('Title') }}">
</div>

<div class="mb-3">
    <label class="form-label">{{ __('Subtitle') }}</label>
    <input type="text" name="subtitle" value="{{ Arr::get($attributes, 'subtitle') }}" class="form-control" placeholder="{{ __('Subtitle') }}">
</div>

<div class="mb-3">
    <label class="form-label">{{ __('Limit category') }}</label>
    <input type="number" name="limit_category" value="{{ Arr::get($attributes, 'limit_category') }}" class="form-control" placeholder="{{ __('Limit category') }}">
</div>


<div class="mb-3">
    <label class="form-label" for="style_{{ $random }}">{{ __('Style') }}</label>
    {!!
        Form::customSelect('style', [
            'style-1' => __('Style 1'),
            'style-5' => __('Style 5'),
        ], Arr::get($attributes, 'style'), ['id' => 'style_' . $random])
    !!}
</div>

