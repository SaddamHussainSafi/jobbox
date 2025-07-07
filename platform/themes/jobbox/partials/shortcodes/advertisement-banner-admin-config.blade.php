<div class="mb-3">
    <label class="form-label">{{ __('First title') }}</label>
    <input type="text" name="first_title" value="{{ Arr::get($attributes, 'first_title') }}" class="form-control" placeholder="{{ __('First title') }}">
</div>

<div class="mb-3">
    <label class="form-label">{{ __('First Description') }}</label>
    <input type="text" name="first_description" value="{{ Arr::get($attributes, 'first_description') }}" class="form-control" placeholder="{{ __('First description') }}">
</div>

<div class="mb-3">
    <label class="form-label">{{ __('Load more text for first content') }}</label>
    <input type="text" name="load_more_first_content_text" value="{{ Arr::get($attributes, 'load_more_first_content_text') }}" class="form-control" placeholder="{{ __('Load more first content text') }}">
</div>

<div class="mb-3">
    <label class="form-label">{{ __('Load more link for first content') }}</label>
    <input type="url" name="load_more_link_first_content" value="{{ Arr::get($attributes, 'load_more_link_first_content') }}" class="form-control" placeholder="{{ __('https://example.com') }}">
</div>

<div class="mb-3">
    <label class="form-label">{{ __('Image for first content') }}</label>
    {!! Form::mediaImage('image_of_first_content', Arr::get($attributes, 'image_of_first_content')) !!}
</div>

<div class="mb-3">
    <label class="form-label">{{ __('Image alt text of first content') }}</label>
    <input type="text" name="first_image_alt_text" value="{{ Arr::get($attributes, 'first_image_alt_text') }}" class="form-control" placeholder="{{ __('Image alt text of first content') }}">
</div>

<div class="mb-3">
    <label class="form-label">{{ __('Second title') }}</label>
    <input type="text" name="second_title" value="{{ Arr::get($attributes, 'second_title') }}" class="form-control" placeholder="{{ __('Second title') }}">
</div>

<div class="mb-3">
    <label class="form-label">{{ __('Second description') }}</label>
    <input type="text" name="second_description" value="{{ Arr::get($attributes, 'second_description') }}" class="form-control" placeholder="{{ __('Second description') }}">
</div>

<div class="mb-3">
    <label class="form-label">{{ __('Load more text for second content') }}</label>
    <input type="text" name="load_more_second_content_text" value="{{ Arr::get($attributes, 'load_more_second_content_text') }}" class="form-control" placeholder="{{ __('Load more text for second content') }}">
</div>

<div class="mb-3">
    <label class="form-label">{{ __('Load more link of second content') }}</label>
    <input type="url" name="load_more_link_second_content" value="{{ Arr::get($attributes, 'load_more_link_second_content') }}" class="form-control" placeholder="{{ __('https://example.com') }}">
</div>

<div class="mb-3">
    <label class="form-label">{{ __('Image of second content') }}</label>
    {!! Form::mediaImage('image_of_second_content', Arr::get($attributes, 'image_of_second_content')) !!}
</div>

<div class="mb-3">
    <label class="form-label">{{ __('Image alt text of second content') }}</label>
    <input type="text" name="second_image_alt_text" value="{{ Arr::get($attributes, 'second_image_alt_text') }}" class="form-control" placeholder="{{ __('Image alt text of second content') }}">
</div>
