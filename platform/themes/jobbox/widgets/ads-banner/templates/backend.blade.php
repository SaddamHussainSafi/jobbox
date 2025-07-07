<div class="mb-3">
    <label class="form-label" for="banner_ads">{{ __('Banner ADS') }}</label>
    {!! Form::mediaImage('banner_ads', $config['banner_ads']) !!}
</div>

<div class="mb-3">
    <label class="form-label" for="number_display">{{ __('URL') }}</label>
    <input type="text" class="form-control" name="url" value="{{  $config['url'] }}">
</div>
