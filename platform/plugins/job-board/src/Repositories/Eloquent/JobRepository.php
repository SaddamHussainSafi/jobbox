<?php

namespace Botble\JobBoard\Repositories\Eloquent;

use Botble\JobBoard\Enums\JobStatusEnum;
use Botble\JobBoard\Facades\JobBoardHelper;
use Botble\JobBoard\Models\Job;
use Botble\JobBoard\Repositories\Interfaces\JobInterface;
use Botble\Location\Facades\Location;
use Botble\Support\Repositories\Eloquent\RepositoriesAbstract;

class JobRepository extends RepositoriesAbstract implements JobInterface
{
    public function getJobs(array $filters = [], array $params = [])
    {
        $filters = JobBoardHelper::getJobFilters($filters);

        $filters = array_merge([
            'keyword' => null,
            'city_id' => null,
            'state_id' => null,
            'city' => null,
            'location' => null,
            'job_categories' => [],
            'job_tags' => [],
            'job_types' => [],
            'job_experiences' => [],
            'job_skills' => [],
            'offered_salary_from' => null,
            'offered_salary_to' => null,
            'date_posted' => null,
        ], $filters);

        $with = [
            'slugable',
            'tags',
            'tags.slugable',
            'jobTypes',
            'jobExperience',
            'company',
            'company.slugable',
            'currency',
        ];

        if (is_plugin_active('location')) {
            $with = array_merge($with, array_keys(Location::getSupported(Job::class)));
        }

        $params = array_merge([
            'condition' => [],
            'order_by' => [
                'created_at' => 'DESC',
                'is_featured' => 'DESC',
            ],
            'take' => null,
            'paginate' => [
                'per_page' => 20,
                'current_paged' => 1,
            ],
            'select' => ['jb_jobs.*'],
            'with' => $with,
            'withCount' => ['applicants'],
        ], $params);

        $this->model = $this->originalModel;

        $jobDisplayQueryConditions = JobBoardHelper::getJobDisplayQueryConditions();

        if (JobBoardHelper::isClosedJobAccessible() || JobBoardHelper::isClosedJobListing()) {
            unset($jobDisplayQueryConditions['jb_jobs.status']);
        }

        $this->model = $this->model->select($params['select'])->where($jobDisplayQueryConditions);

        if (JobBoardHelper::isClosedJobAccessible() || JobBoardHelper::isClosedJobListing()) {
            $this->model = $this->model->whereIn('jb_jobs.status', [
                JobStatusEnum::PUBLISHED, JobStatusEnum::CLOSED,
            ]);
        }

        if ($params['take'] === 1) {
            if (! JobBoardHelper::isExpiredJobAccessible()) {
                // @phpstan-ignore-next-line
                $this->model = $this->model->notExpired();
            }

            if (! JobBoardHelper::isClosedJobAccessible()) {
                $this->model = $this->model->notClosed();
            }
        } else {
            if (! JobBoardHelper::isExpiredJobListing()) {
                // @phpstan-ignore-next-line
                $this->model = $this->model->notExpired();
            }

            if (! JobBoardHelper::isClosedJobListing()) {
                $this->model = $this->model->notClosed();
            }
        }

        if (JobBoardHelper::isSalaryHiddenForGuests() && ! auth('account')->check()) {
            unset($filters['offered_salary_from'], $filters['offered_salary_to']);
        }

        $this->model = $this->model->filterJobs($filters);

        if (JobBoardHelper::isClosedJobListing()) {
            $params['order_by']['application_closing_date'] = 'asc';
        }

        if (JobBoardHelper::isExpiredJobListing()) {
            $params['order_by']['expire_date'] = 'desc';
        }

        $params['select'] = []; // Reset select to avoid duplicate columns

        return $this->advancedGet($params);
    }

    public function getFeaturedJobs(int $limit = 10, array $with = [])
    {
        $params = [
            'condition' => ['is_featured' => true],
            'order_by' => [
                'created_at' => 'DESC',
            ],
            'take' => $limit,
        ];

        if ($with) {
            $params['with'] = $with;
        }

        return $this->getJobs([], $params);
    }

    public function getRecentJobs(int $limit = 10, array $with = [])
    {
        $params = [
            'order_by' => [
                'is_featured' => true,
                'created_at' => 'DESC',
            ],
            'take' => $limit,
        ];
        if ($with) {
            $params['with'] = $with;
        }

        return $this->getJobs([], $params);
    }

    public function getPopularJobs(int $limit = 10, array $with = [])
    {
        $params = [
            'order_by' => [
                'views' => 'DESC',
                'is_featured' => true,
                'created_at' => 'DESC',
            ],
            'take' => $limit,
        ];
        if ($with) {
            $params['with'] = $with;
        }

        return $this->getJobs([], $params);
    }

    public function countActiveJobs()
    {
        // @phpstan-ignore-next-line
        $data = $this->model->active();

        return $this->applyBeforeExecuteQuery($data)->count('id');
    }
}
