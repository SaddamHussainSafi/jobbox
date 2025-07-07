<?php

namespace Botble\JobBoard\Models;

use Botble\Base\Casts\SafeContent;
use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Models\BaseModel;
use Botble\JobBoard\Models\Concerns\HasActiveJobsRelation;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JobExperience extends BaseModel
{
    use HasActiveJobsRelation;

    protected $table = 'jb_job_experiences';

    protected $fillable = [
        'name',
        'order',
        'is_default',
        'status',
    ];

    protected $casts = [
        'status' => BaseStatusEnum::class,
        'name' => SafeContent::class,
    ];

    public function jobs(): HasMany
    {
        return $this->hasMany(Job::class, 'job_experience_id');
    }
}
