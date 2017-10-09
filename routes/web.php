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

Route::get('/', function () {
    return view('welcome');
});

Route::group(['middleware' => ['api'],'prefix' => 'api'], function () {
    Route::post('register', 'APIController@register');
    Route::post('login', 'APIController@login');
    Route::group(['middleware' => 'jwt-auth'], function () {
        Route::post('get_user_details', 'APIController@get_user_details');
    });

    Route::resource('post', 'PostController');
    Route::resource('user', 'UserController');
    Route::resource('post', 'PostController');
    Route::resource('comments', 'ComentsController');
    Route::resource('transaction', 'TransacaoController');
    Route::resource('notification', 'NotificacaoController');
});
