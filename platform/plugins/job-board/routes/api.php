<?php

use Illuminate\Support\Facades\Route;

Route::group([
    'middleware' => 'api',
    'prefix' => 'api/v1',
    'namespace' => 'Botble\JobBoard\Http\Controllers\API',
], function (): void {
    Route::get('jobs', 'JobController@index');
});
