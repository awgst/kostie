<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Kost\Http\Controllers\API\KostController;

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

Route::group(['prefix' => 'v1/kost'], function () {
    Route::get('/', [KostController::class, 'index']);
    Route::get('/{id}', [KostController::class, 'show']);
    Route::group(['middleware' => 'auth:api'], function () {
        Route::post('/', [KostController::class, 'store']);
        Route::post('/{id}/ask-availability', [KostController::class, 'askAvailability']);
        Route::put('/{id}', [KostController::class, 'update']);
        Route::delete('/{id}', [KostController::class, 'destroy']);
    });
});