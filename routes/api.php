<?php

use App\Http\Controllers\AppController;
use App\Services\CoinPayments;
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

Route::get('cron/{password}', [AppController::class, 'cron'])->name('cron');

Route::post('wallet/process/coinpayments/{id}', function ($id) {
    CoinPayments::process($id, 'process');
})->name('wallet.process.coinpayments');

Route::get('receive/{auth_key}', [AppController::class, 'receive'])->name('receive');
Route::post('receive/{auth_key}', [AppController::class, 'receive']);

Route::get('receive', [AppController::class, 'receiveDepreciated']);
Route::post('receive', [AppController::class, 'receiveDepreciated']);
