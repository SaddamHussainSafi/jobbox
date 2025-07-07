<div class="mb-3">
    <label class="form-label">{{ __('Title') }}</label>
    <input type="text" name="title" value="{{ Arr::get($attributes, 'title') }}" class="form-control" placeholder="{{ __('Title') }}">
</div>

<div class="mb-3">
    <label class="form-label">{{ __('Description') }}</label>
    <textarea name="description" class="form-control" rows="2" placeholder="{{ __('Description') }}">{{ Arr::get($attributes, 'description') }}</textarea>
</div>

<div class="mb-3">
    <label class="form-label">{{ __('Number per page') }}</label>
    <input type="text" name="number_per_page" value="{{ Arr::get($attributes, 'number_per_page') }}" class="form-control" placeholder="{{ __('Number per page') }}">
</div>

<div class="mb-3">
    <label class="form-label">{{ __('style') }}</label>
    {!! Form::customSelect('style', [
            'list' => __('List'),
            'grid' => __('Grid'),
    ], Arr::get($attributes, 'style')) !!}
</div>
