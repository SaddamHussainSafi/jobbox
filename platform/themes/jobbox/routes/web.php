<?php

use Botble\Base\Http\Middleware\RequiresJsonRequestMiddleware;
use Botble\Theme\Facades\Theme;
use Illuminate\Support\Facades\Route;

Route::group(['namespace' => 'Theme\Jobbox\Http\Controllers', 'middleware' => ['web', 'core']], function (): void {
    Route::group(apply_filters(BASE_FILTER_GROUP_PUBLIC_ROUTE, []), function (): void {
        Route::group(['as' => 'public.'], function (): void {
            Route::group(['prefix' => 'ajax', 'as' => 'ajax.'], function (): void {
                Route::controller('JobboxController')
                    ->middleware(RequiresJsonRequestMiddleware::class)
                    ->group(function (): void {
                        Route::get('categories', [
                            'as' => 'categories',
                            'uses' => 'ajaxGetJobCategories',
                        ]);

                        Route::get('jobs-by-category/{category_id}', [
                            'as' => 'jobs-by-category',
                            'uses' => 'ajaxGetJobByCategories',
                        ]);

                        Route::get('locations', [
                            'as' => 'locations',
                            'uses' => 'ajaxGetLocation',
                        ]);

                        Route::get('quick-search-jobs', [
                            'as' => 'quick-search-jobs',
                            'uses' => 'ajaxQuickSearchJobs',
                        ]);
                    });
            });
        });
    });
});

Theme::routes();
