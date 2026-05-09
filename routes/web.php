<?php

use App\Http\Controllers\ActividadController;
use App\Http\Controllers\AlumnoController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DocenteController;
use App\Http\Controllers\GrupoController;
use App\Http\Controllers\MateriaController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReporteController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => redirect()->route('dashboard'));

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', DashboardController::class)->name('dashboard');

    // Perfil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Reportes (admin + docente)
    Route::middleware('role:administrador|docente')->group(function () {
        Route::get('/reportes/alumno/{alumno}/pdf', [ReporteController::class, 'alumnoPdf'])->name('reportes.alumno');
        Route::get('/reportes/grupo/{grupo}/pdf', [ReporteController::class, 'grupoPdf'])->name('reportes.grupo');
    });

    // Solo administrador
    Route::middleware('role:administrador')->group(function () {
        Route::resource('alumnos', AlumnoController::class);
        Route::resource('docentes', DocenteController::class);
        Route::resource('materias', MateriaController::class)->except('show');
    });

    // Admin + Docente
    Route::middleware('role:administrador|docente')->group(function () {
        Route::resource('grupos', GrupoController::class);
        Route::resource('actividades', ActividadController::class)
            ->parameters(['actividades' => 'actividad']);
        Route::post('/actividades/{actividad}/asistencia', [ActividadController::class, 'asistencia'])->name('actividades.asistencia');
    });
});

require __DIR__.'/auth.php';
