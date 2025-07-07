<?php

namespace Botble\SocialLogin\Models;

use Botble\Base\Models\BaseModel;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class SocialLogin extends BaseModel
{
    protected $table = 'social_logins';

    protected $fillable = [
        'user_id',
        'user_type',
        'provider',
        'provider_id',
        'token',
        'refresh_token',
        'token_expires_at',
        'provider_data',
    ];

    protected $casts = [
        'token_expires_at' => 'datetime',
        'provider_data' => 'json',
    ];

    public function user(): MorphTo
    {
        return $this->morphTo();
    }

    public function isExpired(): bool
    {
        if (! $this->token_expires_at) {
            return false;
        }

        return $this->token_expires_at->isPast();
    }

    public function isExpiringSoon(int $days = 1): bool
    {
        if (! $this->token_expires_at) {
            return false;
        }

        return $this->token_expires_at->isBefore(Carbon::now()->addDays($days));
    }
}
