<?php

namespace Botble\JobBoard\Http\Controllers\Fronts;

use Botble\Base\Http\Controllers\BaseController;
use Botble\JobBoard\Facades\JobBoardHelper;
use Botble\JobBoard\Http\Requests\AjaxReviewRequest;
use Botble\JobBoard\Http\Requests\StoreReviewRequest;
use Botble\JobBoard\Models\Account;
use Botble\JobBoard\Models\Company;
use Botble\JobBoard\Models\Review;
use Illuminate\Support\Facades\Auth;

class ReviewController extends BaseController
{
    public function __construct()
    {
        abort_unless(JobBoardHelper::isEnabledReview(), 404);
    }

    public function store(StoreReviewRequest $request)
    {
        /**
         * @var Account $account
         */
        $account = Auth::guard('account')->user();

        /**
         * @var Company|Account|null $reviewable
         */
        $reviewable = match ($request->input('reviewable_type')) {
            Company::class => Company::query()->findOrFail($request->input('reviewable_id')),
            Account::class => Account::query()->findOrFail($request->input('reviewable_id')),
            default => null,
        };

        if (! $reviewable || ! $account->canReview($reviewable)) {
            return $this
                ->httpResponse()
                ->setError()
                ->setMessage(__('You can not review.'));
        }

        $formData = [
            'reviewable_type' => $request->input('reviewable_type'),
            'reviewable_id' => $request->input('reviewable_id'),
            'created_by_type' => $account->isJobSeeker() ? Account::class : Company::class,
            'created_by_id' => $account->isJobSeeker() ? $account->getKey() : $request->input('company_id'),
        ];

        if (Review::query()->where($formData)->exists()) {
            return $this
                ->httpResponse()
                ->setError()
                ->setMessage(__('You have already reviewed.'));
        }

        Review::query()->create(array_merge($formData, [
            'star' => $request->input('star'),
            'review' => $request->input('review'),
        ]));

        return $this
            ->httpResponse()->setMessage(__('Added review successfully!'));
    }

    public function loadMore(AjaxReviewRequest $request)
    {
        abort_unless($request->ajax(), 404);

        $reviewable = match ($request->input('reviewable_type')) {
            Company::class => Company::query()->findOrFail($request->input('reviewable_id')),
            Account::class => Account::query()->findOrFail($request->input('reviewable_id')),
            default => null,
        };

        abort_unless($reviewable, 404);

        $reviews = Review::query()
            ->where('reviewable_type', $request->input('reviewable_type'))
            ->where('reviewable_id', $request->input('reviewable_id'))
            ->latest()
            ->paginate(10);

        return JobBoardHelper::view('partials.review-load', compact('reviews'))->render();
    }
}
