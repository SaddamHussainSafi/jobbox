<?php

use Botble\JobBoard\Models\Account;
use Botble\JobBoard\Models\Category;
use Botble\JobBoard\Models\Company;
use Botble\JobBoard\Models\Job;
use Botble\JobBoard\Models\Tag;
use Botble\Location\Models\City;
use Botble\Location\Models\State;
use Botble\Slug\Facades\SlugHelper;
use Botble\Theme\Facades\Theme;
use Illuminate\Support\Facades\Route;

Route::group(['namespace' => 'Botble\JobBoard\Http\Controllers\Fronts', 'middleware' => ['web', 'core']], function (): void {
    Route::post('jobs/apply/{id?}', [
        'as' => 'public.job.apply',
        'uses' => 'PublicController@postApplyJob',
    ]);

    Route::get('currency/switch/{code?}', [
        'as' => 'public.change-currency',
        'uses' => 'PublicController@changeCurrency',
    ]);

    Theme::registerRoutes(function (): void {
        Route::get('ajax/jobs', [
            'as' => 'public.ajax.jobs',
            'uses' => 'PublicController@getJobs',
        ]);

        Route::get('ajax/candidates', [
            'as' => 'public.ajax.candidates',
            'uses' => 'PublicController@getCandidates',
        ]);

        Route::get('ajax/companies', [
            'as' => 'public.ajax.companies',
            'uses' => 'PublicController@getCompanies',
        ]);

        Route::get(SlugHelper::getPrefix(Job::class, 'jobs') . '/{slug}', [
            'as' => 'public.job',
            'uses' => 'PublicController@getJob',
        ]);

        Route::get(SlugHelper::getPrefix(Category::class, 'job-categories') . '/{slug}', [
            'as' => 'public.job-category',
            'uses' => 'PublicController@getJobCategory',
        ]);

        Route::get(SlugHelper::getPrefix(Tag::class, 'job-tags') . '/{slug}', [
            'as' => 'public.job-tag',
            'uses' => 'PublicController@getJobTag',
        ]);

        Route::get(SlugHelper::getPrefix(Company::class, 'companies') . '/{slug}', [
            'as' => 'public.company',
            'uses' => 'PublicController@getCompany',
        ]);

        Route::get(SlugHelper::getPrefix(Account::class, 'candidates') . '/{slug}', [
            'as' => 'public.candidate',
            'uses' => 'PublicController@getCandidate',
        ]);

        Route::get(
            sprintf('%s/%s/{slug?}', SlugHelper::getPrefix(Job::class, 'jobs'), SlugHelper::getPrefix(City::class, 'city')),
            'JobByLocationController@city'
        )->name('public.jobs-by-city');

        Route::get(
            sprintf('%s/%s/{slug?}', SlugHelper::getPrefix(Job::class, 'jobs'), SlugHelper::getPrefix(State::class, 'state')),
            'JobByLocationController@state'
        )->name('public.jobs-by-state');
    });

    Route::group(['prefix' => 'payments'], function (): void {
        Route::post('checkout', 'CheckoutController@postCheckout')->name('payments.checkout');
    });
});

Route::group(['namespace' => 'Botble\JobBoard\Http\Controllers', 'middleware' => ['web', 'core']], function (): void {
    Route::get('download-cv/{account}', [
        'as' => 'public.candidate.download-cv',
        'uses' => 'AccountDownloadCvController@__invoke',
    ]);
});
