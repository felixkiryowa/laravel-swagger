<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\BookController;

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


Route::post('v1/user-register', [AuthController::class, 'register']);
Route::post('v1/user-login', [AuthController::class, 'authenticate']);

Route::group(['middleware' => ['jwt.verify']], function() {
    Route::get('v1/user', [AuthController::class, 'getAuthenticatedUser']);
    Route::get('v1/books', [BookController::class, 'getAllBooks']);
    Route::post('v1/create/book', [BookController::class, 'storeBook']);
    Route::get('v1/get/book', [BookController::class, 'showGivenBook']);
});
