<?php

use App\Http\Controllers\ClienController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\RegisterClientAPI;
use App\Http\Middleware\LoginUser;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::post('register',[ ClienController::class, 'register'])->middleware(RegisterClientAPI::class);
Route::post('login',[ ClienController::class, 'LoginPlainText'])->middleware(LoginUser::class);