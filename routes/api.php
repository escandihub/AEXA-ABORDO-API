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
use App\Http\Middleware\CheckDevice;
use App\Http\Middleware\validateUpdate;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('corridas',[ CorridasController::class, 'choseTyeOfquery']); // ->middleware(VadilateLocation::class); 
    Route::get('pasajeros/{id}',[ CorridasController::class, 'show']); //->middleware(VadilateLocation::class); 
    Route::put('pasajero/{pasajero_id}',[ CorridasController::class, 'update'])->middleware(isPassengerValid::class); 
    Route::get('pasajero_equipaje/{pasajero_id}',[ PasajerosController::class, 'getInfo']); 
    Route::post('pasajero_equipaje/{pasajero_id}',[ PasajerosController::class, 'saveDocumentation']); 
    Route::get('pasajero/asientos/{corrida}',[ PasajerosController::class, 'asientos']); 
    
    // Route::get('corrida/test',[ CorridasController::class, 'terminales']); 
    //documentacion
    Route::get('pasajero_equipaje/documents/{pasajero_id}',[ PasajerosController::class, 'getDocumentation']); 
    Route::put('pasajero/documents/{pasajero_id}',[ PasajerosController::class, 'updateDocumentation']); 
});

Route::post('register',[ ClienController::class, 'register'])->middleware(RegisterClientAPI::class); // validateUpdate::class
Route::post('login',[ ClienController::class, 'LoginPlainText'])->middleware([LoginUser::class, CheckDevice::class, validateUpdate::class]);
Route::get('corridas/all',[ CorridasController::class, 'getCorridas']); 
Route::get('corridas/columna',[ CorridasController::class, 'readCorridas']); 
# administracion del dispositivo
// Route::post('device-info',[ CorridasController::class, 'readCorridas']); 
// Route::post('app-updated/{app_id}',[ AppTraking::class, 'updateStatus']); 