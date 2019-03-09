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
        Route::get('options/towns/{stateId}', ['uses' => '\App\Http\Controllers\OptionController@towns', 'as' => 'OptionController@towns']);
    });


    Route::group(['middleware' => ['auth']], function () {
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
                'middleware' => 'permission:institutions:write'
            ]
        );
        Route::put(
            'institutions/{id}',
            [
                'uses' => '\App\Http\Controllers\InstitutionController@update',
                'as' => 'InstitutionController@update',
                'middleware' => 'permission:institutions:write'
            ]
        );
    });
});

Auth::routes();
