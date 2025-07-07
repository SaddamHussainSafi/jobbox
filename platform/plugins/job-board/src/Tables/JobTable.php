<?php

namespace Botble\JobBoard\Tables;

use Botble\Base\Facades\BaseHelper;
use Botble\Base\Facades\Html;
use Botble\JobBoard\Enums\JobStatusEnum;
use Botble\JobBoard\Enums\ModerationStatusEnum;
use Botble\JobBoard\Models\Job;
use Botble\Table\Abstracts\TableAbstract;
use Botble\Table\Actions\Action;
use Botble\Table\Actions\DeleteAction;
use Botble\Table\Actions\EditAction;
use Botble\Table\BulkActions\DeleteBulkAction;
use Botble\Table\BulkChanges\CreatedAtBulkChange;
use Botble\Table\BulkChanges\NameBulkChange;
use Botble\Table\BulkChanges\StatusBulkChange;
use Botble\Table\Columns\CreatedAtColumn;
use Botble\Table\Columns\EnumColumn;
use Botble\Table\Columns\FormattedColumn;
use Botble\Table\Columns\IdColumn;
use Botble\Table\Columns\NameColumn;
use Botble\Table\Columns\StatusColumn;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Eloquent\Relations\Relation as EloquentRelation;
use Illuminate\Database\Query\Builder as QueryBuilder;

class JobTable extends TableAbstract
{
    public function setup(): void
    {
        $this
            ->model(Job::class)
            ->addActions([
                Action::make('analytics')
                    ->route('jobs.analytics')
                    ->label(trans('plugins/job-board::job.analytics.title'))
                    ->icon('ti ti-chart-line')
                    ->color('info'),
                EditAction::make()->route('jobs.edit'),
                DeleteAction::make()->route('jobs.destroy'),
            ]);
    }

    public function query(): Relation|Builder|QueryBuilder
    {
        $query = $this
            ->getModel()
            ->query()
            ->select([
                'id',
                'name',
                'created_at',
                'status',
                'moderation_status',
                'expire_date',
                'never_expired',
                'application_closing_date',
            ]);

        return $this->applyScopes($query);
    }

    public function columns(): array
    {
        return [
            IdColumn::make(),
            NameColumn::make()->route('jobs.edit'),
            CreatedAtColumn::make(),
            FormattedColumn::make('expire_date')
                ->title(__('Expire date'))
                ->width(150)
                ->getValueUsing(function (FormattedColumn $column) {
                    $item = $column->getItem();

                    if ($item->never_expired) {
                        return BaseHelper::renderIcon('ti ti-infinity');
                    }

                    if ($item->expire_date->isPast()) {
                        return Html::tag('span', $item->expire_date->toDateString(), ['class' => 'text-danger'])->toHtml();
                    }

                    if (Carbon::now()->diffInDays($item->expire_date) < 3) {
                        return Html::tag('span', $item->expire_date->toDateString(), ['class' => 'text-warning'])->toHtml();
                    }

                    return $item->expire_date->toDateString();
                }),
            StatusColumn::make(),
            EnumColumn::make('moderation_status')
                ->title(trans('plugins/job-board::job.moderation_status'))
                ->width(150),
        ];
    }

    public function buttons(): array
    {
        $buttons = $this->addCreateButton(route('jobs.create'), 'jobs.create');

        if ($this->hasPermission('import-jobs.index')) {
            $buttons['import'] = [
                'link' => route('import-jobs.index'),
                'text' =>
                    BaseHelper::renderIcon('ti ti-upload')
                    . trans('plugins/job-board::import.name'),
            ];
        }

        if ($this->hasPermission('export-jobs.index')) {
            $buttons['export'] = [
                'link' => route('export-jobs.index'),
                'text' =>
                    BaseHelper::renderIcon('ti ti-download')
                    . trans('plugins/job-board::export.jobs.name'),
            ];
        }

        return $buttons;
    }

    public function bulkActions(): array
    {
        return [
            DeleteBulkAction::make()->permission('jobs.destroy'),
        ];
    }

    public function getBulkChanges(): array
    {
        return [
            NameBulkChange::make(),
            StatusBulkChange::make()->choices(JobStatusEnum::labels()),
            CreatedAtBulkChange::make(),
            'moderation_status' => [
                'title' => trans('plugins/job-board::job.moderation_status'),
                'type' => 'select',
                'choices' => ModerationStatusEnum::labels(),
                'validate' => 'required|in:' . implode(',', ModerationStatusEnum::values()),
            ],
            'type' => [
                'title' => __('Type'),
                'type' => 'select',
                'choices' => [
                    'expired' => __('Expired Jobs'),
                    'without-company' => __('Jobs without a company'),
                ],
            ],
        ];
    }

    public function getOperationsHeading(): array
    {
        return [
            'operations' => [
                'title' => trans('core/base::tables.operations'),
                'width' => '300px',
                'class' => 'text-center',
                'orderable' => false,
                'searchable' => false,
                'exportable' => false,
                'printable' => false,
            ],
        ];
    }

    public function applyFilterCondition(
        EloquentBuilder|QueryBuilder|EloquentRelation $query,
        string $key,
        string $operator,
        ?string $value
    ): EloquentRelation|EloquentBuilder|QueryBuilder {
        if ($key == 'type') {
            switch ($value) {
                case 'expired':
                    // @phpstan-ignore-next-line
                    return $query->expired();
                case 'without-company':
                    return $query->whereNull('company_id');
            }
        }

        return parent::applyFilterCondition($query, $key, $operator, $value);
    }
}
