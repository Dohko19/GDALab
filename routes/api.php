<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\CustomerController;
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

Route::middleware('auth:sanctum')->group(function() {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::post('/customer/create', [CustomerController::class, 'create']);
    Route::get('/customer', [CustomerController::class, 'searchCustomer']);
    Route::delete('/customer/{dni}', [CustomerController::class, 'destroy']);
});


Route::post('/login', [AuthController::class, 'login']);
