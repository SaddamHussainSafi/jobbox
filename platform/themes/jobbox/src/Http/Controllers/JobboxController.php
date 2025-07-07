<?php

namespace Theme\Jobbox\Http\Controllers;

use Botble\Base\Facades\BaseHelper;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\JobBoard\Facades\JobBoardHelper;
use Botble\JobBoard\Models\Job;
use Botble\JobBoard\Repositories\Interfaces\CategoryInterface;
use Botble\JobBoard\Repositories\Interfaces\JobInterface;
use Botble\Location\Facades\Location;
use Botble\Location\Repositories\Interfaces\CityInterface;
use Botble\Location\Repositories\Interfaces\CountryInterface;
use Botble\Location\Repositories\Interfaces\StateInterface;
use Botble\Theme\Facades\Theme;
use Botble\Theme\Http\Controllers\PublicController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Theme\Jobbox\Http\Resources\CategoryResource;
use Theme\Jobbox\Http\Resources\LocationResource;

class JobboxController extends PublicController
{
    public function ajaxGetLocation(
        Request $request,
        CityInterface $cityRepository,
        StateInterface $stateRepository,
        CountryInterface $countryRepository,
        BaseHttpResponse $response
    ) {
        $request->validate([
            'k' => ['nullable', 'string'],
            'type' => ['required', 'string', 'in:state,city'],
        ]);

        $keyword = BaseHelper::stringify($request->query('k'));
        $limit = (int) theme_option('limit_results_on_job_location_filter', 10) ?: 1000;
        if ($request->input('type', 'state') === 'state') {
            $locations = $stateRepository->filters($keyword, $limit);

            $jobsLocationAvailable = $stateRepository->getModel()::query()
                ->wherePublished()
                ->whereExists(function ($query): void {
                    $query->select('id')
                        ->from(with(new Job())->getTable())
                        ->whereColumn('state_id', 'states.id');
                })
                ->pluck('id')
                ->toArray();
        } else {
            $locations = $cityRepository->filters($keyword, $limit);
            $locations->loadMissing('state');

            $jobsLocationAvailable = $cityRepository->getModel()::query()
                ->whereExists(function ($query): void {
                    $query->select('id')
                        ->from(with(new Job())->getTable())
                        ->whereColumn('city_id', 'cities.id');
                })
                ->wherePublished()
                ->pluck('id')
                ->toArray();
        }

        $countryIds = $countryRepository->getModel()::query()
            ->wherePublished()
            ->whereExists(function ($query): void {
                $query->select('id')
                    ->from(with(new Job())->getTable())
                    ->whereColumn('country_id', 'countries.id')
                    ->whereNull('city_id')
                    ->whereNull('state_id');
            })
            ->where('name', 'like', '%' . $keyword . '%')
            ->pluck('id')
            ->toArray();

        $locations = $locations->whereIn('id', array_values(array_unique($jobsLocationAvailable)));

        $locations = $locations->merge($countryRepository->getByWhereIn('id', $countryIds))->sort();

        return $response->setData([LocationResource::collection($locations), 'total' => $locations->count()]);
    }

    public function ajaxGetJobCategories(
        Request $request,
        BaseHttpResponse $response,
        CategoryInterface $categoryRepository
    ) {
        $keyword = BaseHelper::stringify($request->query('k'));

        $condition = [
            'with' => ['slugable'],
            'paginate' => [
                'per_page' => 10,
                'current_paged' => $request->integer('page', 1),
            ],
        ];

        if ($keyword) {
            $condition['condition'] = ['keyword' => ['name', 'like', '%' . $keyword . '%']];
        }

        $categories = $categoryRepository->advancedGet($condition);

        $total = $categories->total();

        return $response->setData([CategoryResource::collection($categories), 'total' => $total]);
    }

    public function ajaxGetJobByCategories(
        $categoryId,
        Request $request,
        BaseHttpResponse $response,
        JobInterface $jobRepository
    ) {
        $validator = Validator::make($request->input(), [
            'style' => ['nullable', 'string'],
        ]);

        if ($validator->fails()) {
            return $response->setNextUrl(route('public.index'));
        }

        $with = [
            'company',
            'slugable',
            'metadata',
        ];

        if (is_plugin_active('location')) {
            $with = array_merge($with, array_keys(Location::getSupported(Job::class)));
        }

        $view = Theme::getThemeNamespace('views.job-board.partials.job-of-the-day-items');

        $style = BaseHelper::stringify($request->input('style')) ?: 'style-1';

        $requestQuery = JobBoardHelper::getJobFilters($request->input());

        $jobs = $jobRepository
            ->getJobs(
                array_merge($requestQuery, [
                    'job_categories' => [$categoryId],
                ]),
                [
                    'take' => $request->integer('limit') ?: 8,
                    'with' => $with,
                ]
            );

        return $response
            ->setData(view($view, compact('jobs', 'style'))->render());
    }

    public function ajaxQuickSearchJobs(Request $request, BaseHttpResponse $response): BaseHttpResponse
    {
        $validated = $request->validate([
            'job_categories' => ['nullable', 'string'],
            'location' => ['nullable', 'string'],
            'keyword' => ['nullable', 'string'],
        ]);

        $jobs = app(JobInterface::class)->getJobs($validated, [
            'take' => 10,
        ]);

        if ($jobs->isEmpty()) {
            return $response->setError();
        }

        return $response->setData([
            'html' => Theme::partial('job-quick-search', compact('jobs')),
        ]);
    }
}
