@for($i = 1; $i <= 4 ; $i++)
    <div class="mb-3">
        <label class="form-label">{{ __('Counter title :number', ['number' => $i]) }}</label>
        <input type="text" name="counter_title_{{ $i }}" value="{{ Arr::get($attributes, 'counter_title_' . $i) }}" class="form-control" placeholder="{{ __('Counter title :number', ['number' => $i]) }}">
    </div>

    <div class="mb-3">
        <label class="form-label">{{ __('Description :number', ['number' => $i]) }}</label>
        <input type="text" name="counter_description_{{ $i }}" value="{{ Arr::get($attributes, 'counter_description_' . $i) }}" class="form-control" placeholder="{{ __('Description :number', ['number' => $i]) }}">
    </div>

    <div class="mb-3">
        <label class="form-label">{{ __('Counter number :number', ['number' => $i]) }}</label>
        <input type="text" name="counter_number_{{ $i }}" value="{{ Arr::get($attributes, 'counter_number_' . $i) }}" class="form-control" placeholder="{{ __('Counter number :number', ['number' => $i]) }}">
    </div>
@endfor
