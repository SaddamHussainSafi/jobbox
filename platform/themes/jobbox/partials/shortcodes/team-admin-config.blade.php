<div class="mb-3">
    <label class="form-label">{{ __('Title') }}</label>
    <input type="text" name="title" value="{{ Arr::get($attributes, 'title') }}" class="form-control" placeholder="{{ __('Title') }}">
</div>

<div class="mb-3">
    <label class="form-label">{{ __('Subtitle') }}</label>
    <input type="text" name="subtitle" value="{{ Arr::get($attributes, 'subtitle') }}" class="form-control" placeholder="{{ __('SubTitle') }}">
</div>

<div class="mb-3">
    <label class="form-label">{{ __('Description') }}</label>
    <textarea name="description" class="form-control" placeholder="{{ __('Description') }}">{{ Arr::get($attributes, 'description') }}</textarea>
</div>

<div class="mb-3">
    <label class="form-label">{{ __('Number of people') }}</label>
    <input type="number" name="number_of_people" value="{{ Arr::get($attributes, 'number_of_people') }}" class="form-control" placeholder="{{ __('Number of people') }}">
</div>
