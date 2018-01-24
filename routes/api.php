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

Route::middleware('auth:api')->group(function() {
    Route::get('/me', function (Request $request) {
        return $request->user();
    });

    // Wallet
    Route::prefix('/wallet')->group(function() {
        Route::get('/balance', 'WalletController@balance');
        Route::get('/balance/general', 'WalletController@balanceAccountGeneral');
        Route::post('/transfer/{customerTransfer}', 'WalletController@transferToAnotherCustomer')
            ->where('customerTransfer', '[0-9]+');
        Route::post('/transfer/account', 'WalletController@transferToAccount');
        Route::post('/fund', 'WalletController@fund');
        Route::get('/transactions', 'WalletController@transactions');
    });
});
