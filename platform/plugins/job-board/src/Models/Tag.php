<?php

namespace Botble\JobBoard\Models;

use Botble\Base\Casts\SafeContent;
use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Models\BaseModel;
use Botble\JobBoard\Models\Concerns\HasActiveJobsRelation;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Tag extends BaseModel
{
    use HasActiveJobsRelation;

    protected $table = 'jb_tags';

    protected $fillable = [
        'name',
        'description',
        'status',
    ];

    protected $casts = [
        'status' => BaseStatusEnum::class,
        'name' => SafeContent::class,
        'description' => SafeContent::class,
    ];

    public function jobs(): BelongsToMany
    {
        return $this
            ->belongsToMany(Job::class, 'jb_jobs_tags', 'tag_id', 'job_id');
    }
}
