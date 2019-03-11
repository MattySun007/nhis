<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group(['middleware' => ['query_log']], function () {

    Route::group(['middleware' => ['guest']], function () {
        Route::get('institutions', ['uses' => '\App\Http\Controllers\InstitutionController@index', 'as' => 'InstitutionController@index']);
        Route::post('institutions', ['uses' => '\App\Http\Controllers\InstitutionController@create', 'as' => 'InstitutionController@create']);
        Route::put('institutions/{id}', ['uses' => '\App\Http\Controllers\InstitutionController@update', 'as' => 'InstitutionController@update']);
    });


    Route::group(['middleware' => ['auth']], function () {
        Route::get('options/towns/{stateId}', ['uses' => '\App\Http\Controllers\OptionController@towns', 'as' => 'OptionController@towns']);
        Route::match(['get', 'post'], 'logout', ['uses' => '\App\Http\Controllers\Auth\LoginController@logout', 'as' => 'logout']);
        Route::get('/', ['uses' => '\App\Http\Controllers\HomeController@index', 'as' => 'dashboard']);

        Route::get(
            'institutions',
            [
                'uses' => '\App\Http\Controllers\InstitutionController@index',
                'as' => 'InstitutionController@index',
                'middleware' => 'permission:institutions:read'
            ]
        );
        Route::post(
            'institutions',
            [
                'uses' => '\App\Http\Controllers\InstitutionController@create',
                'as' => 'InstitutionController@create',
                'middleware' => 'permission:institutions:create'
            ]
        );
        Route::put(
            'institutions/{id}',
            [
                'uses' => '\App\Http\Controllers\InstitutionController@update',
                'as' => 'InstitutionController@update',
                'middleware' => 'permission:institutions:update'
            ]
        );

        Route::get(
            'hcps',
            [
                'uses' => '\App\Http\Controllers\HcpController@index',
                'as' => 'HcpController@index',
                'middleware' => 'permission:hcps:read'
            ]
        );
        Route::post(
            'hcps',
            [
                'uses' => '\App\Http\Controllers\HcpController@create',
                'as' => 'HcpController@create',
                'middleware' => 'permission:hcps:create'
            ]
        );
        Route::put(
            'hcps/{id}',
            [
                'uses' => '\App\Http\Controllers\HcpController@update',
                'as' => 'HcpController@update',
                'middleware' => 'permission:hcps:update'
            ]
        );
    });
});

Auth::routes();
