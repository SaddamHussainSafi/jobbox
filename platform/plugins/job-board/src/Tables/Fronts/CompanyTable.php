<?php

namespace Botble\JobBoard\Tables\Fronts;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Facades\Html;
use Botble\JobBoard\Facades\JobBoardHelper;
use Botble\JobBoard\Models\Account;
use Botble\JobBoard\Models\Company;
use Botble\Media\Facades\RvMedia;
use Botble\Table\Abstracts\TableAbstract;
use Botble\Table\Actions\DeleteAction;
use Botble\Table\Actions\EditAction;
use Botble\Table\Columns\Column;
use Botble\Table\Columns\CreatedAtColumn;
use Botble\Table\Columns\IdColumn;
use Botble\Table\Columns\NameColumn;
use Botble\Table\Columns\StatusColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Http\JsonResponse;

class CompanyTable extends TableAbstract
{
    public function setup(): void
    {
        $this
            ->model(Company::class)
            ->addActions([
                EditAction::make()->route('public.account.companies.edit'),
                DeleteAction::make()->route('public.account.companies.destroy'),
            ]);
    }

    public function ajax(): JsonResponse
    {
        $data = $this->table
            ->eloquent($this->query())
            ->editColumn('logo', function (Company $item) {
                return Html::image(
                    RvMedia::getImageUrl($item->logo, 'thumb', false, RvMedia::getDefaultImage()),
                    $item->name,
                    ['width' => 50]
                );
            });

        return $this->toJson($data);
    }

    public function query(): Relation|Builder|QueryBuilder
    {
        /**
         * @var Account $account
         */
        $account = auth('account')->user();

        $query = $this
            ->getModel()
            ->query()
            ->select([
                'id',
                'logo',
                'name',
                'created_at',
                'status',
            ])
            ->whereHas('accounts', function ($query) use ($account): void {
                $query->where('account_id', $account->getKey());
            })
            ->withCount([
                'jobs' => function ($query): void {
                    $query->where('status', BaseStatusEnum::PUBLISHED);
                },
            ]);

        return $this->applyScopes($query);
    }

    public function columns(): array
    {
        return [
            IdColumn::make(),
            Column::make('logo')
                ->title(__('Logo'))
                ->width(70),
            NameColumn::make()->route('public.account.companies.edit'),
            CreatedAtColumn::make(),
            StatusColumn::make(),
        ];
    }

    public function buttons(): array
    {
        if (! JobBoardHelper::employerCreateMultipleCompanies() && auth('account')->user()->companies()->exists()) {
            return parent::buttons();
        }

        return $this->addCreateButton(route('public.account.companies.create'));
    }
}
