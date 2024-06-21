<?php

use App\Http\Controllers\ClienController;
use App\Http\Controllers\CorridasController;
use App\Http\Middleware\isLocationValid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\RegisterClientAPI;
use App\Http\Middleware\LoginUser;
use App\Http\Middleware\isPassengerValid;
use Illuminate\Routing\RouteGroup;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('corridas',[ CorridasController::class, 'index']); 
    Route::get('pasajeros/{id}',[ CorridasController::class, 'show']); 
    Route::put('pasajero/{id}',[ CorridasController::class, 'update'])->middleware(isPassengerValid::class); 
});

Route::post('register',[ ClienController::class, 'register'])->middleware(RegisterClientAPI::class);
Route::post('login',[ ClienController::class, 'LoginPlainText'])->middleware([LoginUser::class]);