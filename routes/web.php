<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Redirect;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Clientes\ClientesController;
use App\Http\Controllers\Vehiculos\VehiculosController;
use App\Http\Controllers\Servicios\ServiciosController;
use App\Http\Controllers\Calendario\CalendarioController;
use App\Http\Controllers\OrdenTrabajos\OrdenTrabajosController;
use App\Http\Controllers\Mail\MailController;

Auth::routes();
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/home', [HomeController::class, 'index'])->name('home');

// CRUD
Route::resource('clientes', ClientesController::class)
    ->missing(function (Request $request) {
        return Redirect::route('clientes.index');
    });

Route::resource('vehiculos', VehiculosController::class)
    ->missing(function (Request $request) {
        return Redirect::route('vehiculos.index');
    });

Route::resource('servicios', ServiciosController::class)
    ->missing(function (Request $request) {
        return Redirect::route('servicios.index');
    });

Route::resource('ordentrabajos', OrdenTrabajosController::class)
    ->missing(function (Request $request) {
        return Redirect::route('ordentrabajos.index');
    });

// CUSTOM
Route::get('calendario', [CalendarioController::class, 'index'])->name('calendario.index');

// AJAX methods
Route::post('ordentrabajos/getVehiculos', [OrdenTrabajosController::class, 'getVehiculosByClienteId']);
Route::post('ordentrabajos/getCliente', [OrdenTrabajosController::class, 'getClienteByVehiculoId']);
Route::get('calendario/getEvents', [CalendarioController::class, 'getEvents'])->name('calendario.getEvents');
Route::post('calendario/addEvent', [CalendarioController::class, 'addEvent'])->name('calendario.addEvent');
Route::post('calendario/deleteEvent', [CalendarioController::class, 'deleteEvent'])->name('calendario.deleteEvent');
Route::get('calendario/getUpcomingEvents', [CalendarioController::class, 'getUpcomingEvents'])->name('calendario.getUpcomingEvents');