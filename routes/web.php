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
    Route::get('about', ['as' => 'about', 'uses' => 'AppController@pageAbout']);
    Route::get('privacy', ['as' => 'privacy', 'uses' => 'AppController@pagePrivacy']);
    Route::get('delivery', ['as' => 'delivery', 'uses' => 'AppController@pageDelivery']);
    Route::get('faq', ['as' => 'faq', 'uses' => 'AppController@pageFaq']);
    Route::get('returning', ['as' => 'returning', 'uses' => 'AppController@pageReturning']);

    Route::group(['as' => 'contact.', 'prefix' => 'contact'], function () {
        Route::get('', ['as' => 'form', 'uses' => 'ContactController@contact']);
        Route::post('send', ['as' => 'send', 'uses' => 'ContactController@send']);
    });

    Route::group(['as' => 'product.', 'prefix' => 'product'], function () {
        Route::get('', ['as' => 'home', 'uses' => 'ProductController@home']);
        Route::get('search', ['as' => 'search', 'uses' => 'ProductController@webSearch']);
        Route::get('category/{id}/{slug?}', ['as' => 'byCategory', 'uses' => 'ProductController@webSearch']);
        Route::get('show/{id}/{slug?}', ['as' => 'show', 'uses' => 'ProductController@show']);
    });

    Route::group(['as' => 'cart.', 'prefix' => 'cart'], function () {
        Route::group(['as' => '', 'prefix' => 'user'], function () {
            Route::get('edit', ['as' => 'userEdit', 'uses' => 'UserController@cartUserEdit']);
            Route::post('update', ['as' => 'userUpdate', 'uses' => 'UserController@cartUserUpdate']);
        });

        Route::group(['as' => '', 'prefix' => 'address'], function () {
            Route::get('address', ['as' => 'address', 'uses' => 'AddressController@cartAddressIndex']);
            Route::get('create', ['as' => 'addressCreate', 'uses' => 'AddressController@cartAddressCreate']);
            Route::post('addressStore', ['as' => 'addressStore', 'uses' => 'AddressController@cartAddressStore']);
            Route::get('edit/{id}', ['as' => 'addressEdit', 'uses' => 'AddressController@cartAddressEdit']);
            Route::post('addressUpdate/{id}', ['as' => 'addressUpdate', 'uses' => 'AddressController@cartAddressUpdate']);
            Route::get('confirm/{id}', ['as' => 'addressConfirm', 'uses' => 'CartController@addressConfirm']);
        });

        Route::group(['as' => '', 'prefix' => 'product'], function () {
            Route::get('add/{id}', ['as' => 'addProduct', 'uses' => 'CartController@addProduct']);
            Route::get('update/{id}', ['as' => 'updateProduct', 'uses' => 'CartController@updateProduct']);
            Route::get('delete/{id}', ['as' => 'deleteProduct', 'uses' => 'CartController@deleteProduct']);
        });

        Route::post('payment', ['as' => 'payment', 'uses' => 'CartController@payment']);
        Route::get('callback/{status}', ['as' => 'callback', 'uses' => 'CartController@callback']);
        Route::get('callback/{status}/{cart_id}', ['as' => 'callbackPage', 'uses' => 'CartController@callbackPage']);
        Route::post('updateStatus', ['as' => 'updateStatus', 'uses' => 'CartController@updateStatus']);
        Route::get('{finish?}', ['as' => 'cart', 'uses' => 'CartController@cart']);
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

// app routes
Route::group(['as' => 'app.', 'prefix' => 'app', 'middleware' => 'auth'], function () {
    // ALL PROFILES
    Route::get('dashboard', ['as' => 'dashboard', 'uses' => 'AppController@dashboard']);

    Route::group(['as' => 'wishlistItem.', 'prefix' => 'wishlistItem'], function () {
        Route::get('', ['as' => 'index', 'uses' => 'WishlistItemController@index']);
        Route::get('create/{id}', ['as' => 'create', 'uses' => 'WishlistItemController@create']);
        Route::get('delete/{id}', ['as' => 'delete', 'uses' => 'WishlistItemController@delete']);
        Route::get('deleteByProductId/{id}', ['as' => 'deleteByProductId', 'uses' => 'WishlistItemController@deleteByProductId']);
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

    Route::group(['as' => 'cart.', 'prefix' => 'cart'], function () {
        Route::get('', ['as' => 'index', 'uses' => 'CartController@index']);
        Route::post('search', ['as' => 'search', 'uses' => 'CartController@search']);
        Route::get('show/{id}', ['as' => 'show', 'uses' => 'CartController@show']);
        Route::get('delete/{id}', ['middleware' => 'role:ADMIN', 'as' => 'delete', 'uses' => 'CartController@delete']);
        Route::post('cancel/{id}', ['as' => 'cancel', 'uses' => 'CartController@cancel']);
    });

    // MIXED ADMIN, COLABORATOR & CUSTOMER PROFILES

    Route::group(['as' => 'user.', 'prefix' => 'user'], function () {
        Route::get('', ['middleware' => 'role:ADMIN', 'as' => 'index', 'uses' => 'UserController@index']);
        Route::post('search', ['middleware' => 'role:ADMIN', 'as' => 'search', 'uses' => 'UserController@search']);
        Route::get('create', ['middleware' => 'role:ADMIN', 'as' => 'create', 'uses' => 'UserController@create']);
        Route::post('store', ['middleware' => 'role:ADMIN', 'as' => 'store', 'uses' => 'UserController@store']);
        Route::get('edit/{id}', ['middleware' => 'role:ADMIN', 'as' => 'edit', 'uses' => 'UserController@edit']);
        Route::post('update/{id}', ['middleware' => 'role:ADMIN', 'as' => 'update', 'uses' => 'UserController@update']);
        Route::get('delete/{id}', ['middleware' => 'role:ADMIN', 'as' => 'delete', 'uses' => 'UserController@delete']);
        Route::get('passwordGenerate/{id}', ['middleware' => 'role:ADMIN', 'as' => 'passwordGenerate', 'uses' => 'UserController@passwordGenerate']);
        Route::get('passwordChange', ['as' => 'passwordChange', 'uses' => 'UserController@passwordChange']);
        Route::post('passwordChangePost', ['as' => 'passwordChangePost', 'uses' => 'UserController@passwordChangePost']);
        Route::get('accountUpdate', ['as' => 'accountUpdate', 'uses' => 'UserController@accountUpdate']);
        Route::post('accountUpdatePost', ['as' => 'accountUpdatePost', 'uses' => 'UserController@accountUpdatePost']);
    });

    Route::group(['as' => 'cartReview.', 'prefix' => 'cartReview'], function () {
        Route::get('', ['middleware' => 'role:ADMIN&COLABORATOR', 'as' => 'index', 'uses' => 'CartReviewController@index']);
        Route::post('search', ['middleware' => 'role:ADMIN&COLABORATOR', 'as' => 'search', 'uses' => 'CartReviewController@search']);
        Route::get('create/{cart_id}', ['as' => 'create', 'uses' => 'CartReviewController@create']);
        Route::post('store/{cart_id}', ['as' => 'store', 'uses' => 'CartReviewController@store']);
        Route::get('delete/{id}', ['middleware' => 'role:ADMIN&COLABORATOR', 'as' => 'delete', 'uses' => 'CartReviewController@delete']);
    });

    // MIXED ADMIN & COLABORATOR PROFILES

    Route::group(['as' => 'category.', 'prefix' => 'category', 'middleware' => 'role:ADMIN&COLABORATOR'], function () {
        Route::get('', ['as' => 'index', 'uses' => 'CategoryController@index']);
        Route::post('search', ['as' => 'search', 'uses' => 'CategoryController@search']);
        Route::get('create', ['as' => 'create', 'uses' => 'CategoryController@create']);
        Route::post('store', ['as' => 'store', 'uses' => 'CategoryController@store']);
        Route::get('edit/{id}', ['as' => 'edit', 'uses' => 'CategoryController@edit']);
        Route::post('update/{id}', ['as' => 'update', 'uses' => 'CategoryController@update']);
        Route::get('delete/{id}', ['as' => 'delete', 'uses' => 'CategoryController@delete']);
    });

    Route::group(['as' => 'product.', 'prefix' => 'product', 'middleware' => 'role:ADMIN&COLABORATOR'], function () {
        Route::get('', ['as' => 'index', 'uses' => 'ProductController@index']);
        Route::post('search', ['as' => 'search', 'uses' => 'ProductController@search']);
        Route::get('create', ['as' => 'create', 'uses' => 'ProductController@create']);
        Route::post('store', ['as' => 'store', 'uses' => 'ProductController@store']);
        Route::get('edit/{id}', ['as' => 'edit', 'uses' => 'ProductController@edit']);
        Route::post('update/{id}', ['as' => 'update', 'uses' => 'ProductController@update']);
        Route::get('delete/{id}', ['as' => 'delete', 'uses' => 'ProductController@delete']);
        Route::get('photo/main/{productId}/{photoId}', ['as' => 'photoMain', 'uses' => 'ProductController@photoMain']);
        Route::get('photo/remove/{photoId}', ['as' => 'photoRemove', 'uses' => 'ProductController@photoRemove']);
    });

    Route::group(['as' => 'productQuestion.', 'prefix' => 'productQuestion', 'middleware' => 'role:ADMIN&COLABORATOR'], function () {
        Route::get('', ['as' => 'index', 'uses' => 'ProductQuestionController@index']);
        Route::post('search', ['as' => 'search', 'uses' => 'ProductQuestionController@search']);
        Route::post('store', ['as' => 'store', 'uses' => 'ProductQuestionController@store']);
        Route::get('edit/{id}', ['as' => 'edit', 'uses' => 'ProductQuestionController@edit']);
        Route::post('update/{id}', ['as' => 'update', 'uses' => 'ProductQuestionController@update']);
        Route::get('delete/{id}', ['as' => 'delete', 'uses' => 'ProductQuestionController@delete']);
    });

    Route::group(['as' => 'productPhoto.', 'prefix' => 'productPhoto', 'middleware' => 'role:ADMIN&COLABORATOR'], function () {
        Route::get('', ['as' => 'index', 'uses' => 'ProductPhotoController@index']);
        Route::post('search', ['as' => 'search', 'uses' => 'ProductPhotoController@search']);
        Route::get('create', ['as' => 'create', 'uses' => 'ProductPhotoController@create']);
        Route::post('store', ['as' => 'store', 'uses' => 'ProductPhotoController@store']);
        Route::get('edit/{id}', ['as' => 'edit', 'uses' => 'ProductPhotoController@edit']);
        Route::post('update/{id}', ['as' => 'update', 'uses' => 'ProductPhotoController@update']);
        Route::get('delete/{id}', ['as' => 'delete', 'uses' => 'ProductPhotoController@delete']);
    });

    Route::group(['as' => 'featured.', 'prefix' => 'featured', 'middleware' => 'role:ADMIN&COLABORATOR'], function () {
        Route::get('', ['as' => 'index', 'uses' => 'FeaturedController@index']);
        Route::post('search', ['as' => 'search', 'uses' => 'FeaturedController@search']);
        Route::get('create', ['as' => 'create', 'uses' => 'FeaturedController@create']);
        Route::post('store', ['as' => 'store', 'uses' => 'FeaturedController@store']);
        Route::get('edit/{id}', ['as' => 'edit', 'uses' => 'FeaturedController@edit']);
        Route::post('update/{id}', ['as' => 'update', 'uses' => 'FeaturedController@update']);
        Route::get('delete/{id}', ['as' => 'delete', 'uses' => 'FeaturedController@delete']);
    });

    Route::group(['as' => 'contact.', 'prefix' => 'contact', 'middleware' => 'role:ADMIN&COLABORATOR'], function () {
        Route::get('', ['as' => 'index', 'uses' => 'ContactController@index']);
        Route::post('search', ['as' => 'search', 'uses' => 'ContactController@search']);
        Route::get('delete/{id}', ['as' => 'delete', 'uses' => 'ContactController@delete']);
        Route::get('reply/{id}', ['as' => 'reply', 'uses' => 'ContactController@reply']);
        Route::post('reply/{id}', ['as' => 'replyPost', 'uses' => 'ContactController@replyPost']);
    });

    // ONLY ADMIN PROFILE

    Route::group(['as' => 'paymentMethodsAvailable.', 'prefix' => 'paymentMethodsAvailable', 'middleware' => 'role:ADMIN'], function () {
        Route::get('', ['as' => 'index', 'uses' => 'PaymentMethodsAvailableController@index']);
        Route::post('search', ['as' => 'search', 'uses' => 'PaymentMethodsAvailableController@search']);
        Route::get('create', ['as' => 'create', 'uses' => 'PaymentMethodsAvailableController@create']);
        Route::post('store', ['as' => 'store', 'uses' => 'PaymentMethodsAvailableController@store']);
        Route::get('edit/{id}', ['as' => 'edit', 'uses' => 'PaymentMethodsAvailableController@edit']);
        Route::post('update/{id}', ['as' => 'update', 'uses' => 'PaymentMethodsAvailableController@update']);
        Route::get('delete/{id}', ['as' => 'delete', 'uses' => 'PaymentMethodsAvailableController@delete']);
    });

    Route::group(['as' => 'store.', 'prefix' => 'store', 'middleware' => 'role:ADMIN'], function () {
        // Route::get('', ['as' => 'index', 'uses' => 'StoreController@index']);
        // Route::post('search', ['as' => 'search', 'uses' => 'StoreController@search']);
        // Route::get('create', ['as' => 'create', 'uses' => 'StoreController@create']);
        // Route::post('store', ['as' => 'store', 'uses' => 'StoreController@store']);
        Route::get('edit/{id}', ['as' => 'edit', 'uses' => 'StoreController@edit']);
        Route::post('update/{id}', ['as' => 'update', 'uses' => 'StoreController@update']);
        // Route::get('delete/{id}', ['as' => 'delete', 'uses' => 'StoreController@delete']);
    });

    Route::group(['as' => 'config.', 'prefix' => 'config', 'middleware' => 'role:ADMIN'], function () {
        Route::get('', ['as' => 'config', 'uses' => 'AppController@config']);
    });
});
