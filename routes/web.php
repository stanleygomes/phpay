<?php

use Illuminate\Support\Facades\Route;

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


// ========================
// APP ROUTES
// ========================

Route::group(['as' => 'app.', 'prefix' => 'app', 'middleware' => 'auth'], function () {
    Route::get('', ['as' => 'dashboard', 'uses' => 'AuthController@dashboard']);

    // Configuracoes do sistema e do usuario
    Route::group(['as' => 'user.', 'middleware' => 'role:ADMIN', 'prefix' => 'user'], function () {
        Route::get('', ['as' => 'index', 'uses' => 'UserController@index']);
        Route::get('create', ['as' => 'create', 'uses' => 'UserController@create']);
        Route::post('store', ['as' => 'store', 'uses' => 'UserController@store']);
        Route::get('edit/{id}', ['as' => 'edit', 'uses' => 'UserController@edit']);
        Route::post('update/{id}', ['as' => 'update', 'uses' => 'UserController@update']);
        Route::get('delete/{id}', ['as' => 'delete', 'uses' => 'UserController@delete']);
    });
});
