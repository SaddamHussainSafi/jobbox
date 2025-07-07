<?php

namespace Botble\JobBoard\Tables\Fronts;

use Botble\Base\Facades\BaseHelper;
use Botble\Base\Facades\Html;
use Botble\JobBoard\Models\Account;
use Botble\JobBoard\Models\JobApplication;
use Botble\Table\Abstracts\TableAbstract;
use Botble\Table\Actions\DeleteAction;
use Botble\Table\Actions\EditAction;
use Botble\Table\Columns\Column;
use Botble\Table\Columns\CreatedAtColumn;
use Botble\Table\Columns\FormattedColumn;
use Botble\Table\Columns\IdColumn;
use Botble\Table\Columns\StatusColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Http\JsonResponse;

class ApplicantTable extends TableAbstract
{
    public function setup(): void
    {
        $this
            ->model(JobApplication::class)
            ->addActions([
                EditAction::make()->route('public.account.applicants.edit'),
                DeleteAction::make()->route('public.account.applicants.destroy'),
            ]);
    }

    public function ajax(): JsonResponse
    {
        $data = $this->table
            ->eloquent($this->query())
            ->editColumn('job_id', function (JobApplication $item) {
                if (! $item->job->name) {
                    return '&mdash;';
                }

                return Html::link(
                    $item->job->url,
                    $item->job->name . ' ' . BaseHelper::renderIcon('ti ti-external-link'),
                    ['target' => '_blank'],
                    null,
                    false
                );
            })
            ->editColumn('is_external_apply', function (JobApplication $item) {
                return $item->is_external_apply ? __('External') : __('Internal');
            })
            ->editColumn('company', function (JobApplication $item) {
                return $item->job->company->name ?: '&mdash;';
            });

        $data = $data
            ->filter(function ($query) {
                if ($keyword = $this->request->input('search.value')) {
                    $keyword =  '%' . $keyword . '%';

                    return $query
                        ->whereHas('job', function ($query) use ($keyword) {
                            return $query->where('name', 'LIKE', $keyword);
                        })
                        ->orWhereHas('job.company', function ($query) use ($keyword) {
                            return $query
                                ->where('account_id', auth('account')->id())
                                ->where('name', 'LIKE', $keyword);
                        })
                        ->orWhere('email', 'LIKE', $keyword)
                        ->orWhere('phone', 'LIKE', $keyword);
                }

                return $query;
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
                'job_id',
                'account_id',
                'first_name',
                'last_name',
                'email',
                'phone',
                'created_at',
                'is_external_apply',
                'status',
            ])
            ->whereHas('job.company.accounts', function (Builder $query) use ($account): void {
                $query->where('account_id', $account->getKey());
            })
            ->with([
                'job:id,name,company_id',
                'job.slugable',
                'job.company:id,name',
            ]);

        return $this->applyScopes($query);
    }

    public function columns(): array
    {
        return [
            IdColumn::make(),
            FormattedColumn::make('first_name')
                ->title(trans('plugins/job-board::job-application.tables.name'))
                ->alignLeft()
                ->getValueUsing(function (FormattedColumn $column) {
                    $jobApplication = $column->getItem();

                    if ($jobApplication->is_external_apply) {
                        return $jobApplication->full_name ?: '&mdash;';
                    }

                    if ($jobApplication->account->id && $jobApplication->account->is_public_profile) {
                        return '<a href="' . $jobApplication->account->url . '">' . $jobApplication->account->name . ' ' . BaseHelper::renderIcon('ti ti-external-link') . '</a>';
                    }

                    return $jobApplication->full_name ?: '&mdash;';
                }),
            Column::make('email')
                ->title(trans('plugins/job-board::job-application.tables.email'))
                ->alignLeft(),
            Column::make('phone')
                ->title(trans('plugins/job-board::job-application.tables.phone'))
                ->alignLeft(),
            Column::make('job_id')
                ->title(__('Job Name'))
                ->alignLeft(),
            Column::make('is_external_apply')
                ->title(__('Type')),
            Column::make('company')
                ->title(__('Company'))
                ->orderable(false),
            CreatedAtColumn::make(),
            StatusColumn::make(),
        ];
    }

    public function getDefaultButtons(): array
    {
        return [
            'export',
            'reload',
        ];
    }
}
