<div class="mb-3">
    <label class="form-label">{{ __('Title') }}</label>
    <input type="text" name="title" value="{{ Arr::get($attributes, 'title') }}" class="form-control" placeholder="{{ __('Title') }}">
</div>

@for($i = 1; $i <= 10; $i++)
    <div style="border: 1px solid #cac6c6; padding: 15px 10px; margin-bottom: 20px;">
        <div class="mb-3">
            <label class="form-label">{{ __('Company Name :i', ['i' => $i]) }}</label>
            <input type="text" name="name_{{ $i }}" value="{{ Arr::get($attributes, 'name_' . $i) }}" class="form-control" placeholder="{{ __('Company Name :i', ['i' => $i]) }}">
        </div>
        <div class="mb-3">
            <label class="form-label">{{ __('Company URL :i', ['i' => $i]) }}</label>
            <input type="text" name="url_{{ $i }}" value="{{ Arr::get($attributes, 'url_' . $i) }}" class="form-control" placeholder="{{ __('Company URL :i', ['i' => $i]) }}">
        </div>
        <div class="mb-3">
            <label class="form-label">{{ __('Company Image :i', ['i' => $i]) }}</label>
            {!! Form::mediaImage('image_' . $i, Arr::get($attributes, 'image_' . $i)) !!}
        </div>
    </div>
@endfor
