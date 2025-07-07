<?php

namespace Botble\JobBoard\Listeners;

use Botble\JobBoard\Models\Category;
use Botble\JobBoard\Models\Company;
use Botble\JobBoard\Models\Job;
use Botble\JobBoard\Models\Tag;
use Botble\Location\Models\City;
use Botble\Location\Models\State;
use Botble\Theme\Events\RenderingSiteMapEvent;
use Botble\Theme\Facades\SiteMapManager;
use Illuminate\Support\Arr;

class RenderingSiteMapListener
{
    public function handle(RenderingSiteMapEvent $event): void
    {
        if ($key = $event->key) {
            switch ($key) {
                case 'job-categories':

                    $categories = Category::query()
                        ->wherePublished()
                        ->latest('updated_at')
                        ->with(['slugable'])
                        ->get();

                    foreach ($categories as $category) {
                        SiteMapManager::add($category->url, $category->updated_at, '0.8');
                    }

                    break;

                case 'job-tags':

                    $tags = Tag::query()
                        ->wherePublished()
                        ->latest('updated_at')
                        ->with(['slugable'])
                        ->get();

                    foreach ($tags as $tag) {
                        SiteMapManager::add($tag->url, $tag->updated_at, '0.4');
                    }

                    break;

                case 'jobs-city':

                    $items = City::query()
                        ->wherePublished()
                        ->latest('updated_at')
                        ->get();

                    foreach ($items as $item) {
                        SiteMapManager::add(route('public.jobs-by-city', $item->slug), $item->updated_at, '0.8');
                    }

                    break;

                case 'jobs-state':

                    $items = State::query()
                        ->wherePublished()
                        ->latest('updated_at')
                        ->get();

                    foreach ($items as $item) {
                        SiteMapManager::add(route('public.jobs-by-state', $item->slug), $item->updated_at, '0.8');
                    }

                    break;

                case 'job-companies':
                    $items = Company::query()
                        ->wherePublished()
                        ->latest('updated_at')
                        ->get();

                    foreach ($items as $item) {
                        SiteMapManager::add($item->url, $item->updated_at, '0.8');
                    }

                    break;
            }

            if (preg_match('/^jobs-((?:19|20|21|22)\d{2})-(0?[1-9]|1[012])$/', $key, $matches)) {
                if (($year = Arr::get($matches, 1)) && ($month = Arr::get($matches, 2))) {
                    // @phpstan-ignore-next-line
                    $jobs = Job::query()
                        ->active()
                        ->whereYear('updated_at', $year)
                        ->whereMonth('updated_at', $month)
                        ->latest('updated_at')
                        ->select(['id', 'name', 'updated_at'])
                        ->with(['slugable'])
                        ->get();

                    foreach ($jobs as $job) {
                        if (! $job->slugable) {
                            continue;
                        }

                        SiteMapManager::add($job->url, $job->updated_at, '0.8');
                    }
                }
            }

            return;
        }

        // @phpstan-ignore-next-line
        $jobs = Job::query()
            ->selectRaw('YEAR(updated_at) as updated_year, MONTH(updated_at) as updated_month, MAX(updated_at) as updated_at')
            ->active()
            ->groupBy('updated_year', 'updated_month')
            ->orderBy('updated_year', 'desc')
            ->orderBy('updated_month', 'desc')
            ->get();

        foreach ($jobs as $job) {
            $key = sprintf('jobs-%s-%s', $job->updated_year, str_pad($job->updated_month, 2, '0', STR_PAD_LEFT));
            SiteMapManager::addSitemap(SiteMapManager::route($key), $job->updated_at);
        }

        $candidateLastUpdated = Company::query()
            ->wherePublished()
            ->latest('updated_at')
            ->value('updated_at');

        SiteMapManager::addSitemap(SiteMapManager::route('job-companies'), $candidateLastUpdated);

        $categoryLastUpdated = Category::query()
            ->wherePublished()
            ->latest('updated_at')
            ->value('updated_at');

        SiteMapManager::addSitemap(SiteMapManager::route('job-categories'), $categoryLastUpdated);

        $tagLastUpdated = Tag::query()
            ->wherePublished()
            ->latest('updated_at')
            ->value('updated_at');

        SiteMapManager::addSitemap(SiteMapManager::route('job-tags'), $tagLastUpdated);
        $cityLastUpdated = City::query()
            ->wherePublished()
            ->latest('updated_at')
            ->value('updated_at');
        $stateLastUpdated = State::query()
            ->wherePublished()
            ->latest('updated_at')
            ->value('updated_at');

        SiteMapManager::addSitemap(SiteMapManager::route('jobs-city'), $cityLastUpdated);
        SiteMapManager::addSitemap(SiteMapManager::route('jobs-state'), $stateLastUpdated);
    }
}
