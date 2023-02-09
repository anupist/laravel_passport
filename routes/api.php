<?php

use App\Http\Controllers\GroupController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PassportAuthController;
use App\Http\Controllers\PostController;
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
Route::group(['middleware' => ['cors', 'json.response']], function () {
    Route::post('register', [PassportAuthController::class, 'register']);
    Route::post('login', [PassportAuthController::class, 'login']);
    Route::middleware('auth:api')->group(function () {
        // Route::resource('posts', PostController::class);
        Route::post('create', [PostController::class,'store'])->name('store')->middleware('api.admin');
        Route::get('post/index', [PostController::class,'index'])->name('index')->middleware('api.admin');
        Route::get('group/index', [GroupController::class,'index'])->name('index')->middleware('api.employee');
        Route::get('logout', [PassportAuthController::class, 'logout']);
    });
});
