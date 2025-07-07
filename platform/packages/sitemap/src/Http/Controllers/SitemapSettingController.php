<?php

namespace Botble\Sitemap\Http\Controllers;

use Botble\Base\Services\ClearCacheService;
use Botble\Setting\Http\Controllers\SettingController;
use Botble\Sitemap\Forms\Settings\SitemapSettingForm;
use Botble\Sitemap\Http\Requests\SitemapSettingRequest;

class SitemapSettingController extends SettingController
{
    public function edit()
    {
        $this->pageTitle(trans('packages/sitemap::sitemap.settings.title'));

        return SitemapSettingForm::create()->renderForm();
    }

    public function update(SitemapSettingRequest $request)
    {
        // Check if sitemap_items_per_page has changed
        $oldItemsPerPage = setting('sitemap_items_per_page');
        $newItemsPerPage = $request->input('sitemap_items_per_page');

        $response = $this->performUpdate($request->validated());

        // Clear sitemap cache if sitemap_enabled or sitemap_items_per_page has changed
        if ($request->has('sitemap_enabled') || ($oldItemsPerPage != $newItemsPerPage && $newItemsPerPage)) {
            // Use the new centralized method to clear all sitemap caches
            ClearCacheService::make()->clearFrameworkCache();
        }

        return $response->withUpdatedSuccessMessage();
    }
}
