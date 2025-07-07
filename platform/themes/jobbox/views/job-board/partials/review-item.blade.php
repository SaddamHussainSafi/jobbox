<div class="d-sm-flex align-items-top review-item">
    <div class="flex-shrink-0">
        <img class="rounded-circle avatar-md img-thumbnail review-user-avatar" src="{{ $review->createdBy->avatar_thumb_url ?? RvMedia::getImageUrl($review->createdBy?->logo) }}" alt="{{ $review->createdBy?->name }}">
    </div>
    <div class="flex-grow-1 ms-sm-3">
        <div>
            <p class="text-muted float-end fs-14 mb-2">{{ $review->created_at?->translatedFormat('M d, Y') }}</p>
            <h6 class="mt-sm-0 mt-3 mb-1">{{ $review->createdBy?->name }}</h6>
            <div class="text-warning review-rating mb-2">
                {!! Theme::partial('rating-star', ['star' => $review->star]) !!}
            </div>
            <p class="text-muted fst-italic">{{ $review->review }}</p>
        </div>
    </div>
</div>
