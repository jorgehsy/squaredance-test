<?php

use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\StoreController;
use \App\Http\Controllers\Api\AuthController;
use \App\Http\Controllers\ProductController;
use \App\Http\Controllers\ManagerController;
use \App\Http\Controllers\UserController;

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
    Route::get('/users/{user}/notifications', [UserController::class, 'getNotifications']);
    Route::put('/users/{user}/notifications/{id}', [UserController::class, 'markSeenNotification']);

    Route::post('product/{product}/sell', [StoreController::class, 'makeSell']);

    // TODO: protect endpoints with roles, only managers can update products
    // -> https://laravel.com/docs/9.x/authorization
    Route::post('product/{product}/manage/{status}', [ManagerController::class, 'manageProduct']);
});