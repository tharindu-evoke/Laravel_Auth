<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\UserController;

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::group(['middleware' => 'api',  'prefix' => 'auth'], function ($router) {
    Route::post('register', [UserController::class, 'register']);      // OK
    Route::post('login', [UserController::class, 'login']);            // OK
    Route::post('logout', [UserController::class, 'logout']);          // OK
    Route::get('refresh', [UserController::class, 'refresh']);         // OK
    Route::get('profile', [UserController::class, 'profile']);         // OK
});
