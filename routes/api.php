<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\PostitController;
use App\Http\Controllers\UserController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('register', [UserController::class, 'store']);
Route::post('login', [AuthController::class, 'login']);

Route::group(['middleware' => ['jwt.verify']], function() {
    Route::post('groups', [GroupController::class, 'store']);
    Route::get('groups', [GroupController::class, 'index']);
    Route::delete('groups/{id}', [GroupController::class, 'destroy']);
    Route::get('groups/{id}', [GroupController::class, 'show']);
    Route::post('groups/{id}/users', [GroupController::class, 'subscribe']);

    Route::post('groups/{id}/postits', [PostitController::class, 'store']);
    Route::get('groups/{id}/postits', [PostitController::class, 'listByGroupId']);
    Route::delete('postits/{id}', [PostitController::class, 'destroy']);
    Route::get('postits/{id}', [PostitController::class, 'show']);
});