@for($i = 1; $i <= 5; $i++)
    <div class="mb-3">
        <label class="form-label">{{ __('Image :i', ['i' => $i]) }}</label>
        {!! Form::mediaImage('image_' . $i, Arr::get($attributes, 'image_' . $i)) !!}
    </div>
@endfor



