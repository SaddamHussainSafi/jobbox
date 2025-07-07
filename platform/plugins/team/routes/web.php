<?php

use Botble\Base\Facades\AdminHelper;
use Illuminate\Support\Facades\Route;

AdminHelper::registerRoutes(function (): void {
    Route::group(['namespace' => 'Botble\Team\Http\Controllers'], function (): void {
        Route::group(['prefix' => 'teams', 'as' => 'team.'], function (): void {
            Route::resource('', 'TeamController')->parameters(['' => 'team']);
        });
    });
});
