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
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post("store",[UserController:: class,'store']);
Route::post("authenticate",[UserController:: class,'authenticate']);
Route::get('users/{id}', [UserController::class, 'getUser']);
Route::put('users/{id}', [UserController::class, 'update']);

Route::group(['middleware' => 'jwt.auth'], function () {
    Route::get('index', [UserController::class, 'index']);   
    Route::delete('/delete/{id}', [UserController::class, 'destroy']);
    
});
