<?php

namespace Botble\Payment\Tables;

use Botble\Payment\Models\PaymentLog;
use Botble\Table\Abstracts\TableAbstract;
use Botble\Table\Actions\Action;
use Botble\Table\Actions\DeleteAction;
use Botble\Table\BulkActions\DeleteBulkAction;
use Botble\Table\Columns\Column;
use Botble\Table\Columns\DateTimeColumn;
use Botble\Table\Columns\EnumColumn;
use Botble\Table\Columns\IdColumn;
use Illuminate\Database\Eloquent\Builder;

class PaymentLogTable extends TableAbstract
{
    public function setup(): void
    {
        $this
            ->model(PaymentLog::class)
            ->addActions([
                Action::make('view')
                    ->icon('ti ti-eye')
                    ->color('info')
                    ->route('payments.logs.show')
                    ->label(trans('core/base::tables.view')),
                DeleteAction::make()->route('payments.logs.destroy'),
            ])
            ->addColumns([
                IdColumn::make(),
                EnumColumn::make('payment_method'),
                Column::make('ip_address'),
                DateTimeColumn::make('created_at'),
            ])
            ->addBulkActions([
                DeleteBulkAction::make()->permission('payments.logs.destroy'),
            ])
            ->queryUsing(
                fn (Builder $query) => $query->select(['id', 'payment_method', 'request', 'ip_address', 'created_at'])
            );
    }
}
