<?php

namespace Botble\JobBoard\Http\Controllers;

use Botble\Base\Facades\Assets;
use Botble\Base\Http\Actions\DeleteResourceAction;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Supports\Breadcrumb;
use Botble\JobBoard\Models\Review;
use Botble\JobBoard\Tables\ReviewTable;

class ReviewController extends BaseController
{
    protected function breadcrumb(): Breadcrumb
    {
        return parent::breadcrumb()
            ->add(trans('plugins/job-board::job-board.name'))
            ->add(trans('plugins/job-board::review.name'), route('reviews.index'));
    }

    public function index(ReviewTable $dataTable)
    {
        $this->pageTitle(trans('plugins/job-board::review.name'));

        Assets::addStylesDirectly('vendor/core/plugins/job-board/css/review.css');

        return $dataTable->renderTable();
    }

    public function destroy(Review $review)
    {
        return DeleteResourceAction::make($review);
    }
}
