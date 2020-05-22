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

// payment routes
Route::group(['as' => 'mercadoPago.', 'prefix' => 'mercado-pago'], function () {
    Route::get('preview', 'MercadoPagoController@preview')->name('preview');
    Route::get('create-customer-sandbox', 'MercadoPagoController@createCustomerSandbox')->name('createCustomerSandbox');
    Route::get('status', 'MercadoPagoController@status')->name('status');
    Route::get('callback/{type}', 'MercadoPagoController@callback')->name('callback');
});

Route::get('/', function () {
    return view('welcome');
});
