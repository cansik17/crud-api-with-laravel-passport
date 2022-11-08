<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MoviesController;

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



Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
Route::post('logout', [AuthController::class, 'logout'])->middleware("auth:api");

Route::get('movies', [MoviesController::class, 'index'])->middleware("auth:api");
Route::post('movies', [MoviesController::class, 'store'])->middleware("auth:api");
Route::get('movies/{id}', [MoviesController::class, 'show'])->middleware("auth:api");
Route::put('movies/{id}', [MoviesController::class, 'update'])->middleware("auth:api");
Route::delete('movies/{id}', [MoviesController::class, 'destroy'])->middleware("auth:api");
