<div class="mb-3">
    <label class="form-label">{{ __('Title') }}</label>
    <input type="text" name="title" value="{{ Arr::get($attributes, 'title') }}" class="form-control" placeholder="{{ __('Title') }}">
</div>

<div class="mb-3">
    <label class="form-label">{{ __('Description') }}</label>
    <textarea name="description" class="form-control" rows="3" placeholder="{{ __('Description') }}">{{ Arr::get($attributes, 'description') }}</textarea>
</div>

@for($i = 1; $i <= 3; $i++)
    <div style="border: 1px solid #cac6c6; padding: 15px 10px; margin-bottom: 20px;">
        <div class="mb-3">
            <label class="form-label">{{ __('Step Label :i', ['i' => $i]) }}</label>
            <input type="text" name="step_label_{{ $i }}" value="{{ Arr::get($attributes, 'step_label_' . $i) }}" class="form-control" placeholder="{{ __('Step Label :i', ['i' => $i]) }}">
        </div>

        <div class="mb-3">
            <label class="form-label">{{ __('Step Help :i', ['i' => $i]) }}</label>
            <textarea name="step_help_{{ $i }}" class="form-control" rows="2" placeholder="{{ __('Step Help :i', ['i' => $i]) }}">{{ Arr::get($attributes, 'step_help_' . $i) }}</textarea>
        </div>
    </div>
@endfor
<div style="border: 1px solid #cac6c6; padding: 15px 10px; margin-bottom: 20px;">
    <div class="mb-3">
        <label class="form-label">{{ __('Button Label') }}</label>
        <input type="text" name="button_label" value="{{ Arr::get($attributes, 'button_label') }}" class="form-control" placeholder="{{ __('Button Label') }}">
    </div>

    <div class="mb-3">
        <label class="form-label">{{ __('Button URL') }}</label>
        <input type="text" name="button_url" value="{{ Arr::get($attributes, 'button_url') }}" class="form-control" placeholder="{{ __('Button URL') }}">
    </div>
</div>
