<?php

namespace Botble\SocialLogin\Providers;

use Botble\Base\Facades\PanelSectionManager;
use Botble\Base\PanelSections\PanelSectionItem;
use Botble\Base\Supports\ServiceProvider;
use Botble\Base\Traits\LoadAndPublishDataTrait;
use Botble\Setting\PanelSections\SettingOthersPanelSection;
use Botble\SocialLogin\Console\RefreshSocialTokensCommand;
use Botble\SocialLogin\Facades\SocialService;
use Botble\SocialLogin\Services\SocialLoginService;
use Botble\SocialLogin\Supports\SocialService as SocialServiceSupport;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\AliasLoader;

class SocialLoginServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    public function boot(): void
    {
        $this
            ->setNamespace('plugins/social-login')
            ->loadHelpers()
            ->loadAndPublishConfigurations(['permissions', 'general'])
            ->loadAndPublishViews()
            ->loadAndPublishTranslations()
            ->loadMigrations()
            ->loadRoutes()
            ->publishAssets();

        AliasLoader::getInstance()->alias('SocialService', SocialService::class);

        PanelSectionManager::default()->beforeRendering(function (): void {
            PanelSectionManager::registerItem(
                SettingOthersPanelSection::class,
                fn () => PanelSectionItem::make('social-login')
                    ->setTitle(trans('plugins/social-login::social-login.menu'))
                    ->withDescription(trans('plugins/social-login::social-login.description'))
                    ->withIcon('ti ti-social')
                    ->withPriority(100)
                    ->withRoute('social-login.settings')
            );
        });

        $this->app->register(HookServiceProvider::class);

        $this->app->afterResolving(Schedule::class, function (Schedule $schedule): void {
            $schedule->command(RefreshSocialTokensCommand::class)->daily();
        });
    }

    public function register(): void
    {
        $this->app->singleton(SocialServiceSupport::class, function () {
            return new SocialServiceSupport();
        });

        $this->app->singleton(SocialLoginService::class);

        $this->commands([
            RefreshSocialTokensCommand::class,
        ]);
    }
}
