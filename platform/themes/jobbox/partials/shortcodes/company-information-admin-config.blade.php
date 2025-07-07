<div class="mb-3">
    <label class="form-label">{{ __('Company name') }}</label>
    <input type="text" name="company_name" value="{{ Arr::get($attributes, 'company_name') }}" class="form-control" placeholder="{{ __('Company name') }}">
</div>

<div class="mb-3">
    <label class="form-label">{{ __('Company Logo') }}</label>
    {!! Form::mediaImage('logo_company', Arr::get($attributes, 'logo_company')) !!}
</div>

<div class="mb-3">
    <label class="form-label">{{ __('URL') }}</label>
    <input type="text" name="url" value="{{ Arr::get($attributes, 'url') }}" class="form-control" placeholder="{{ __('URL company') }}">
</div>

<div class="mb-3">
    <label class="form-label">{{ __('Company address') }}</label>
    <input type="text" name="company_address" value="{{ Arr::get($attributes, 'company_address') }}" class="form-control" placeholder="{{ __('Company address') }}">
</div>

<div class="mb-3">
    <label class="form-label">{{ __('Company phone') }}</label>
    <input type="number" name="company_phone" value="{{ Arr::get($attributes, 'company_phone') }}" class="form-control" placeholder="{{ __('Company phone') }}">
</div>

<div class="mb-3">
    <label class="form-label">{{ __('Company email') }}</label>
    <input type="email" name="company_email" value="{{ Arr::get($attributes, 'company_email') }}" class="form-control" placeholder="{{ __('Company email') }}">
</div>

@for ($i = 0; $i < 6; $i++)
    <div style="border: 1px solid #cac6c6; padding: 15px 10px; margin-bottom: 20px;">
        <div class="mb-3">
            <label class="form-label">{{ __('Branch Company :number', ['number' => $i + 1]) }}</label>
        </div>
        <div class="mb-3">
            <label class="form-label">{{ __('Branch Company Name') }}</label>
            <input type="text" name="branch_company_name_{{ $i }}" value="{{ Arr::get($attributes, 'branch_company_name_' . $i) }}" class="form-control" placeholder="{{ __('Company name') }}">
        </div>
        <div class="mb-3">
            <label class="form-label">{{ __('Branch Company Address') }}</label>
            <input type="text" name="branch_company_address_{{ $i }}" value="{{ Arr::get($attributes, 'branch_company_address_' . $i) }}" class="form-control" placeholder="{{ __('Company address') }}">
        </div>
    </div>
@endfor

