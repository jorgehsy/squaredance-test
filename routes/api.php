<?php

use Illuminate\Http\Request;
use \App\Http\Controllers\ProductController;
use \App\Http\Controllers\ManagerController;
use \App\Http\Controllers\StoreController;
use \App\Http\Controllers\Api\AuthController;
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

Route::post('/auth/login', [AuthController::class, 'login']);
Route::post('/auth/register', [AuthController::class, 'register']);

Route::middleware('auth:sanctum')->group( function () {
    Route::resource('products', ProductController::class);

    Route::post('product/{product}/sell', [StoreController::class, 'makeSell']);

    Route::post('product/{product}/manage/{status}', [ManagerController::class, 'manageProduct']);
});