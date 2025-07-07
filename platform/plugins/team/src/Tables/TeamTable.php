<?php

namespace Botble\Team\Tables;

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
use Botble\Table\Columns\ImageColumn;
use Botble\Table\Columns\NameColumn;
use Botble\Table\Columns\StatusColumn;
use Botble\Table\HeaderActions\CreateHeaderAction;
use Botble\Team\Models\Team;
use Illuminate\Database\Eloquent\Builder;

class TeamTable extends TableAbstract
{
    public function setup(): void
    {
        $this
            ->model(Team::class)
            ->addHeaderAction(CreateHeaderAction::make()->route('team.create'))
            ->addActions([
                EditAction::make()->route('team.edit'),
                DeleteAction::make()->route('team.destroy'),
            ])
            ->addBulkActions([
                DeleteBulkAction::make()->permission('team.destroy'),
            ])
            ->addBulkChanges([
                NameBulkChange::make(),
                StatusBulkChange::make(),
                CreatedAtBulkChange::make(),
            ])
            ->addColumns([
                IdColumn::make(),
                ImageColumn::make('photo')
                    ->title(trans('plugins/team::team.forms.photo')),
                NameColumn::make()->route('team.edit'),
                Column::make('title')
                    ->title(trans('plugins/team::team.forms.title'))
                    ->alignLeft(),
                Column::make('location')
                    ->title(trans('plugins/team::team.forms.location')),
                CreatedAtColumn::make(),
                StatusColumn::make(),
            ])
            ->queryUsing(fn (Builder $query) => $query->select([
                'id',
                'name',
                'title',
                'photo',
                'location',
                'socials',
                'created_at',
                'status',
            ]));
    }
}
