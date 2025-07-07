<?php

namespace Botble\JobBoard\Tables;

use Botble\Base\Facades\Html;
use Botble\JobBoard\Models\DegreeLevel;
use Botble\JobBoard\Models\DegreeType;
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
use Illuminate\Http\JsonResponse;

class DegreeTypeTable extends TableAbstract
{
    public function setup(): void
    {
        $this
            ->model(DegreeType::class)
            ->addActions([
                EditAction::make()->route('degree-types.edit'),
                DeleteAction::make()->route('degree-types.destroy'),
            ]);
    }

    public function ajax(): JsonResponse
    {
        $data = $this->table
            ->eloquent($this->query())
            ->editColumn('degree_level_id', function (DegreeType $item) {
                if (! $item->degree_level_id) {
                    return null;
                }

                return Html::link(route('degree-levels.edit', $item->degree_level_id), $item->level->name);
            });

        return $this->toJson($data);
    }

    public function query(): Relation|Builder|QueryBuilder
    {
        $query = $this->getModel()->query()->select([
            'id',
            'name',
            'degree_level_id',
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
            NameColumn::make()->route('degree-types.edit'),
            Column::make('degree_level_id')
                ->title(trans('plugins/job-board::degree-type.degree-level'))
                ->alignLeft(),
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
        return $this->addCreateButton(route('degree-types.create'), 'degree-types.create');
    }

    public function bulkActions(): array
    {
        return [
            DeleteBulkAction::make()->permission('degree-types.destroy'),
        ];
    }

    public function getBulkChanges(): array
    {
        return [
            NameBulkChange::make(),
            StatusBulkChange::make(),
            CreatedAtBulkChange::make(),
            'degree_level_id' => [
                'title' => trans('plugins/job-board::degree-type.degree-level'),
                'type' => 'select',
                'validate' => 'required|max:120',
                'callback' => 'getDegreeLevels',
            ],
        ];
    }

    public function getDegreeLevels(): array
    {
        return DegreeLevel::query()->pluck('name', 'id')->all();
    }
}
