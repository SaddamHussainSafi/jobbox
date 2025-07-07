<div class="mb-3">
    <label class="form-label">{{ __('Jobs per page default') }}</label>
    <input type="number" name="jobs_per_page" value="{{ Arr::get($attributes, 'jobs_per_page', Arr::first(\Botble\JobBoard\Facades\JobBoardHelper::getPerPageParams())) }}" class="form-control" placeholder="{{ __('Jobs per page default') }}">
</div>

<div class="mb-3">
    <label class="form-label">{{ __('Jobs per page options') }}</label>
    <input type="text" name="jobs_per_page_options" value="{{ Arr::get($attributes, 'jobs_per_page_options', implode(',', JobBoardHelper::getPerPageParams())) }}" class="form-control" placeholder="{{ __('Jobs per page options') }}">
    <small class="text-muted">{{ __('Enter comma separated values. Example: 10,20,30. Leave empty to use default values.') }}</small>
</div>
