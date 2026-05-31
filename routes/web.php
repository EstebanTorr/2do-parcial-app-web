<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Public\PreRegistroEstudianteController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\PostulanteController;

// ── RAÍZ → LOGIN (O AL DASHBOARD CORRESPONDIENTE SI YA ESTÁ LOGUEADO) ──
Route::get('/', function () {
    if (Illuminate\Support\Facades\Auth::check()) {
        $usuario = Illuminate\Support\Facades\Auth::user();
        $rol = Illuminate\Support\Facades\DB::table('rol')->where('id', $usuario->rol_id)->value('nombre_rol');

        return match($rol) {
            'ADMINISTRATIVO' => redirect()->route('admin.dashboard'),
            'DOCENTE'        => redirect()->route('docente.dashboard'),
            'POSTULANTE'     => redirect()->route('postulante.dashboard'),
            default          => redirect()->route('login'),
        };
    }
    return redirect()->route('login');
});

// ── DASHBOARD GENÉRICO (Redirige al dashboard de su rol) ─────
Route::get('/dashboard', function () {
    if (Illuminate\Support\Facades\Auth::check()) {
        $usuario = Illuminate\Support\Facades\Auth::user();
        $rol = Illuminate\Support\Facades\DB::table('rol')->where('id', $usuario->rol_id)->value('nombre_rol');

        return match($rol) {
            'ADMINISTRATIVO' => redirect()->route('admin.dashboard'),
            'DOCENTE'        => redirect()->route('docente.dashboard'),
            'POSTULANTE'     => redirect()->route('postulante.dashboard'),
            default          => view('dashboard'),
        };
    }
    return redirect()->route('login');
})->middleware(['auth', 'verified'])->name('dashboard');

