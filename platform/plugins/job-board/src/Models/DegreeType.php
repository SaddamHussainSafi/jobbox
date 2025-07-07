<?php

namespace Botble\JobBoard\Models;

use Botble\Base\Casts\SafeContent;
use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DegreeType extends BaseModel
{
    protected $table = 'jb_degree_types';

    protected $fillable = [
        'name',
        'degree_level_id',
        'order',
        'is_default',
        'status',
    ];

    protected $casts = [
        'status' => BaseStatusEnum::class,
        'name' => SafeContent::class,
    ];

    public function level(): BelongsTo
    {
        return $this->belongsTo(DegreeLevel::class, 'degree_level_id')->withDefault();
    }
}
