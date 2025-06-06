<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\ClientesController;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Rutas para clientes
Route::get('/clientes', [ClientesController::class, 'index'])->middleware(['auth', 'verified'])->name('clientes');
Route::post('/clientes', [ClientesController::class, 'store'])->middleware(['auth', 'verified'])->name('clientes.store');
Route::put('/clientes/{id}', [ClientesController::class, 'update'])->middleware(['auth', 'verified'])->name('clientes.update');
Route::delete('/clientes/{id}', [ClientesController::class, 'destroy'])->middleware(['auth', 'verified'])->name('clientes.destroy');
Route::patch('/clientes/{id}/baja', [ClientesController::class, 'darBajaPorMorosidad'])->middleware(['auth', 'verified'])->name('clientes.baja');
Route::get('/clientes/{id}/pago-mensual', [ClientesController::class, 'calcularPagoMensual'])->middleware(['auth', 'verified'])->name('clientes.pago');
Route::get('/clientes/llamadas-rastreo', [ClientesController::class, 'llamadasRecibidas'])->middleware(['auth', 'verified'])->name('clientes.llamadasRastreo');
Route::get('/clientes/buscar-llamadas', function () {
    return Inertia::render('Clientes/LlamadasRastreo');
})->middleware(['auth', 'verified'])->name('clientes.buscarLlamadas');
Route::get('/clientes/mas-servicios', [ClientesController::class, 'clienteMasServicios'])
    ->middleware(['auth', 'verified'])
    ->name('clientes.masServicios');
Route::get('/clientes/tele-seleccion', [ClientesController::class, 'clientesTeleSeleccion'])->middleware(['auth', 'verified'])->name('clientes.teleSeleccion');
Route::get('/clientes/pagos-mensuales', [ClientesController::class, 'pagosMensuales'])->middleware(['auth', 'verified'])->name('clientes.pagosMensuales');
Route::get('/clientes/llamadas-recibidas', [ClientesController::class, 'llamadasRecibidas'])->middleware(['auth', 'verified'])->name('clientes.llamadasRecibidas');


require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
