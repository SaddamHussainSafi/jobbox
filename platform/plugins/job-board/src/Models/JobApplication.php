<?php

namespace Botble\JobBoard\Models;

use Botble\Base\Casts\SafeContent;
use Botble\Base\Models\BaseModel;
use Botble\JobBoard\Enums\JobApplicationStatusEnum;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JobApplication extends BaseModel
{
    protected $table = 'jb_applications';

    protected $fillable = [
        'first_name',
        'last_name',
        'phone',
        'email',
        'resume',
        'cover_letter',
        'message',
        'job_id',
        'account_id',
        'status',
    ];

    protected $casts = [
        'status' => JobApplicationStatusEnum::class,
        'first_name' => SafeContent::class,
        'last_name' => SafeContent::class,
        'message' => SafeContent::class,
    ];

    public function job(): BelongsTo
    {
        return $this->belongsTo(Job::class, 'job_id')->withDefault();
    }

    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'account_id')->withDefault();
    }

    public function getFullNameAttribute(): string
    {
        if ($this->account->id && $this->account->is_public_profile) {
            return $this->account->name;
        }

        return $this->first_name . ' ' . $this->last_name;
    }

    public function getJobUrlAttribute(): string
    {
        $url = '';
        if (! $this->job->is_expired) {
            $url = $this->job->url;
        }

        return $url;
    }
}
