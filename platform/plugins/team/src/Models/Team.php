<?php

namespace Botble\Team\Models;

use Botble\Base\Casts\SafeContent;
use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Models\BaseModel;

class Team extends BaseModel
{
    protected $table = 'teams';

    protected $fillable = [
        'name',
        'description',
        'content',
        'title',
        'phone',
        'email',
        'address',
        'website',
        'photo',
        'location',
        'socials',
        'status',
    ];

    protected $casts = [
        'status' => BaseStatusEnum::class,
        'socials' => 'json',
        'name' => SafeContent::class,
        'description' => SafeContent::class,
        'title' => SafeContent::class,
        'photo' => SafeContent::class,
        'location' => SafeContent::class,
    ];
}
