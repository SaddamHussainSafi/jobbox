<?php

namespace Botble\JobBoard\Models;

use Botble\Base\Casts\SafeContent;
use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Review extends BaseModel
{
    protected $table = 'jb_reviews';

    protected $fillable = [
        'reviewable_type',
        'reviewable_id',
        'created_by_type',
        'created_by_id',
        'star',
        'review',
        'status',
    ];

    protected $casts = [
        'status' => BaseStatusEnum::class,
        'review' => SafeContent::class,
    ];

    /**
     * @deprecated since 15 May 2023. Use createdBy() instead.
     */
    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'created_by_id')->withDefault();
    }

    /**
     * @deprecated since 15 May 2023. Use reviewable() instead.
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'reviewable_id')->withDefault();
    }

    public function reviewable(): MorphTo
    {
        return $this->morphTo();
    }

    public function createdBy(): MorphTo
    {
        return $this->morphTo();
    }
}
