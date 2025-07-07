<?php

namespace Botble\JobBoard\Repositories\Eloquent;

use Botble\JobBoard\Repositories\Interfaces\CurrencyInterface;
use Botble\Support\Repositories\Eloquent\RepositoriesAbstract;

class CurrencyRepository extends RepositoriesAbstract implements CurrencyInterface
{
    public function getAllCurrencies()
    {
        $data = $this->model
            ->orderBy('order')
            ->get();

        $this->resetModel();

        return $data;
    }
}
