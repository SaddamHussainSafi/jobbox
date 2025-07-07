<?php

namespace Botble\JobBoard\Tables;

use Botble\JobBoard\Models\CustomField;
use Botble\Table\Abstracts\TableAbstract;
use Botble\Table\Actions\DeleteAction;
use Botble\Table\Actions\EditAction;
use Botble\Table\BulkActions\DeleteBulkAction;
use Botble\Table\BulkChanges\CreatedAtBulkChange;
use Botble\Table\BulkChanges\NameBulkChange;
use Botble\Table\Columns\CreatedAtColumn;
use Botble\Table\Columns\IdColumn;
use Botble\Table\Columns\NameColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Query\Builder as QueryBuilder;

class CustomFieldTable extends TableAbstract
{
    public function setup(): void
    {
        $this
            ->model(CustomField::class)
            ->addActions([
                EditAction::make()->route('job-board.custom-fields.edit'),
                DeleteAction::make()->route('job-board.custom-fields.destroy'),
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
            ]);

        return $this->applyScopes($query);
    }

    public function columns(): array
    {
        return [
            IdColumn::make(),
            NameColumn::make()->route('job-board.custom-fields.edit'),
            CreatedAtColumn::make(),
        ];
    }

    public function buttons(): array
    {
        return $this->addCreateButton(route('job-board.custom-fields.create'), 'job-board.custom-fields.create');
    }

    public function bulkActions(): array
    {
        return [
            DeleteBulkAction::make()->permission('job-board.custom-fields.destroy'),
        ];
    }

    public function getBulkChanges(): array
    {
        return [
            NameBulkChange::make(),
            CreatedAtBulkChange::make(),
        ];
    }
}
