<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});
Route::get('getPrices', 'App\Http\Controllers\Api\InfoControl@getPrices');
Route::get('getCachedInvoicers', 'App\Http\Controllers\Api\InvoiceController@getInvoicers')->middleware('auth');
Route::post('getInvoicerByNIP', 'App\Http\Controllers\Api\InvoiceController@getInvoicersByNIP')->middleware('auth');
Route::post('getInvoicerById', 'App\Http\Controllers\Api\InvoiceController@getInvoicersById')->middleware('auth');
Route::post('saveInvoicer', 'App\Http\Controllers\Api\InvoiceController@saveInvoicer')->middleware('auth');
Route::post('generateInvoice', 'App\Http\Controllers\Api\InvoiceController@generateInvoice')->middleware('auth');
Route::post('setPrices', 'App\Http\Controllers\Api\InfoControl@setPrices')->middleware('auth');
Route::post('generateVoucher', 'App\Http\Controllers\Api\InfoControl@generateVoucher')->middleware('auth');
Route::post('generateVoucherIMG', 'App\Http\Controllers\Api\InfoControl@generateVoucherIMG');
Route::post('checkVoucher', 'App\Http\Controllers\Api\InfoControl@checkVoucher')->middleware('auth');
Route::post('useVoucher', 'App\Http\Controllers\Api\InfoControl@useVoucher')->middleware('auth');
Route::post('addDane', 'App\Http\Controllers\Api\DaneFakturController@saveDane')->middleware('auth');
Route::post('changePassword', [App\Http\Controllers\Api\AccountController::class, 'updatePassword'])->name('update-password');
