<?php

namespace App\Http\Controllers\Docente;

use App\Http\Controllers\Controller;
use App\Models\Docente;
use App\Models\Grupo;
use App\Models\Nota;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * CU22 - Dashboard del docente (ver sus grupos y calificar)
     */
    public function index()
    {
        // Obtener el docente del usuario autenticado
        $docente = Docente::where('usuario_id', Auth::id())->first();

        if (!$docente) {
            abort(403, 'No tienes perfil de docente');
        }

        // Obtener grupos del docente
        $grupos = $docente->grupos()->with(['postulantes', 'convocatoria'])->get();

        // Estadísticas
        $estadisticas = [
            'grupos' => $grupos->count(),
            'postulantes' => $grupos->sum(fn($g) => $g->postulantes()->count()),
            'notas_registradas' => $docente->notas()->count(),
        ];

        return view('docente.dashboard', compact('docente', 'grupos', 'estadisticas'));
    }

    /**
     * CU22 - Ver postulantes de un grupo para calificar
     */
    public function grupoPostulantes(Grupo $grupo)
    {
        // Verificar que el docente esté asignado al grupo
        $docente = Docente::where('usuario_id', Auth::id())->firstOrFail();

        if (!$grupo->docentes()->where('docente_id', $docente->id)->exists()) {
            abort(403, 'No tienes acceso a este grupo');
        }

        $grupo->load(['postulantes', 'materias']);
        $postulantes = $grupo->postulantes()->get();

        // Para cada postulante, cargar sus notas
        foreach ($postulantes as $postulante) {
            $postulante->notas_grupo = $postulante->notas()
                ->where('grupo_id', $grupo->id)
                ->with('materia')
                ->get()
                ->groupBy('materia_id');
        }

        return view('docente.grupo.postulantes', compact('docente', 'grupo', 'postulantes'));
    }

    /**
     * CU22 - Formulario para registrar/editar nota (desde docente)
     */
    public function registrarNota(Grupo $grupo, $postulanteId)
    {
        $docente = Docente::where('usuario_id', Auth::id())->firstOrFail();

        // Verificar acceso
        if (!$grupo->docentes()->where('docente_id', $docente->id)->exists()) {
            abort(403, 'No tienes acceso a este grupo');
        }

        $postulante = $grupo->postulantes()->findOrFail($postulanteId);
        $materias = $grupo->materias()->get();
        $notas = $postulante->notas()
            ->where('grupo_id', $grupo->id)
            ->get()
            ->keyBy('materia_id');

        return view('docente.grupo.registrar-nota', compact('docente', 'grupo', 'postulante', 'materias', 'notas'));
    }
}
