<?php

use Botble\Base\Facades\AdminHelper;
use Botble\Location\Http\Controllers\ExportLocationController;
use Botble\Location\Http\Controllers\ImportLocationController;
use Botble\Theme\Facades\Theme;
use Illuminate\Support\Facades\Route;

Route::group(['namespace' => 'Botble\Location\Http\Controllers'], function (): void {
    AdminHelper::registerRoutes(function (): void {
        Route::group(['prefix' => 'countries', 'as' => 'country.'], function (): void {
            Route::resource('', 'CountryController')->parameters(['' => 'country']);

            Route::get('list', [
                'as' => 'list',
                'uses' => 'CountryController@getList',
                'permission' => 'country.index',
            ]);
        });

        Route::group(['prefix' => 'states', 'as' => 'state.'], function (): void {
            Route::resource('', 'StateController')->parameters(['' => 'state']);

            Route::get('list', [
                'as' => 'list',
                'uses' => 'StateController@getList',
                'permission' => 'state.index',
            ]);
        });

        Route::group(['prefix' => 'cities', 'as' => 'city.'], function (): void {
            Route::resource('', 'CityController')->parameters(['' => 'city']);

            Route::get('list', [
                'as' => 'list',
                'uses' => 'CityController@getList',
                'permission' => 'city.index',
            ]);
        });

        Route::group(['prefix' => 'locations/bulk-import', 'as' => 'location.bulk-import.', 'permission' => 'location.bulk-import.index'], function (): void {
            Route::get('/', [ImportLocationController::class, 'index'])->name('index');
            Route::post('/', [ImportLocationController::class, 'import'])->name('store');
            Route::post('validate', [ImportLocationController::class, 'validateData'])->name('validate');
            Route::post('download-example', [ImportLocationController::class, 'downloadExample'])->name('download-example');
            Route::post('import-location-data', [ImportLocationController::class, 'importLocationData'])->name('import-location-data');
        });

        Route::group(['prefix' => 'locations/export', 'as' => 'location.export.', 'permission' => 'location.export.index'], function (): void {
            Route::get('/', [ExportLocationController::class, 'index'])->name('index');
            Route::post('/', [ExportLocationController::class, 'store'])->name('process');
        });
    });

    Theme::registerRoutes(function (): void {
        Route::get('ajax/states-by-country', 'StateController@ajaxGetStates')
            ->name('ajax.states-by-country');
        Route::get('ajax/cities-by-state', 'CityController@ajaxGetCities')
            ->name('ajax.cities-by-state');
    });
});
