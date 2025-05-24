<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\ClientesController;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('dashboard', [ClientesController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

// Rutas para clientes
Route::get('/clientes', [ClientesController::class, 'index'])->middleware(['auth', 'verified'])->name('clientes');
Route::post('/clientes', [ClientesController::class, 'incluirCliente'])->middleware(['auth', 'verified'])->name('clientes.store');
Route::patch('/clientes/{id}/baja', [ClientesController::class, 'darBajaPorMorosidad'])->middleware(['auth', 'verified'])->name('clientes.baja');
Route::get('/clientes/{id}/pago-mensual', [ClientesController::class, 'calcularPagoMensual'])->middleware(['auth', 'verified'])->name('clientes.pago');
Route::get('/clientes/{id}/llamadas-rastreo', [ClientesController::class, 'llamadasConRastreo'])->middleware(['auth', 'verified'])->name('clientes.rastreo');
Route::get('/clientes/mas-servicios', [ClientesController::class, 'clienteMasServicios'])->middleware(['auth', 'verified'])->name('clientes.masServicios');
Route::get('/clientes/tele-seleccion', [ClientesController::class, 'clientesTeleSeleccion'])->middleware(['auth', 'verified'])->name('clientes.teleSeleccion');
Route::put('/clientes/{id}', [ClientesController::class, 'update'])->middleware(['auth', 'verified'])->name('clientes.update');
Route::delete('/clientes/{id}', [ClientesController::class, 'destroy'])->middleware(['auth', 'verified'])->name('clientes.destroy');

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
