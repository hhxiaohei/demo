<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/
Route::auth();


Route::get('/', function () {
    return redirect()->route('admin.admins.index');
});




Route::group(['middlewareGroups'=>['web']],function (){
    Route::get('/home', 'HomeController@index');
    Route::get('/test', 'HomeController@test');

    Route::controllers([
        'admin/auth' => config('forone.auth.administrator_auth_controller', 'Auth\AuthController'),
    ]);
    Route::get('/admin/qiniu/upload-token', ['as'=>'admin.qiniu.upload-token', 'uses'=>'Upload\QiniuController@token']);

});


//admin
Route::group(['prefix' => 'admin','middlewareGroups'=>'web', 'middleware' => ['auth', 'permission:admin','log.add']], function () {

    Route::resource('operations', 'OperationLogsController');

    Route::group(['namespace' => '\Permissions'], function () {
        Route::resource('roles', 'RolesController');
        Route::resource('permissions', 'PermissionsController');
        Route::resource('admins', 'AdminsController');
        Route::post('roles/assign-permission', ['as' => 'admin.roles.assign-permission', 'uses' => 'RolesController@assignPermission']);
        Route::post('admins/assign-role', ['as' => 'admin.roles.assign-role', 'uses' => 'AdminsController@assignRole']);
    });

});

//admin
Route::group(['prefix' => 'admin','middlewareGroups'=>'web', 'middleware' => ['auth', 'permission:admin','log.add']], function () {
    Route::get('newdata/export' , ['as' => 'admin.newdata.export', 'uses' => 'NewData\NewDataController@export']);
    Route::group(['namespace'=>'User'],function(){
        Route::resource('user', 'UserController');
    });
    Route::group(['namespace'=>'Form'],function(){
        Route::resource('form', 'FormController');
    });
    Route::group(['namespace'=>'NewData'],function(){
        Route::resource('newdata', 'NewDataController');
    });
    Route::group(['namespace'=>'Img'],function(){
        Route::resource('imgs', 'ImgController');
    });
    Route::group(['namespace'=>'File'],function(){
        Route::resource('files', 'FileController');
    });
    Route::resource('operations', 'OperationLogsController');


});
