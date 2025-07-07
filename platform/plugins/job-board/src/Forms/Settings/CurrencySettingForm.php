<?php

namespace Botble\JobBoard\Forms\Settings;

use Botble\Base\Facades\Assets;
use Botble\JobBoard\Http\Requests\Settings\CurrencySettingRequest;
use Botble\JobBoard\Models\Currency;
use Botble\Setting\Forms\SettingForm;

class CurrencySettingForm extends SettingForm
{
    public function setup(): void
    {
        parent::setup();

        Assets::addScripts(['jquery-ui'])
            ->addScriptsDirectly('vendor/core/plugins/job-board/js/currencies.js')
            ->addStylesDirectly('vendor/core/plugins/job-board/css/currencies.css');

        $currencies = Currency::query()
            ->orderBy('order')
            ->get()
            ->all();

        $this
            ->setSectionTitle(trans('plugins/job-board::settings.currency.title'))
            ->setSectionDescription(trans('plugins/job-board::settings.currency.description'))
            ->columns()
            ->setFormOptions([
                'class' => 'main-setting-form',
            ])
            ->contentOnly()
            ->setValidatorClass(CurrencySettingRequest::class)
            ->add('job_board_enable_auto_detect_visitor_currency', 'onOffCheckbox', [
                'label' => trans('plugins/job-board::settings.currency.enable_auto_detect_visitor_currency'),
                'value' => setting('job_board_enable_auto_detect_visitor_currency', false),
                'help_block' => [
                    'text' => trans('plugins/job-board::settings.currency.auto_detect_visitor_currency_description'),
                ],
                'colspan' => 2,
            ])
            ->add('job_board_add_space_between_price_and_currency', 'onOffCheckbox', [
                'label' => trans('plugins/job-board::settings.currency.add_space_between_price_and_currency'),
                'value' => setting('job_board_add_space_between_price_and_currency', false),
                'colspan' => 2,
            ])
            ->add('job_board_thousands_separator', 'customSelect', [
                'label' => trans('plugins/job-board::settings.currency.thousands_separator'),
                'selected' => setting('job_board_thousands_separator', '.'),
                'choices' => [
                    ',' => trans('plugins/job-board::settings.currency.separator_comma'),
                    '.' => trans('plugins/job-board::settings.currency.separator_period'),
                    'space' => trans('plugins/job-board::settings.currency.separator_space'),
                ],
            ])
            ->add('job_board_decimal_separator', 'customSelect', [
                'label' => trans('plugins/job-board::settings.currency.decimal_separator'),
                'selected' => setting('job_board_decimal_separator', ','),
                'choices' => [
                    ',' => trans('plugins/job-board::settings.currency.separator_comma'),
                    '.' => trans('plugins/job-board::settings.currency.separator_period'),
                    'space' => trans('plugins/job-board::settings.currency.separator_space'),
                ],
            ])
            ->add('currency_table', 'html', [
                'html' => view('plugins/job-board::settings.partials.currency-table', compact('currencies')),
                'colspan' => 2,
            ]);
    }
}
