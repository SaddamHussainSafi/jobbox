<div class="mb-3">
    <label class="form-label" for="widget_label">{{ __('Section label') }}</label>
    <input type="text" id="widget_label" class="form-control" name="label" value="{{ $config['label'] }}">
</div>

<div class="mb-3">
    <label class="form-label" for="widget_app_store_url">{{ __('App Store URL') }}</label>
    <input type="text" id="widget_app_store_url" class="form-control" name="app_store_url" value="{{ $config['app_store_url'] }}">
</div>

<div class="mb-3">
    <label class="form-label" for="banner_ads">{{ __('App Store Image') }}</label>
    {!! Form::mediaImage('app_store_image', $config['app_store_image']) !!}
</div>

<div class="mb-3">
    <label class="form-label" for="widget_android_app_url">{{ __('Google Play URL') }}</label>
    <input type="text" id="widget_android_app_url" class="form-control" name="android_app_url" value="{{ $config['android_app_url'] }}">
</div>

<div class="mb-3">
    <label class="form-label" for="banner_ads">{{ __('Google Play Image') }}</label>
    {!! Form::mediaImage('google_play_image', $config['google_play_image']) !!}
</div>
