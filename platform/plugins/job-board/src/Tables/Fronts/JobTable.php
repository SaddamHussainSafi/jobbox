<?php

namespace Botble\JobBoard\Tables\Fronts;

use Botble\Base\Facades\BaseHelper;
use Botble\Base\Facades\Html;
use Botble\JobBoard\Models\Job;
use Botble\Table\Abstracts\TableAbstract;
use Botble\Table\Actions\Action;
use Botble\Table\Actions\DeleteAction;
use Botble\Table\Actions\EditAction;
use Botble\Table\Columns\CreatedAtColumn;
use Botble\Table\Columns\EnumColumn;
use Botble\Table\Columns\FormattedColumn;
use Botble\Table\Columns\IdColumn;
use Botble\Table\Columns\NameColumn;
use Botble\Table\Columns\StatusColumn;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Query\Builder as QueryBuilder;

class JobTable extends TableAbstract
{
    public function setup(): void
    {
        $this
            ->model(Job::class)
            ->addActions([
                Action::make('analytics')
                    ->route('public.account.jobs.analytics')
                    ->label(__('Analytics'))
                    ->icon('ti ti-chart-line')
                    ->color('info'),
                EditAction::make()->route('public.account.jobs.edit'),
                DeleteAction::make()->route('public.account.jobs.destroy'),
            ]);
    }

    public function query(): Relation|Builder|QueryBuilder
    {
        // @phpstan-ignore-next-line
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
            ])
            ->byAccount(auth('account')->id());

        return $this->applyScopes($query);
    }

    public function columns(): array
    {
        return [
            IdColumn::make(),
            NameColumn::make()->route('public.account.jobs.edit'),
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
        if (! auth('account')->user()->canPost()) {
            return [];
        }

        return $this->addCreateButton(route('public.account.jobs.create'));
    }
}
