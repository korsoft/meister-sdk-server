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
});

Route::group(['middleware' => ['auth:api']], function () {
   
    Route::post('logout', 'Auth\ApiLoginController@logout');
    Route::get('types', 'UserController@types');
    Route::put('/users/{id}/update_my_user','UserController@update_my_user');
    Route::get('/clientgateways/{id}/test_connection','ClientGatewayController@test_connection');
    Route::get('/clientgateways/{id}/execute','ClientGatewayController@execute');
    Route::post('/clientgateways/{id}/execute','ClientGatewayController@execute_changes');
    Route::post('/clientgateways/{id}/execute_endpoint','ClientGatewayController@execute_endpoint');

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

    Route::apiResource('clientuserrole', 'ClientUserRoleController',
        [
            'except' => 
                [
                    'index','show','edit'
                ]
        ]
    );
    
    Route::get('/requestlogs','RequestLogsController@index');
    Route::delete('/requestlogs/all','RequestLogsController@deleteAll');

    Route::get('/clientuserrole/byuserid/{id}','ClientUserRoleController@getByUserId');
    Route::get('/clientuserrole/byclientanduserid/{client_id}/{user_id}','ClientUserRoleController@getByClientAndUserId');
    Route::delete('/clientuserrole/{client_id}/{user_id}','ClientUserRoleController@destroyByClientUserId');
    Route::put('/clientuserrole/{client_id}/{user_id}','ClientUserRoleController@updateByClientAndUserId');
    Route::post('/users/changedefault/{client_id}','UserController@changeDef');


    Route::apiResource('clientgatewayrelation', 'ClientGatewayRelationController',
        [
            'except' => 
                [
                    'index','create','edit'
                ]
        ]
    );

    // Route::get('/clientgatewayrelation/getbyclientid/{id}','ClientGatewayRelationController@getByClientId');
    // Route::get('/clientgatewayrelation/getbygatewaytid/{id}','ClientGatewayRelationController@getByGatewayId');

    Route::get('/clientgatewayrelation/getbygatewaytidandcurrentuser/{gatewayId}','ClientGatewayRelationController@getByGatewayIdAndCurrentUser');
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
