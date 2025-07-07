<?php

namespace Botble\Team\Providers;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Facades\DashboardMenu;
use Botble\Base\Traits\LoadAndPublishDataTrait;
use Botble\LanguageAdvanced\Supports\LanguageAdvancedManager;
use Botble\SeoHelper\Facades\SeoHelper;
use Botble\SeoHelper\SeoOpenGraph;
use Botble\Slug\Facades\SlugHelper;
use Botble\Slug\Models\Slug;
use Botble\Team\Models\Team;
use Botble\Theme\Facades\Theme;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;

class TeamServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    public function boot(): void
    {
        SlugHelper::registerModule(Team::class, 'Teams');
        SlugHelper::setPrefix(Team::class, 'teams', true);

        $this
            ->setNamespace('plugins/team')
            ->loadHelpers()
            ->loadAndPublishConfigurations(['permissions'])
            ->loadMigrations()
            ->loadAndPublishTranslations()
            ->loadAndPublishViews()
            ->loadRoutes();

        if (defined('LANGUAGE_MODULE_SCREEN_NAME') && defined('LANGUAGE_ADVANCED_MODULE_SCREEN_NAME')) {
            LanguageAdvancedManager::registerModule(Team::class, [
                'name',
                'title',
                'location',
            ]);
        }

        DashboardMenu::default()->beforeRetrieving(function (): void {
            DashboardMenu::make()
                ->registerItem([
                    'id' => 'cms-plugins-team',
                    'priority' => 5,
                    'name' => 'plugins/team::team.name',
                    'icon' => 'ti ti-users',
                    'route' => 'team.index',
                ]);
        });

        $this->app->booted(function (): void {
            add_filter(BASE_FILTER_PUBLIC_SINGLE_DATA, function (Slug|array $slug): Slug|array {
                if (! $slug instanceof Slug || $slug->reference_type != Team::class) {
                    return $slug;
                }

                $condition = [
                    'id' => $slug->reference_id,
                    'status' => BaseStatusEnum::PUBLISHED,
                ];

                if (Auth::guard()->check() && request()->input('preview')) {
                    Arr::forget($condition, 'status');
                }

                $team = Team::query()
                    ->where($condition)
                    ->with(['slugable'])
                    ->firstOrFail();

                SeoHelper::setTitle($team->name)
                    ->setDescription($team->description);

                SeoHelper::setSeoOpenGraph(
                    (new SeoOpenGraph())
                        ->setDescription($team->description)
                        ->setUrl($team->url)
                        ->setTitle($team->name)
                        ->setType('article')
                );

                Theme::breadcrumb()->add($team->name);

                return [
                    'view' => 'teams.team',
                    'default_view' => 'plugins/team::themes.team',
                    'data' => compact('team'),
                    'slug' => $team->slug,
                ];
            }, 2);
        });
    }
}
