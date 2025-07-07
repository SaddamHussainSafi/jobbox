<div class="mb-3">
    <label class="form-label">{{ __('Title') }}</label>
    <input type="text" name="title" value="{{ Arr::get($attributes, 'title') }}" class="form-control" placeholder="{{ __('Title') }}">
</div>

<div class="mb-3">
    <label class="form-label">{{ __('Subtitle') }}</label>
    <input type="text" name="subtitle" value="{{ Arr::get($attributes, 'subtitle') }}" class="form-control" placeholder="{{ __('Subtitle') }}">
</div>

<div class="mb-3">
    <label class="form-label">{{ __('Description') }}</label>
    <textarea name="description" class="form-control" placeholder="{{ __('Description') }}">{{ Arr::get($attributes, 'description') }}</textarea>
</div>

<div class="mb-3">
    <label class="form-label">{{ __('Title Box') }}</label>
    <input type="text" name="title_box" value="{{ Arr::get($attributes, 'title_box') }}" class="form-control" placeholder="{{ __('Title box') }}">
</div>

<div class="mb-3">
    <label class="form-label">{{ __('Image Box') }}</label>
    {!! Form::mediaImage('image', Arr::get($attributes, 'image')) !!}
</div>

<div class="mb-3">
    <textarea name="description_box" rows="4" class="form-control" placeholder="{{ __('Description Box') }}">{{ Arr::get($attributes, 'description_box') }}</textarea>
</div>

<div class="mb-3">
    <label class="form-label">{{ __('URL') }}</label>
    <input type="text" name="url" value="{{ Arr::get($attributes, 'url') }}" class="form-control" placeholder="{{ __('URL') }}">
</div>

<div class="mb-3">
    <label class="form-label">{{ __('Text Button Box') }}</label>
    <input type="text" name="text_button_box" value="{{ Arr::get($attributes, 'text_button_box') }}" class="form-control" placeholder="{{ __('Text button box') }}">
</div>
