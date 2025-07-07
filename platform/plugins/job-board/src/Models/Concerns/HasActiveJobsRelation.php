<?php

namespace Botble\JobBoard\Models\Concerns;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait HasActiveJobsRelation
{
    public function activeJobs(): BelongsToMany|HasMany|MorphMany
    {
        return $this->jobs()->active(); // @phpstan-ignore-line
    }
}
