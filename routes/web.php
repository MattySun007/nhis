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
    Route::get('getTP/{user_id}', ['uses' => '\App\Http\Controllers\UserController@getTP', 'as' => 'UserController@getTP']);
    Route::get('options/towns/{stateId}', ['uses' => '\App\Http\Controllers\OptionController@towns', 'as' => 'OptionController@towns']);
    Route::get('options/hcps/{key_name}/{key_id}', ['uses' => '\App\Http\Controllers\OptionController@hcps', 'as' => 'OptionController@hcps']);
    Route::get('codes/institution', ['uses' => '\App\Http\Controllers\InstitutionController@getInstitutionCode', 'as' => 'InstitutionController@getInstitutionCode']);
    Route::get('codes/hcp', ['uses' => '\App\Http\Controllers\HcpController@getHcpCode', 'as' => 'HcpController@getHcpCode']);
    Route::get('codes/hcp/{code}/treatments', ['uses' => '\App\Http\Controllers\TreatmentController@getTreatmentCode', 'as' => 'TreatmentController@getTreatmentCode']);
    Route::get('codes/user', ['uses' => '\App\Http\Controllers\UserController@getUserCode', 'as' => 'UserController@getUserCode']);
    Route::match(['get', 'post'], 'logout', ['uses' => '\App\Http\Controllers\Auth\LoginController@logout', 'as' => 'logout']);
    Route::get('/', ['uses' => '\App\Http\Controllers\HomeController@index', 'as' => 'dashboard']);

    /**
     * institution-hcp
     */
    /*Route::get(
      'institution-hcp-delete',
      [
        'uses' => '\App\Http\Controllers\InstitutionController@deleteHcp',
        'as' => 'InstitutionControler@deleteHcp',
        'middleware' => 'permission:institution-hcp:create'
      ]
    );*/
    Route::get(
      'institution-hcp/{id?}',
      [
        'uses' => '\App\Http\Controllers\InstitutionController@listHcp',
        'as' => 'InstitutionControler@listHcp',
        'middleware' => 'permission:institution-hcp:read'
      ]
    );
    Route::post(
      'institution-hcp',
      [
        'uses' => '\App\Http\Controllers\InstitutionController@addHcp',
        'as' => 'InstitutionControler@addHcp',
        'middleware' => 'permission:institution-hcp:create'
      ]
    );
    Route::delete(
      'institution-hcp/{id}',
      [
        'uses' => '\App\Http\Controllers\InstitutionController@deleteHcp',
        'as' => 'InstitutionControler@deleteHcp',
        'middleware' => 'permission:institution-hcp:delete'
      ]
    );


    /**
     * users
     */
    Route::get(
      'agency-users',
      [
        'uses' => '\App\Http\Controllers\UserController@agencyUsers',
        'as' => 'UserController@agencyUsers',
        'middleware' => 'permission:agency-users:read'
      ]
    );
    Route::post(
      'agency-users',
      [
        'uses' => '\App\Http\Controllers\UserController@createAgencyUser',
        'as' => 'UserController@createAgencyUser',
        'middleware' => 'permission:agency-users:create'
      ]
    );
    Route::put(
      'agency-users/{id}',
      [
        'uses' => '\App\Http\Controllers\UserController@updateAgencyUser',
        'as' => 'UserController@updateAgencyUser',
        'middleware' => 'permission:agency-users:update'
      ]
    );
    Route::delete(
      'agency-users/{id}',
      [
        'uses' => '\App\Http\Controllers\UserController@deleteAgencyUser',
        'as' => 'UserController@deleteAgencyUser',
        'middleware' => 'permission:agency-users:delete'
      ]
    );

    /**
     * contributors
     */
    Route::get(
      'individual-contributors',
      [
        'uses' => '\App\Http\Controllers\UserController@contributors',
        'as' => 'UserController@contributors',
        'middleware' => 'permission:individual-contributors:read'
      ]
    );
    Route::post(
      'individual-contributors',
      [
        'uses' => '\App\Http\Controllers\UserController@createIndividualContributor',
        'as' => 'UserController@createIndividualContributor',
        'middleware' => 'permission:individual-contributors:create'
      ]
    );
    Route::put(
      'individual-contributors/{id}',
      [
        'uses' => '\App\Http\Controllers\UserController@updateIndividualContributor',
        'as' => 'UserController@updateIndividualContributor',
        'middleware' => 'permission:individual-contributors:update'
      ]
    );
    Route::delete(
      'individual-contributors/{id}',
      [
        'uses' => '\App\Http\Controllers\UserController@deleteIndividualContributor',
        'as' => 'UserController@deleteIndividualContributor',
        'middleware' => 'permission:individual-contributors:delete'
      ]
    );

    /**
     * institutions
     */
    Route::get(
      'institutions',
      [
        'uses' => '\App\Http\Controllers\InstitutionController@index',
        'as' => 'InstitutionController@index',
        'middleware' => 'permission:institutions:read'
      ]
    );
    Route::get(
      'institutions/search/{str}',
      [
        'uses' => '\App\Http\Controllers\InstitutionController@search',
        'as' => 'InstitutionController@search',
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

    /**
     * hcps
     */
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

    /**
     * hcp users
     */
    Route::get(
      'hcps/{id}/users',
      [
        'uses' => '\App\Http\Controllers\UserController@hcpUsers',
        'as' => 'UserController@hcpUsers',
        'middleware' => 'permission:hcp-users:read'
      ]
    );
    Route::post(
      'hcp-users',
      [
        'uses' => '\App\Http\Controllers\UserController@createHcpUser',
        'as' => 'UserController@createHcpUser',
        'middleware' => 'permission:hcp-users:create'
      ]
    );
    Route::put(
      'hcp-users',
      [
        'uses' => '\App\Http\Controllers\UserController@updateHcpUser',
        'as' => 'UserController@updateHcpUser',
        'middleware' => 'permission:hcp-users:update'
      ]
    );
    Route::delete(
      'hcp-users/{id}',
      [
        'uses' => '\App\Http\Controllers\UserController@deleteHcpUser',
        'as' => 'UserController@deleteHcpUser',
        'middleware' => 'permission:hcp-users:delete'
      ]
    );
    /**
     * institution users
     */
    Route::get(
      'institutions/{id}/users',
      [
        'uses' => '\App\Http\Controllers\UserController@institutionUsers',
        'as' => 'UserController@institutionUsers',
        'middleware' => 'permission:institution-users:read'
      ]
    );
    Route::post(
      'institution-users',
      [
        'uses' => '\App\Http\Controllers\UserController@createInstitutionUser',
        'as' => 'UserController@createInstitutionUser',
        'middleware' => 'permission:institution-users:create'
      ]
    );
    Route::put(
      'institution-users',
      [
        'uses' => '\App\Http\Controllers\UserController@updateInstitutionUser',
        'as' => 'UserController@updateInstitutionUser',
        'middleware' => 'permission:institution-users:update'
      ]
    );
    Route::delete(
      'institution-users/{id}',
      [
        'uses' => '\App\Http\Controllers\UserController@deleteInstitutionUser',
        'as' => 'UserController@deleteInstitutionUser',
        'middleware' => 'permission:institution-users:delete'
      ]
    );

    /**
     * treatments
     */
    Route::get(
      'hcp/{id}/treatments',
      [
        'uses' => '\App\Http\Controllers\TreatmentController@index',
        'as' => 'TreatmentController@index',
        'middleware' => 'permission:treatments:read'
      ]
    );
    Route::get(
      'hcp/{id}/treatments/verify/{str}',
      [
        'uses' => '\App\Http\Controllers\TreatmentController@verify',
        'as' => 'TreatmentController@verify',
        'middleware' => 'permission:treatments:read'
      ]
    );
    Route::post(
      'treatments',
      [
        'uses' => '\App\Http\Controllers\TreatmentController@create',
        'as' => 'TreatmentController@create',
        'middleware' => 'permission:treatments:create'
      ]
    );

    Route::put(
      'treatments/{id}',
      [
        'uses' => '\App\Http\Controllers\TreatmentController@update',
        'as' => 'TreatmentController@update',
        'middleware' => 'permission:treatments:update'
      ]
    );
    Route::delete(
      'treatments/{id}',
      [
        'uses' => '\App\Http\Controllers\TreatmentController@deleteTreatment',
        'as' => 'TreatmentController@deleteTreatment',
        'middleware' => 'permission:treatments:delete'
      ]
    );

    /**
     * claims
     */
    Route::get(
      'claims/unpaid',
      [
        'uses' => '\App\Http\Controllers\ClaimController@index',
        'as' => 'ClaimController@index',
        'middleware' => 'permission:claims:read,claims:manage'
      ]
    );

    Route::get(
      'claims/paid',
      [
        'uses' => '\App\Http\Controllers\ClaimController@paidClaims',
        'as' => 'ClaimController@paidClaims',
        'middleware' => 'permission:claims:read,claims:manage'
      ]
    );

    Route::get(
      'state/{id}/hcps/claims/unpaid',
      [
        'uses' => '\App\Http\Controllers\ClaimController@stateHcpsClaimsUnpaid',
        'as' => 'ClaimController@stateHcpsClaimsUnpaid',
        'middleware' => 'permission:claims:read,claims:manage'
      ]
    );
    Route::get(
      'state/{id}/hcps/claims/paid',
      [
        'uses' => '\App\Http\Controllers\ClaimController@stateHcpsClaimsPaid',
        'as' => 'ClaimController@stateHcpsClaimsPaid',
        'middleware' => 'permission:claims:read,claims:manage'
      ]
    );

    Route::get(
      'hcp/{id}/treatments/claims/unpaid',
      [
        'uses' => '\App\Http\Controllers\ClaimController@hcpTreatmentsClaimsUnpaid',
        'as' => 'ClaimController@hcpTreatmentsClaimsUnpaid',
        'middleware' => 'permission:claims:read,claims:manage'
      ]
    );
    Route::get(
      'hcp/{id}/treatments/claims/paid',
      [
        'uses' => '\App\Http\Controllers\ClaimController@hcpTreatmentsClaimsPaid',
        'as' => 'ClaimController@hcpTreatmentsClaimsPaid',
        'middleware' => 'permission:claims:read,claims:manage'
      ]
    );



  });
});

Auth::routes();