// ── PERFIL ──────────────────────────────────────────────────
Route::middleware('auth')->group(function () {
    Route::get('/profile',    [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile',  [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ── AUTH (login / logout) ────────────────────────────────────
require __DIR__.'/auth.php';

// ── ADMIN ────────────────────────────────────────────────────
Route::middleware('auth')->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])
         ->name('dashboard');

    // Pre-registros
    Route::get('/pre-registros', [App\Http\Controllers\Admin\PreRegistroController::class, 'index'])
         ->name('pre-registros.index');
    Route::get('/pre-registros/estudiante/{id}', [App\Http\Controllers\Admin\PreRegistroController::class, 'showEstudiante'])
         ->name('pre-registros.estudiante.show');
    Route::get('/pre-registros/docente/{id}', [App\Http\Controllers\Admin\PreRegistroController::class, 'showDocente'])
         ->name('pre-registros.docente.show');
    Route::post('/pre-registros/estudiante/{id}/aprobar', [App\Http\Controllers\Admin\PreRegistroController::class, 'aprobarEstudiante'])
         ->name('pre-registros.estudiante.aprobar');
    Route::post('/pre-registros/estudiante/{id}/rechazar', [App\Http\Controllers\Admin\PreRegistroController::class, 'rechazarEstudiante'])
         ->name('pre-registros.estudiante.rechazar');
    Route::post('/pre-registros/docente/{id}/rechazar', [App\Http\Controllers\Admin\PreRegistroController::class, 'rechazarDocente'])
         ->name('pre-registros.docente.rechazar');

    // Postulantes
    Route::get('/postulantes', fn() => view('admin.coming-soon', ['titulo' => 'Postulantes', 'icono' => 'ti-id-badge']))
         ->name('postulantes.index');

    // Docentes
    Route::get('/docentes', fn() => view('admin.coming-soon', ['titulo' => 'Docentes', 'icono' => 'ti-chalkboard']))
         ->name('docentes.index');

    // Grupos
    Route::get('/grupos', [App\Http\Controllers\Admin\GrupoController::class, 'index'])
         ->name('grupos.index');
    Route::post('/grupos/generar', [App\Http\Controllers\Admin\GrupoController::class, 'generar'])
         ->name('grupos.generar');
    Route::post('/grupos/limpiar', [App\Http\Controllers\Admin\GrupoController::class, 'limpiar'])
         ->name('grupos.limpiar');
    Route::post('/grupos/auto-asignar', [App\Http\Controllers\Admin\GrupoController::class, 'autoAsignar'])
         ->name('grupos.auto-asignar');
    Route::get('/grupos/{grupo}', [App\Http\Controllers\Admin\GrupoController::class, 'show'])
         ->name('grupos.show');
    Route::post('/grupos/{grupo}/asignar-postulante', [App\Http\Controllers\Admin\GrupoController::class, 'asignarPostulante'])
         ->name('grupos.asignar-postulante');
    Route::post('/grupos/{grupo}/desasignar-postulante', [App\Http\Controllers\Admin\GrupoController::class, 'desasignarPostulante'])
         ->name('grupos.desasignar-postulante');
    Route::post('/grupos/{grupo}/asignar-docente', [App\Http\Controllers\Admin\GrupoController::class, 'asignarDocente'])
         ->name('grupos.asignar-docente');
    Route::post('/grupos/{grupo}/desasignar-docente', [App\Http\Controllers\Admin\GrupoController::class, 'desasignarDocente'])
         ->name('grupos.desasignar-docente');
    Route::get('/grupos/{grupo}/docentes', [App\Http\Controllers\Admin\GrupoController::class, 'docentesPorGrupo'])
         ->name('grupos.docentes');

    // Exámenes
    Route::get('/examenes', fn() => view('admin.coming-soon', ['titulo' => 'Exámenes', 'icono' => 'ti-file-text']))
         ->name('examenes.index');

    // Convocatorias
    Route::get('/convocatorias', [App\Http\Controllers\Admin\ConvocatoriaController::class, 'index'])
         ->name('convocatorias.index');
    Route::post('/convocatorias', [App\Http\Controllers\Admin\ConvocatoriaController::class, 'store'])
         ->name('convocatorias.store');
    Route::get('/convocatorias/{id}/edit', [App\Http\Controllers\Admin\ConvocatoriaController::class, 'edit'])
         ->name('convocatorias.edit');
    Route::patch('/convocatorias/{id}', [App\Http\Controllers\Admin\ConvocatoriaController::class, 'update'])
         ->name('convocatorias.update');
    Route::post('/convocatorias/{id}/activar', [App\Http\Controllers\Admin\ConvocatoriaController::class, 'activar'])
         ->name('convocatorias.activar');
    Route::post('/convocatorias/{id}/concluir', [App\Http\Controllers\Admin\ConvocatoriaController::class, 'concluir'])
         ->name('convocatorias.concluir');

    // Reportes
    Route::get('/reportes', fn() => view('admin.coming-soon', ['titulo' => 'Reportes', 'icono' => 'ti-chart-bar']))
         ->name('reportes.index');

    // Resultados / Admisión
    Route::get('/admision', fn() => view('admin.coming-soon', ['titulo' => 'Ejecutar Admisión', 'icono' => 'ti-trophy']))
         ->name('resultados.admision');

    // Carga masiva
    Route::get('/carga-masiva', fn() => view('admin.coming-soon', ['titulo' => 'Carga Masiva CSV', 'icono' => 'ti-upload']))
         ->name('carga-masiva.index');
});

// ── PANEL POSTULANTE ─────────────────────────────────────────
Route::middleware(['auth', 'role:POSTULANTE'])->prefix('postulante')->name('postulante.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Postulante\DashboardController::class, 'index'])
         ->name('dashboard');
    Route::get('/notas', [App\Http\Controllers\Postulante\DashboardController::class, 'verNotas'])
         ->name('notas');
    Route::get('/grupo', [App\Http\Controllers\Postulante\DashboardController::class, 'verGrupo'])
         ->name('grupo');
});

// ── PANELES (stubs temporales) ───────────────────────────────
Route::get('/docente/dashboard',    fn() => 'Panel Docente — próximamente')->name('docente.dashboard');

// ── PRE-REGISTRO ESTUDIANTE (público) ────────────────────────
Route::get('/pre-registro/estudiante',
    [PreRegistroEstudianteController::class, 'create'])->name('pre-registro.estudiante');

Route::post('/pre-registro/estudiante',
    [PreRegistroEstudianteController::class, 'store'])->name('pre-registro.estudiante.store');

Route::get('/pre-registro/estudiante/exito',
    [PreRegistroEstudianteController::class, 'exito'])->name('pre-registro.estudiante.exito');

// ── PRE-REGISTRO DOCENTE (stub) ──────────────────────────────
Route::get('/pre-registro/docente', fn() => 'Pre-registro docente — próximamente')
     ->name('pre-registro.docente');

Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:ADMINISTRATIVO'])->group(function () {
 
    // Postulantes
    Route::get('postulantes/export',        [PostulanteController::class, 'export'])->name('postulantes.export');
    Route::resource('postulantes', PostulanteController::class)
     ->except(['store']);
 
});