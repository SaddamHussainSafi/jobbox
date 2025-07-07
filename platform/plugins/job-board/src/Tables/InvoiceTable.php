<?php

namespace Botble\JobBoard\Tables;

use Botble\JobBoard\Enums\InvoiceStatusEnum;
use Botble\JobBoard\Models\Currency;
use Botble\JobBoard\Models\Invoice;
use Botble\Table\Abstracts\TableAbstract;
use Botble\Table\Actions\DeleteAction;
use Botble\Table\Actions\EditAction;
use Botble\Table\BulkActions\DeleteBulkAction;
use Botble\Table\BulkChanges\CreatedAtBulkChange;
use Botble\Table\BulkChanges\NameBulkChange;
use Botble\Table\BulkChanges\StatusBulkChange;
use Botble\Table\Columns\Column;
use Botble\Table\Columns\CreatedAtColumn;
use Botble\Table\Columns\IdColumn;
use Botble\Table\Columns\LinkableColumn;
use Botble\Table\Columns\StatusColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Http\JsonResponse;

class InvoiceTable extends TableAbstract
{
    public function setup(): void
    {
        $this
            ->model(Invoice::class)
            ->addActions([
                EditAction::make()->route('invoice.edit'),
                DeleteAction::make()->route('invoice.destroy'),
            ]);
    }

    public function ajax(): JsonResponse
    {
        $data = $this->table
            ->eloquent($this->query())
            ->editColumn('customer_name', function (Invoice $item) {
                return $item->customer_name;
            })
            ->editColumn('amount', function (Invoice $item) {
                $item->loadMissing('payment');
                $payment = $item->payment;

                $currency = $payment ? Currency::query()->where('title', strtoupper($payment->currency))->first() : null;

                return format_price($item->amount, $currency);
            });

        return $this->toJson($data);
    }

    public function query(): Relation|Builder|QueryBuilder
    {
        $query = $this
            ->getModel()
            ->query()
            ->select([
                'id',
                'customer_name',
                'code',
                'amount',
                'payment_id',
                'created_at',
                'updated_at',
                'status',
            ]);

        return $this->applyScopes($query);
    }

    public function columns(): array
    {
        return [
            IdColumn::make(),
            Column::make('customer_name')
                ->title(trans('core/base::tables.name'))
                ->alignLeft(),
            LinkableColumn::make('code')
                ->title(trans('plugins/job-board::invoice.table.code'))
                ->route('invoice.edit')
                ->alignLeft(),
            Column::make('amount')
                ->title(trans('plugins/job-board::invoice.table.amount'))
                ->alignLeft(),
            CreatedAtColumn::make(),
            StatusColumn::make(),
        ];
    }

    public function bulkActions(): array
    {
        return [
            DeleteBulkAction::make()->permission('invoice.destroy'),
        ];
    }

    public function getBulkChanges(): array
    {
        return [
            NameBulkChange::make(),
            StatusBulkChange::make()->choices(InvoiceStatusEnum::labels()),
            CreatedAtBulkChange::make(),
        ];
    }
}
