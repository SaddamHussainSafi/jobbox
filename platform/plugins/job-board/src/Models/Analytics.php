<?php

namespace Botble\JobBoard\Models;

use Botble\Base\Models\BaseModel;

class Analytics extends BaseModel
{
    protected $table = 'jb_analytics';

    protected $fillable = [
        'job_id',
        'country',
        'country_full',
        'referer',
        'ip_address',
    ];

    public function getRefererAttribute(): ?string
    {
        return $this->attributes['referer'] ?? __('Direct / Unknown');
    }
}
