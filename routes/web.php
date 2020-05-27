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
    Route::get('', ['as' => 'home', 'uses' => 'ProductController@home']);
    Route::get('about', ['as' => 'about', 'uses' => 'WebsiteController@about']);
    Route::get('privacy', ['as' => 'privacy', 'uses' => 'WebsiteController@privacy']);
    Route::get('delivery', ['as' => 'delivery', 'uses' => 'WebsiteController@delivery']);
    Route::get('faq', ['as' => 'faq', 'uses' => 'WebsiteController@faq']);
    Route::get('returning', ['as' => 'returning', 'uses' => 'WebsiteController@returning']);

    Route::group(['as' => 'contact.', 'prefix' => 'contact'], function () {
        Route::get('', ['as' => 'form', 'uses' => 'ContactController@contact']);
        Route::post('send', ['as' => 'send', 'uses' => 'ContactController@send']);
    });

    Route::group(['as' => 'product.', 'prefix' => 'product'], function () {
        Route::get('', ['as' => 'send', 'uses' => 'ProductController@home']);
        Route::get('detail/{id}', ['as' => 'send', 'uses' => 'ProductController@show']);
    });
});

// app routes
Route::group(['as' => 'app.', 'prefix' => 'app', 'middleware' => 'auth'], function () {
    Route::get('dashboard', ['as' => 'dashboard', 'uses' => 'AppController@dashboard']);

    Route::group(['as' => 'config.', 'prefix' => 'config'], function () {
        Route::get('', ['as' => 'config', 'uses' => 'AppController@config']);
    });

    Route::group(['as' => 'user.', 'prefix' => 'user'], function () {
        Route::get('', ['as' => 'index', 'uses' => 'UserController@index']);
        Route::post('search', ['as' => 'search', 'uses' => 'UserController@search']);
        Route::get('create', ['as' => 'create', 'uses' => 'UserController@create']);
        Route::post('store', ['as' => 'store', 'uses' => 'UserController@store']);
        Route::get('edit/{id}', ['as' => 'edit', 'uses' => 'UserController@edit']);
        Route::post('update/{id}', ['as' => 'update', 'uses' => 'UserController@update']);
        Route::get('delete/{id}', ['as' => 'delete', 'uses' => 'UserController@delete']);
        Route::get('passwordGenerate/{id}', ['as' => 'passwordGenerate', 'uses' => 'UserController@passwordGenerate']);
        Route::get('passwordChange', ['as' => 'passwordChange', 'uses' => 'UserController@passwordChange']);
        Route::post('passwordChangePost', ['as' => 'passwordChangePost', 'uses' => 'UserController@passwordChangePost']);
        Route::get('accountUpdate', ['as' => 'accountUpdate', 'uses' => 'UserController@accountUpdate']);
        Route::post('accountUpdatePost', ['as' => 'accountUpdatePost', 'uses' => 'UserController@accountUpdatePost']);
    });

    Route::group(['as' => 'address.', 'prefix' => 'address'], function () {
        Route::get('', ['as' => 'index', 'uses' => 'AddressController@index']);
        Route::post('search', ['as' => 'search', 'uses' => 'AddressController@search']);
        Route::get('create', ['as' => 'create', 'uses' => 'AddressController@create']);
        Route::post('store', ['as' => 'store', 'uses' => 'AddressController@store']);
        Route::get('edit/{id}', ['as' => 'edit', 'uses' => 'AddressController@edit']);
        Route::post('update/{id}', ['as' => 'update', 'uses' => 'AddressController@update']);
        Route::get('delete/{id}', ['as' => 'delete', 'uses' => 'AddressController@delete']);
    });

    Route::group(['as' => 'paymentMethodsAvailable.', 'prefix' => 'paymentMethodsAvailable'], function () {
        Route::get('', ['as' => 'index', 'uses' => 'PaymentMethodsAvailableController@index']);
        Route::post('search', ['as' => 'search', 'uses' => 'PaymentMethodsAvailableController@search']);
        Route::get('create', ['as' => 'create', 'uses' => 'PaymentMethodsAvailableController@create']);
        Route::post('store', ['as' => 'store', 'uses' => 'PaymentMethodsAvailableController@store']);
        Route::get('edit/{id}', ['as' => 'edit', 'uses' => 'PaymentMethodsAvailableController@edit']);
        Route::post('update/{id}', ['as' => 'update', 'uses' => 'PaymentMethodsAvailableController@update']);
        Route::get('delete/{id}', ['as' => 'delete', 'uses' => 'PaymentMethodsAvailableController@delete']);
    });

    Route::group(['as' => 'category.', 'prefix' => 'category'], function () {
        Route::get('', ['as' => 'index', 'uses' => 'CategoryController@index']);
        Route::post('search', ['as' => 'search', 'uses' => 'CategoryController@search']);
        Route::get('create', ['as' => 'create', 'uses' => 'CategoryController@create']);
        Route::post('store', ['as' => 'store', 'uses' => 'CategoryController@store']);
        Route::get('edit/{id}', ['as' => 'edit', 'uses' => 'CategoryController@edit']);
        Route::post('update/{id}', ['as' => 'update', 'uses' => 'CategoryController@update']);
        Route::get('delete/{id}', ['as' => 'delete', 'uses' => 'CategoryController@delete']);
    });

    Route::group(['as' => 'featured.', 'prefix' => 'featured'], function () {
        Route::get('', ['as' => 'index', 'uses' => 'FeaturedController@index']);
        Route::post('search', ['as' => 'search', 'uses' => 'FeaturedController@search']);
        Route::get('create', ['as' => 'create', 'uses' => 'FeaturedController@create']);
        Route::post('store', ['as' => 'store', 'uses' => 'FeaturedController@store']);
        Route::get('edit/{id}', ['as' => 'edit', 'uses' => 'FeaturedController@edit']);
        Route::post('update/{id}', ['as' => 'update', 'uses' => 'FeaturedController@update']);
        Route::get('delete/{id}', ['as' => 'delete', 'uses' => 'FeaturedController@delete']);
    });

    Route::group(['as' => 'store.', 'prefix' => 'store'], function () {
        // Route::get('', ['as' => 'index', 'uses' => 'StoreController@index']);
        // Route::post('search', ['as' => 'search', 'uses' => 'StoreController@search']);
        // Route::get('create', ['as' => 'create', 'uses' => 'StoreController@create']);
        // Route::post('store', ['as' => 'store', 'uses' => 'StoreController@store']);
        Route::get('edit/{id}', ['as' => 'edit', 'uses' => 'StoreController@edit']);
        Route::post('update/{id}', ['as' => 'update', 'uses' => 'StoreController@update']);
        // Route::get('delete/{id}', ['as' => 'delete', 'uses' => 'StoreController@delete']);
    });

    Route::group(['as' => 'contact.', 'prefix' => 'contact'], function () {
        Route::get('', ['as' => 'index', 'uses' => 'ContactController@index']);
        Route::post('search', ['as' => 'search', 'uses' => 'ContactController@search']);
        Route::get('delete/{id}', ['as' => 'delete', 'uses' => 'ContactController@delete']);
        Route::get('reply/{id}', ['as' => 'reply', 'uses' => 'ContactController@reply']);
        Route::post('reply/{id}', ['as' => 'replyPost', 'uses' => 'ContactController@replyPost']);
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
