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

// website routes
Route::group(['as' => 'website.', 'prefix' => ''], function () {
    Route::get('', ['as' => 'home', 'uses' => 'WebsiteController@home']);
    Route::get('about', ['as' => 'about', 'uses' => 'WebsiteController@about']);
    Route::get('privacy', ['as' => 'privacy', 'uses' => 'WebsiteController@privacy']);
    Route::get('delivery', ['as' => 'delivery', 'uses' => 'WebsiteController@delivery']);
    Route::get('faq', ['as' => 'faq', 'uses' => 'WebsiteController@faq']);
    Route::get('returning', ['as' => 'returning', 'uses' => 'WebsiteController@returning']);

    Route::group(['as' => 'contact.', 'prefix' => 'contact'], function () {
        Route::get('', ['as' => 'form', 'uses' => 'ContactController@contact']);
        Route::post('send', ['as' => 'send', 'uses' => 'ContactController@send']);
    });

    // Route::group(['as' => 'product.', 'prefix' => ''], function () {
    //     Route::get('products', 'ProductController@home');
    //     Route::get('product', 'ProductController@home')->name('home');
    // });

    // Route::group(['as' => 'cart.', 'prefix' => ''], function () {
    //     Route::get('', 'ProductController@home')->name('cart');
    // });
});

// app routes
Route::group(['as' => 'app.', 'prefix' => 'app', 'middleware' => 'auth'], function () {
    Route::get('dashboard', ['as' => 'dashboard', 'uses' => 'DashboardController@dashboard']);

    Route::group(['as' => 'user.', 'prefix' => 'user'], function () {
        Route::get('', ['as' => 'index', 'uses' => 'UserController@index']);
        Route::post('search', ['as' => 'search', 'uses' => 'UserController@search']);
        Route::get('create', ['as' => 'create', 'uses' => 'UserController@create']);
        Route::post('store', ['as' => 'store', 'uses' => 'UserController@store']);
        Route::get('edit/{id}', ['as' => 'edit', 'uses' => 'UserController@edit']);
        Route::post('update/{id}', ['as' => 'update', 'uses' => 'UserController@update']);
        Route::get('delete/{id}', ['as' => 'delete', 'uses' => 'UserController@delete']);
        Route::get('passwordGenerate/{id}', ['as' => 'passwordGenerate', 'uses' => 'UserController@passwordGenerate']);
    });
});

// auth routes
Route::group(['as' => 'auth.', 'prefix' => 'auth'], function () {
    Route::get('login', ['as' => 'login', 'uses' => 'UserController@login']);
    Route::post('loginPost', ['as' => 'loginPost', 'uses' => 'UserController@loginPost']);
    Route::get('logout', ['as' => 'logout', 'uses' => 'UserController@logout']);
    Route::get('register', ['as' => 'register', 'uses' => 'UserController@register']);
    Route::post('register', ['as' => 'registerPost', 'uses' => 'UserController@registerPost']);
    Route::get('password-request', ['as' => 'passwordRequest', 'uses' => 'UserController@passwordRequest']);
    Route::post('password-request', ['as' => 'passwordRequestPost', 'uses' => 'UserController@passwordRequestPost']);
    Route::get('password-reset', ['as' => 'passwordReset', 'uses' => 'UserController@passwordReset']);
    Route::post('password-reset', ['as' => 'passwordResetPost', 'uses' => 'UserController@passwordResetPost']);
});

// payment routes
Route::group(['as' => 'mercadoPago.', 'prefix' => 'mercado-pago'], function () {
    Route::get('preview', 'MercadoPagoController@preview')->name('preview');
    Route::get('create-customer-sandbox', 'MercadoPagoController@createCustomerSandbox')->name('createCustomerSandbox');
    Route::get('status', 'MercadoPagoController@status')->name('status');
    Route::get('callback/{type}', 'MercadoPagoController@callback')->name('callback');
});
