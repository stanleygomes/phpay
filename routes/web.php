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
    Route::get('contact', ['as' => 'contact', 'uses' => 'WebsiteController@contact']);
    Route::get('privacy', ['as' => 'privacy', 'uses' => 'WebsiteController@privacy']);
    Route::get('delivery', ['as' => 'delivery', 'uses' => 'WebsiteController@delivery']);
    Route::get('returning', ['as' => 'returning', 'uses' => 'WebsiteController@returning']);

    // Route::group(['as' => 'product.', 'prefix' => ''], function () {
    //     Route::get('products', 'ProductController@home');
    //     Route::get('product', 'ProductController@home')->name('home');
    // });

    // Route::group(['as' => 'cart.', 'prefix' => ''], function () {
    //     Route::get('', 'ProductController@home')->name('cart');
    // });
});

// auth routes
Route::group(['as' => 'auth.', 'prefix' => 'auth'], function () {
    Route::get('login', ['as' => 'login', 'uses' => 'UserController@login']);
    Route::get('register', ['as' => 'register', 'uses' => 'UserController@register']);
    Route::get('password-reset', ['as' => 'passwordReset', 'uses' => 'UserController@passwordReset']);
    Route::get('password-request', ['as' => 'passwordRequest', 'uses' => 'UserController@passwordRequest']);
});

// payment routes
Route::group(['as' => 'mercadoPago.', 'prefix' => 'mercado-pago'], function () {
    Route::get('preview', 'MercadoPagoController@preview')->name('preview');
    Route::get('create-customer-sandbox', 'MercadoPagoController@createCustomerSandbox')->name('createCustomerSandbox');
    Route::get('status', 'MercadoPagoController@status')->name('status');
    Route::get('callback/{type}', 'MercadoPagoController@callback')->name('callback');
});
