<?php

namespace Botble\JobBoard\Repositories\Eloquent;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\JobBoard\Repositories\Interfaces\CategoryInterface;
use Botble\Support\Repositories\Eloquent\RepositoriesAbstract;

class CategoryRepository extends RepositoriesAbstract implements CategoryInterface
{
    public function getFeaturedCategories(int $limit = 8, array $with = [])
    {
        $data = $this->model
            ->where(['status' => BaseStatusEnum::PUBLISHED, 'is_featured' => true])
            ->orderBy('order', 'ASC')->latest()
            ->withCount(['activeJobs'])
            ->limit($limit)
            ->with(array_merge($with, ['slugable', 'metadata']));

        return $this->applyBeforeExecuteQuery($data)->get();
    }

    public function getCategories(array $with = [])
    {
        $data = $this->model
            ->where('status', BaseStatusEnum::PUBLISHED)
            ->orderBy('order')->latest()
            ->withCount(['activeJobs'])
            ->with(array_merge($with, ['slugable']));

        return $this->applyBeforeExecuteQuery($data)->get();
    }
}
