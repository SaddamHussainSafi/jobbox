<?php

namespace Botble\JobBoard\Repositories\Interfaces;

use Botble\Support\Repositories\Interfaces\RepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

interface AnalyticsInterface extends RepositoryInterface
{
    public function getReferrers(int|string $jobId, int $limit = 10);

    public function getViews(int|string $jobId): int;

    public function getTodayViews(int|string $jobId): int;

    public function getCountriesViews(int|string $jobId): Collection;
}
