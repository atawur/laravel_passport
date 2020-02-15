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
Route::group(['prefix' => 'v1'],function(){
  Route::group(['middleware' => 'auth:api'], function(){
    Route::post('details', 'API\V1\UserController@user_details');
  });
  Route::post('login', 'API\V1\UserController@user_login');
  Route::post('register', 'API\V1\UserController@user_register');
  
});

