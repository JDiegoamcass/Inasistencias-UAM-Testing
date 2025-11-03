<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClaseController;
use App\Http\Controllers\ProfesorController;
use App\Http\Controllers\SolicitudController;

// Agrupar bajo middleware si es necesario
Route::middleware(['auth'])->group(function () {
    // Profesor
    Route::resource('profesores', ProfesorController::class);

    // Clases
    Route::resource('clases', ClaseController::class);

    // Bandeja de solicitudes (solo index y update)
    Route::get('/solicitudes/pendientes', [SolicitudController::class, 'index'])->name('solicitudes.index');
    Route::put('/solicitudes/{solicitud}', [SolicitudController::class, 'update'])->name('solicitudes.update');
});
