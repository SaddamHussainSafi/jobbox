<?php

namespace Botble\JobBoard\Repositories\Interfaces;

use Botble\Support\Repositories\Interfaces\RepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface AccountActivityLogInterface extends RepositoryInterface
{
    public function getAllLogs(int $accountId, int $paginate = 10): LengthAwarePaginator;
}
