<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return "ss";
// });

Route::group(['namespace' => 'Auth'], function () {
    Route::post('login', 'ApiLoginController@issueToken');
    Route::post('logout', 'ApiLoginController@logout');
});

Route::group(['middleware' => ['auth:api']], function () {
   
    
    Route::get('types', 'UserController@types');
    Route::put('/users/{id}/update_my_user','UserController@update_my_user');
    Route::get('/clientgateways/{id}/test_connection','ClientGatewayController@test_connection');

    Route::apiResource('clients', 'ClientController',  
        [
            'except' => 
                [
                    'create','edit'
                ]
        ]
    );

    Route::apiResource('users', 'UserController',  
        [
            'except' => 
                [
                    'create','edit'
                ]
        ]
    );

    Route::apiResource('clientgateways', 'ClientGatewayController',  
        [
            'except' => 
                [
                    'create','edit'
                ]
        ]
    );

});





/*Route::get('/claims', [
    'uses' => 'MiddleRestController@claims'
]);

Route::get('/claims/details', [
    'uses' => 'MiddleRestController@details'
]);

Route::get('/claims/detail/{claimno}', [
    'uses' => 'MiddleRestController@detail'
]);


Route::get('/claims/simulate', [
    'uses' => 'MiddleRestController@simulate'
]);

Route::get('/claims/approve', [
    'uses' => 'MiddleRestController@approve'
]);

Route::get('/reports', [
    'uses' => 'MiddleRestController@reports'
]);

Route::get('/list_reports', [
    'uses' => 'MiddleRestController@list_reports'
]);


Route::get('/reports/schedule/{reportName}', [
    'uses' => 'MiddleRestController@schelule_report'
]);


Route::get('/reports/detail/{pki}', [
    'uses' => 'MiddleRestController@reports_details'
]);*/
