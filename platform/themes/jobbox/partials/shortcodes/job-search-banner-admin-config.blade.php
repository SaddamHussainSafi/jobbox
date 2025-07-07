<div class="mb-3">
    <label class="form-label">{{ __('Title') }}</label>
    <input type="text" name="title" value="{{ Arr::get($attributes, 'title') }}" class="form-control" placeholder="{{ __('Title') }}">
</div>

<div class="mb-3">
    <label class="form-label">{{ __('Background Image') }}</label>
    {!! Form::mediaImage('background_image', Arr::get($attributes, 'background_image')) !!}
</div>

@for($i = 1; $i <= 3; $i++)

    <div class="mb-3">
        <label class="form-label">{{ __('Checkbox title :i', ['i' => $i]) }}</label>
        <input type="text" name="checkbox_title_{{ $i }}" value="{{ Arr::get($attributes, 'checkbox_title_' . $i) }}" class="form-control" placeholder="{{ __('Checkbox title :i', ['i' => $i]) }}">
    </div>

    <div class="mb-3">
        <label class="form-label">{{ __('Checkbox description :i', ['i' => $i]) }}</label>
        <input type="text" name="checkbox_description_{{$i}}" value="{{ Arr::get($attributes, 'checkbox_description_' . $i) }}" class="form-control" placeholder="{{ __('Description :i', ['i' => $i]) }}">
    </div>

@endfor
