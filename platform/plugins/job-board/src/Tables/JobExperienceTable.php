<?php

namespace Botble\JobBoard\Tables;

use Botble\JobBoard\Models\JobExperience;
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
use Botble\Table\Columns\NameColumn;
use Botble\Table\Columns\StatusColumn;
use Botble\Table\Columns\YesNoColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Query\Builder as QueryBuilder;

class JobExperienceTable extends TableAbstract
{
    public function setup(): void
    {
        $this
            ->model(JobExperience::class)
            ->addActions([
                EditAction::make()->route('job-experiences.edit'),
                DeleteAction::make()->route('job-experiences.destroy'),
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
                'order',
                'is_default',
                'created_at',
                'status',
            ]);

        return $this->applyScopes($query);
    }

    public function columns(): array
    {
        return [
            IdColumn::make(),
            NameColumn::make()->route('job-experiences.edit'),
            Column::make('order')
                ->title(trans('core/base::tables.order'))
                ->width(100),
            YesNoColumn::make('is_default')
                ->title(trans('core/base::forms.is_default'))
                ->width(100),
            StatusColumn::make(),
            CreatedAtColumn::make(),
        ];
    }

    public function buttons(): array
    {
        return $this->addCreateButton(route('job-experiences.create'), 'job-experiences.create');
    }

    public function bulkActions(): array
    {
        return [
            DeleteBulkAction::make()->permission('job-experiences.destroy'),
        ];
    }

    public function getBulkChanges(): array
    {
        return [
            NameBulkChange::make(),
            StatusBulkChange::make(),
            CreatedAtBulkChange::make(),
        ];
    }
}
