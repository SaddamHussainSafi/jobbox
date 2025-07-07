<?php

namespace Botble\JobBoard\Repositories\Interfaces;

use Botble\Support\Repositories\Interfaces\RepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface CompanyInterface extends RepositoryInterface
{
    /**
     * @param string $query
     * @param int $limit
     * @param int $paginate
     * @return LengthAwarePaginator|Collection
     */
    public function getSearch($query, $limit = 10, $paginate = 10);
}
