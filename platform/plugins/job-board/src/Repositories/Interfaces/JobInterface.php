<?php

namespace Botble\JobBoard\Repositories\Interfaces;

use Botble\Support\Repositories\Interfaces\RepositoryInterface;

interface JobInterface extends RepositoryInterface
{
    public function getJobs(array $filters = [], array $params = []);

    public function getFeaturedJobs(int $limit = 10, array $with = []);

    public function getRecentJobs(int $limit = 10, array $with = []);

    public function getPopularJobs(int $limit = 10, array $with = []);

    public function countActiveJobs();
}
