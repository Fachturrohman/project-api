<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\ApiController;

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

Route::post('/login', [AuthController::class, 'login']);
Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::resource('users', UserController::class);

    Route::get('/search-by-nim', [ApiController::class, 'searchNIM']);
    Route::get('/search-by-name', [ApiController::class, 'searchName']);
    Route::get('/search-by-ymd', [ApiController::class, 'searchYMD']);

    Route::post('/logout', [AuthController::class, 'logout']);
});
