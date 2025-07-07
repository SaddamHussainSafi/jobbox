<?php

use Botble\Base\Facades\BaseHelper;
use Botble\Theme\Facades\Theme;
use Illuminate\Support\Facades\Route;

Route::group(['namespace' => 'Botble\JobBoard\Http\Controllers\Fronts', 'middleware' => ['web', 'core']], function (): void {
    Theme::registerRoutes(function (): void {
        Route::post('review/create', [
            'as' => 'public.reviews.create',
            'uses' => 'ReviewController@store',
            'middleware' => 'account',
        ]);

        Route::get('review/load-more', [
            'as' => 'public.reviews.load-more',
            'uses' => 'ReviewController@loadMore',
        ]);
    });
});

Route::group(['namespace' => 'Botble\JobBoard\Http\Controllers', 'middleware' => ['web', 'core']], function (): void {
    Route::group(['prefix' => BaseHelper::getAdminPrefix() . '/job-board', 'middleware' => 'auth'], function (): void {
        Route::group(['prefix' => 'reviews', 'as' => 'reviews.'], function (): void {
            Route::resource('', 'ReviewController')
                ->only(['index', 'destroy'])
                ->parameters(['' => 'review']);
        });
    });
});
