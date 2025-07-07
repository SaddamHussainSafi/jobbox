<?php

namespace Botble\JobBoard\Repositories\Eloquent;

use Botble\Base\Models\BaseModel;
use Botble\JobBoard\Models\CustomField;
use Botble\JobBoard\Repositories\Interfaces\CustomFieldInterface;
use Botble\Support\Repositories\Eloquent\RepositoriesAbstract;
use Illuminate\Support\Facades\Auth;

class CustomFieldRepository extends RepositoriesAbstract implements CustomFieldInterface
{
    public function createOrUpdate($data, array $condition = []): BaseModel|bool
    {
        $item = parent::createOrUpdate($data, $condition);

        /**
         * @var CustomField $item
         */
        $item->authorable()->associate(Auth::user() ?? Auth::guard('account')->user());

        if ($item->save()) {
            $item->saveRelations($data);

            return $item;
        }

        return false;
    }
}
