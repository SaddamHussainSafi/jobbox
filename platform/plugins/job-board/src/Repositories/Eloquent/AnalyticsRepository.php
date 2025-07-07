<?php

namespace Botble\JobBoard\Repositories\Eloquent;

use Botble\JobBoard\Repositories\Interfaces\AnalyticsInterface;
use Botble\Support\Repositories\Eloquent\RepositoriesAbstract;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

class AnalyticsRepository extends RepositoriesAbstract implements AnalyticsInterface
{
    public function getReferrers(int|string $jobId, int $limit = 10)
    {
        $data = $this->model
            ->where('job_id', $jobId)
            ->selectRaw('referer, COUNT(*) as total')
            ->groupBy('referer')->latest('total')
            ->limit($limit ?: 10);

        return $this->applyBeforeExecuteQuery($data)->get();
    }

    public function getViews(int|string $jobId): int
    {
        return $this->model->where('job_id', $jobId)->count();
    }

    public function getTodayViews(int|string $jobId): int
    {
        $data = $this->model
            ->where('job_id', $jobId)
            ->whereDate('created_at', Carbon::now());

        return $this->applyBeforeExecuteQuery($data)->count();
    }

    public function getCountriesViews(int|string $jobId): Collection
    {
        $data = $this->model
            ->where('job_id', $jobId)
            ->selectRaw('country_full, COUNT(*) as total')
            ->groupBy('country_full')->latest('total')
            ->limit(10);

        return $this->applyBeforeExecuteQuery($data)->get();
    }
}
