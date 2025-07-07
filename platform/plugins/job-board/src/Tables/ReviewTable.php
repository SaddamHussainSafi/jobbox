<?php

namespace Botble\JobBoard\Tables;

use Botble\Base\Facades\BaseHelper;
use Botble\Base\Facades\Html;
use Botble\JobBoard\Models\Company;
use Botble\JobBoard\Models\Review;
use Botble\Table\Abstracts\TableAbstract;
use Botble\Table\Actions\DeleteAction;
use Botble\Table\BulkActions\DeleteBulkAction;
use Botble\Table\Columns\Column;
use Botble\Table\Columns\CreatedAtColumn;
use Botble\Table\Columns\FormattedColumn;
use Botble\Table\Columns\IdColumn;
use Botble\Table\Columns\StatusColumn;

class ReviewTable extends TableAbstract
{
    public function setup(): void
    {
        $this
            ->model(Review::class)
            ->addActions([
                DeleteAction::make()->route('reviews.destroy'),
            ])
            ->addBulkActions([
                DeleteBulkAction::make()->permission('reviews.destroy'),
            ])
            ->addColumns([
                IdColumn::make(),
                FormattedColumn::make('reviewable_id')
                    ->title(trans('plugins/job-board::review.account_or_company'))
                    ->alignLeft()
                    ->getValueUsing(function (FormattedColumn $column) {
                        $item = $column->getItem();

                        if (! $item->reviewable_id || ! $item->reviewable?->id) {
                            return '&mdash;';
                        }

                        return Html::link(
                            route(
                                $item->reviewable_type === Company::class ? 'companies.edit' : 'accounts.edit',
                                $item->reviewable_id
                            ),
                            BaseHelper::clean($item->reviewable->name)
                        )->toHtml();
                    }),
                FormattedColumn::make('created_by_id')
                    ->title(trans('plugins/job-board::review.reviewed_by'))
                    ->alignLeft()
                    ->getValueUsing(function (FormattedColumn $column) {
                        $item = $column->getItem();

                        if (! $item->created_by_id || ! $item->createdBy?->id) {
                            return '&mdash;';
                        }

                        return Html::link(
                            route(
                                $item->created_by_type === Company::class ? 'companies.edit' : 'accounts.edit',
                                $item->created_by_id
                            ),
                            BaseHelper::clean($item->createdBy->name)
                        )->toHtml();
                    }),
                FormattedColumn::make('star')
                    ->title(trans('plugins/job-board::review.star'))
                    ->getValueUsing(function (FormattedColumn $column) {
                        $item = $column->getItem();

                        return view('plugins/job-board::reviews.partials.rating', ['star' => $item->star])->render();
                    }),
                Column::make('review')
                    ->title(trans('plugins/job-board::review.review'))
                    ->alignLeft(),
                StatusColumn::make(),
                CreatedAtColumn::make(),
            ])
            ->queryUsing(function ($query) {
                return $query
                    ->select([
                        'id',
                        'star',
                        'review',
                        'reviewable_id',
                        'reviewable_type',
                        'created_by_id',
                        'created_by_type',
                        'status',
                        'created_at',
                    ])
                    ->with(['createdBy', 'reviewable']);
            });
    }
}
