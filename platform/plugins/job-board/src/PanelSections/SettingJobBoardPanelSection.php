<?php

namespace Botble\JobBoard\PanelSections;

use Botble\Base\PanelSections\PanelSection;
use Botble\Base\PanelSections\PanelSectionItem;

class SettingJobBoardPanelSection extends PanelSection
{
    public function setup(): void
    {
        $this
            ->setId('settings.job-board')
            ->setTitle(trans('plugins/job-board::settings.job_board'))
            ->withPriority(1000)
            ->addItems([
                PanelSectionItem::make('settings.job-board.general_settings')
                    ->setTitle(trans('plugins/job-board::settings.general.title'))
                    ->withIcon('ti ti-settings')
                    ->withDescription(trans('plugins/job-board::settings.general.description'))
                    ->withPriority(10)
                    ->withRoute('job-board.settings.general'),
                PanelSectionItem::make('settings.job-board.currency_settings')
                    ->setTitle(trans('plugins/job-board::settings.currency.title'))
                    ->withIcon('ti ti-coin')
                    ->withPriority(20)
                    ->withDescription(trans('plugins/job-board::settings.currency.description'))
                    ->withRoute('job-board.settings.currencies'),
                PanelSectionItem::make('settings.job-board.invoice_settings')
                    ->setTitle(trans('plugins/job-board::settings.invoice.title'))
                    ->withIcon('ti ti-file-invoice')
                    ->withPriority(30)
                    ->withDescription(trans('plugins/job-board::settings.invoice.description'))
                    ->withRoute('job-board.settings.invoices'),
                PanelSectionItem::make('settings.job-board.invoice_template_settings')
                    ->setTitle(trans('plugins/job-board::settings.invoice_template.title'))
                    ->withIcon('ti ti-template')
                    ->withPriority(40)
                    ->withDescription(trans('plugins/job-board::settings.invoice_template.description'))
                    ->withRoute('job-board.settings.invoice-template'),
            ]);
    }
}
