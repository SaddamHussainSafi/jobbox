@php
    $categoryIds = explode(',', Arr::get($attributes, 'category_ids'));
@endphp

<div class="mb-3">
    <label class="form-label">{{ __('Title') }}</label>
    <input type="text" name="title" value="{{ Arr::get($attributes, 'title') }}" class="form-control" placeholder="{{ __('Title') }}">
</div>

<div class="mb-3">
    <label class="form-label">{{ __('Subtitle') }}</label>
    <input type="text" name="subtitle" value="{{ Arr::get($attributes, 'subtitle') }}" class="form-control" placeholder="{{ __('Subtitle') }}">
</div>

<div class="mb-3">
    <label class="form-label">{{ __('Number of FAQ') }}</label>
    <input type="number" name="number_of_faq" value="{{ Arr::get($attributes, 'number_of_faq') }}" class="form-control" placeholder="{{ __('Number of FAQ') }}">
</div>

<div class="mb-3">
    <label class="form-label">{{ __('Choose categories') }}</label>
    <select class="select-full" name="category_ids" multiple>
        @foreach($categories as $category)
            <option @selected(in_array($category->id, $categoryIds)) value="{{ $category->id }}">{{ $category->name }}</option>
        @endforeach
    </select>
</div>
