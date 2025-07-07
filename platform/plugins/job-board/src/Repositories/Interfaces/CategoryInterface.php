<?php

namespace Botble\JobBoard\Repositories\Interfaces;

use Botble\Support\Repositories\Interfaces\RepositoryInterface;

interface CategoryInterface extends RepositoryInterface
{
    public function getFeaturedCategories(int $limit = 8, array $with = []);

    public function getCategories(array $with = []);
}
