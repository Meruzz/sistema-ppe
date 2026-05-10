<?php

use App\Http\Controllers\ActividadController;
use App\Http\Controllers\AlumnoController;
use App\Http\Controllers\AnioLectivoController;
use App\Http\Controllers\BitacoraController;
use App\Http\Controllers\ConfiguracionController;
use App\Http\Controllers\ConvalidacionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DocenteController;
use App\Http\Controllers\GrupoController;
use App\Http\Controllers\AmbitoController;
use App\Http\Controllers\ImportController;
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
        // importar debe ir antes del resource para no ser capturado por {alumno}
        Route::get('/alumnos/importar', [ImportController::class, 'create'])->name('alumnos.importar');
        Route::post('/alumnos/importar', [ImportController::class, 'store'])->name('alumnos.importar.store');
        Route::resource('alumnos', AlumnoController::class);
        Route::resource('docentes', DocenteController::class);
        Route::resource('ambitos', AmbitoController::class)->except('show');
        Route::resource('anio-lectivos', AnioLectivoController::class)
            ->parameters(['anio-lectivos' => 'anio_lectivo'])
            ->except('show');
        Route::resource('convalidaciones', ConvalidacionController::class)
            ->only(['create', 'store', 'edit', 'update', 'destroy']);
        Route::get('/configuracion', [ConfiguracionController::class, 'index'])->name('configuraciones.index');
        Route::put('/configuracion', [ConfiguracionController::class, 'update'])->name('configuraciones.update');
    });

    // Admin + Docente
    Route::middleware('role:administrador|docente')->group(function () {
        Route::resource('grupos', GrupoController::class);
        Route::resource('actividades', ActividadController::class)
            ->parameters(['actividades' => 'actividad']);
        Route::post('/actividades/{actividad}/asistencia', [ActividadController::class, 'asistencia'])->name('actividades.asistencia');
    });

    // Bitácoras (acceso por rol controlado en el controlador)
    Route::resource('bitacoras', BitacoraController::class)
        ->parameters(['bitacoras' => 'bitacora'])
        ->only(['index', 'create', 'store', 'show', 'edit', 'update']);
    Route::post('/bitacoras/{bitacora}/revisar', [BitacoraController::class, 'revisar'])->name('bitacoras.revisar');
});

require __DIR__.'/auth.php';
