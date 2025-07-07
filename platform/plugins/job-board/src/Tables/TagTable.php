<?php

namespace Botble\JobBoard\Tables;

use Botble\JobBoard\Models\Tag;
use Botble\Table\Abstracts\TableAbstract;
use Botble\Table\Actions\DeleteAction;
use Botble\Table\Actions\EditAction;
use Botble\Table\BulkActions\DeleteBulkAction;
use Botble\Table\BulkChanges\CreatedAtBulkChange;
use Botble\Table\BulkChanges\NameBulkChange;
use Botble\Table\BulkChanges\StatusBulkChange;
use Botble\Table\Columns\CreatedAtColumn;
use Botble\Table\Columns\IdColumn;
use Botble\Table\Columns\NameColumn;
use Botble\Table\Columns\StatusColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Query\Builder as QueryBuilder;

class TagTable extends TableAbstract
{
    public function setup(): void
    {
        $this
            ->model(Tag::class)
            ->addActions([
                EditAction::make()->route('job-board.tag.edit'),
                DeleteAction::make()->route('job-board.tag.destroy'),
            ]);
    }

    public function query(): Relation|Builder|QueryBuilder
    {
        $query = $this->getModel()->query()->select([
            'id',
            'name',
            'created_at',
            'status',
        ]);

        return $this->applyScopes($query);
    }

    public function columns(): array
    {
        return [
            IdColumn::make(),
            NameColumn::make()->route('job-board.tag.edit'),
            CreatedAtColumn::make(),
            StatusColumn::make(),
        ];
    }

    public function buttons(): array
    {
        return $this->addCreateButton(route('job-board.tag.create'), 'job-board.tag.create');
    }

    public function bulkActions(): array
    {
        return [
            DeleteBulkAction::make()->permission('job-board.tag.destroy'),
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
