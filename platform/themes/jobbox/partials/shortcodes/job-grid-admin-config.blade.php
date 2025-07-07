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

<div class="mb-3">
    <label class="form-label">{{ __('Title') }}</label>
    <input type="text" name="title" value="{{ Arr::get($attributes, 'title') }}" class="form-control" placeholder="{{ __('Title') }}">
</div>

<div class="mb-3">
    <label class="form-label">{{ __('High light title text') }}</label>
    <input type="text" name="high_light_title_text" value="{{ Arr::get($attributes, 'high_light_title_text') }}" class="form-control" placeholder="{{ __('Title') }}">
</div>

<div class="mb-3">
    <label class="form-label">{{ __('Subtitle') }}</label>
    <input type="text" name="subtitle" value="{{ Arr::get($attributes, 'subtitle') }}" class="form-control" placeholder="{{ __('Subtitle') }}">
</div>

<div class="mb-3">
    <label class="form-label">{{ __('Description') }}</label>
    <input type="text" name="description" value="{{ Arr::get($attributes, 'description') }}" class="form-control" placeholder="{{ __('Description') }}">
</div>

<div class="mb-3">
    <label class="form-label">{{ __('Image job 1') }}</label>
    {!! Form::mediaImage('image_job_1', Arr::get($attributes, 'image_job_1')) !!}
</div>

<div class="mb-3">
    <label class="form-label">{{ __('Image job 2') }}</label>
    {!! Form::mediaImage('image_job_2', Arr::get($attributes, 'image_job_2')) !!}
</div>

<div class="mb-3">
    <label class="form-label">{{ __('Image') }}</label>
    {!! Form::mediaImage('image', Arr::get($attributes, 'image')) !!}
</div>

<div class="mb-3">
    <label class="form-label">{{ __('Button text') }}</label>
    <input type="text" name="button_text" value="{{ Arr::get($attributes, 'button_text') }}" class="form-control" placeholder="{{ __('Button text') }}">
</div>

<div class="mb-3">
    <label class="form-label">{{ __('Button url') }}</label>
    <input type="url" name="button_url" value="{{ Arr::get($attributes, 'button_url') }}" class="form-control" placeholder="{{ __('https://example.com') }}">
</div>

<div class="mb-3">
    <label class="form-label">{{ __('Link text') }}</label>
    <input type="text" name="link_text" value="{{ Arr::get($attributes, 'link_text') }}" class="form-control" placeholder="{{ __('Link text') }}">
</div>

<div class="mb-3">
    <label class="form-label">{{ __('Link text url') }}</label>
    <input type="url" name="link_text_url" value="{{ Arr::get($attributes, 'link_text_url') }}" class="form-control" placeholder="{{ __('https://example.com') }}">
</div>
