@foreach($reviews as $review)
    @include(Theme::getThemeNamespace('views.job-board.partials.review-item'), ['review' => $review])
@endforeach
<div class="review-pagination d-flex justify-content-center mt-3">
    {!! $reviews->onEachSide(1)->links(Theme::getThemeNamespace('partials.pagination')) !!}
</div>
