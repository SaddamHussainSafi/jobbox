<div class="mb-3">
    <label class="form-label" for="widget-logo">{{ __('Background Image') }}</label>
    {!! Form::mediaImage('logo', $config['logo']) !!}
    <small>{{ __('If you don\'t set logo image, it will show the site logo') }}</small>
</div>

<div class="mb-3">
    <label class="form-label" for="widget_introduction">{{ __('Introduction') }}</label>
    <textarea name="introduction" id="widget_introduction" rows="3" class="form-control">{{ $config['introduction'] }}</textarea>
</div>

