<?php

use App\Http\Controllers\ClienController;
use App\Http\Controllers\CorridasController;
use App\Http\Middleware\isLocationValid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\RegisterClientAPI;
use App\Http\Middleware\LoginUser;
use App\Http\Middleware\isPassengerValid;
use App\Http\Middleware\VadilateLocation;
use Illuminate\Routing\RouteGroup;
use App\Http\Controllers\PasajerosController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('corridas',[ CorridasController::class, 'index']); // ->middleware(VadilateLocation::class); 
    Route::get('pasajeros/{id}',[ CorridasController::class, 'show']); //->middleware(VadilateLocation::class); 
    Route::put('pasajero/{pasajero_id}',[ CorridasController::class, 'update'])->middleware(isPassengerValid::class); 
    Route::get('pasajero/asientos/{corrida}',[ PasajerosController::class, 'asientos']); 
});

Route::post('register',[ ClienController::class, 'register'])->middleware(RegisterClientAPI::class);
Route::post('login',[ ClienController::class, 'LoginPlainText'])->middleware([LoginUser::class]);