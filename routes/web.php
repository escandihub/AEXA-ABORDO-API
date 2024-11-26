<?php

use App\Http\Controllers\ProfileController;
use App\Livewire\Terminal;
use App\Livewire\Map\Container;
use App\Livewire\VersionApp;
use App\Livewire\Device\AccessList;
use App\Livewire\DevicesList;
use App\Livewire\Monitoreo;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
});

Route::get('/terminales', Terminal::class)->name('terminales');
Route::get('/app-traking', VersionApp::class)->name('app.abordo.traking');
Route::get('/devices/access', AccessList::class)->name('device.access');
Route::get('/devices', DevicesList::class)->name('device.list');
Route::get('/monitoreo', Monitoreo::class)->name('device.monitoreo');
// Route::get('/map', Container::class)->name('map');

require __DIR__.'/auth.php';
