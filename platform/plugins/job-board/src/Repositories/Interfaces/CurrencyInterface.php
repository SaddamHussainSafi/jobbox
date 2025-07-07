<?php

namespace Botble\JobBoard\Repositories\Interfaces;

use Botble\Support\Repositories\Interfaces\RepositoryInterface;
use Illuminate\Support\Collection;

interface CurrencyInterface extends RepositoryInterface
{
    /**
     * @return Collection
     */
    public function getAllCurrencies();
}
