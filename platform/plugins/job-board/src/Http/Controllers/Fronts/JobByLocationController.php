<?php

namespace Botble\JobBoard\Http\Controllers\Fronts;

use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\JobBoard\Facades\JobBoardHelper;
use Botble\Location\Models\City;
use Botble\Location\Models\State;
use Botble\SeoHelper\Facades\SeoHelper;
use Botble\Theme\Facades\Theme;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class JobByLocationController extends PublicController
{
    public function city(string $slug, Request $request): Response|BaseHttpResponse
    {
        $city = City::query()
            ->wherePublished()
            ->where('slug', $slug)
            ->firstOrFail();

        SeoHelper::setTitle(__('Jobs in :location', ['location' => $city->name]));

        Theme::breadcrumb()
            ->add(SeoHelper::getTitle(), route('public.jobs-by-city', $city->slug));

        do_action(BASE_ACTION_PUBLIC_RENDER_SINGLE, CITY_MODULE_SCREEN_NAME, $city);

        $request->merge(['city_id' => $city->getKey()]);
        $requestQuery = JobBoardHelper::getJobFilters($request->input());

        $jobs = $this->jobRepository->getJobs(
            $requestQuery,
            [
                'paginate' => [
                    'per_page' => $request->integer('per_page', 12),
                ],
            ]
        );

        $data = $this->getJobFilterData();

        $data['jobs'] = $jobs;
        $data['ajaxUrl'] = route('public.jobs-by-city', $city->slug);
        $data['actionUrl'] = route('public.jobs-by-city', $city->slug);

        return Theme::scope('job-board.jobs', $data)->render();
    }

    public function state(string $slug, Request $request): Response|BaseHttpResponse
    {
        $state = State::query()
            ->wherePublished()
            ->where('slug', $slug)
            ->firstOrFail();

        SeoHelper::setTitle(__('Jobs in :location', ['location' => $state->name]));

        Theme::breadcrumb()
            ->add(SeoHelper::getTitle(), route('public.jobs-by-state', $state->slug));

        do_action(BASE_ACTION_PUBLIC_RENDER_SINGLE, STATE_MODULE_SCREEN_NAME, $state);

        $request->merge(['state_id' => $state->getKey()]);
        $requestQuery = JobBoardHelper::getJobFilters($request->input());

        $jobs = $this->jobRepository->getJobs(
            $requestQuery,
            [
                'paginate' => [
                    'per_page' => $request->integer('per_page', 12),
                ],
            ]
        );

        $data = $this->getJobFilterData();

        $data['jobs'] = $jobs;
        $data['ajaxUrl'] = route('public.jobs-by-state', $state->slug);
        $data['actionUrl'] = route('public.jobs-by-state', $state->slug);

        return Theme::scope('job-board.jobs', $data)->render();
    }
}
