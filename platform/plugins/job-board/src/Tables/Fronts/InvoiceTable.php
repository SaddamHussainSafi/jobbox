<?php

namespace Botble\JobBoard\Tables\Fronts;

use Botble\JobBoard\Models\Account;
use Botble\JobBoard\Models\Currency;
use Botble\JobBoard\Models\Invoice;
use Botble\Table\Abstracts\TableAbstract;
use Botble\Table\Actions\Action;
use Botble\Table\Columns\Column;
use Botble\Table\Columns\CreatedAtColumn;
use Botble\Table\Columns\IdColumn;
use Botble\Table\Columns\StatusColumn;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Http\JsonResponse;

class InvoiceTable extends TableAbstract
{
    protected Authenticatable|null|Account $account;

    public function setup(): void
    {
        $this
            ->model(Invoice::class)
            ->addActions([
                Action::make('show')
                    ->route('public.account.invoices.show')
                    ->label(__('View'))
                    ->icon('ti ti-eye'),
            ]);
    }

    public function ajax(): JsonResponse
    {
        $data = $this->table
            ->eloquent($this->query())
            ->editColumn('code', function (Invoice $item) {
                return $item->code;
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
                'code',
                'amount',
                'payment_id',
                'status',
                'created_at',
            ])
            ->whereHas('payment', function (Builder $query): void {
                $query->where('customer_id', auth('account')->id());
            });

        return $this->applyScopes($query);
    }

    public function columns(): array
    {
        return [
            IdColumn::make(),
            Column::make('code')
                ->title(trans('plugins/job-board::invoice.table.code'))
                ->alignLeft(),
            Column::make('amount')
                ->title(trans('plugins/job-board::invoice.table.amount'))
                ->alignLeft(),
            CreatedAtColumn::make(),
            StatusColumn::make(),
        ];
    }

    public function getDefaultButtons(): array
    {
        return [
            'export',
            'reload',
        ];
    }
}
