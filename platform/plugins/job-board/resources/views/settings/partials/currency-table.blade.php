<textarea class="d-none" id="currencies" name="currencies">@json($currencies)</textarea>
<textarea class="d-none" id="deleted_currencies" name="deleted_currencies"></textarea>

<div class="swatches-container">
    <div class="header clearfix">
        <div class="swatch-item">
            {{ trans('plugins/job-board::settings.currency.name') }}
        </div>
        <div class="swatch-item">
            {{ trans('plugins/job-board::settings.currency.symbol') }}
        </div>
        <div class="swatch-item swatch-decimals">
            {{ trans('plugins/job-board::settings.currency.number_of_decimals') }}
        </div>
        <div class="swatch-item swatch-exchange-rate">
            {{ trans('plugins/job-board::settings.currency.exchange_rate') }}
        </div>
        <div class="swatch-item swatch-is-prefix-symbol">
            {{ trans('plugins/job-board::settings.currency.is_prefix_symbol') }}
        </div>
        <div class="swatch-is-default">
            {{ trans('plugins/job-board::settings.currency.is_default') }}
        </div>
        <div class="remove-item">{{ trans('plugins/job-board::settings.currency.remove') }}</div>
    </div>

    <ul class="swatches-list"></ul>

    <div class="d-flex justify-content-between w-100 align-items-center">
        <a class="js-add-new-attribute" href="javascript:void(0)">
            {{ trans('plugins/job-board::settings.currency.new_currency') }}
        </a>
        <x-core::form.helper-text>
            {{ trans('plugins/job-board::settings.currency.instruction') }}
        </x-core::form.helper-text>
    </div>
</div>
