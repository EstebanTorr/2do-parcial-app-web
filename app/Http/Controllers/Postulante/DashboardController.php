<?php

namespace App\Http\Controllers\Postulante;

use App\Http\Controllers\Controller;
use App\Models\Postulante;
use App\Models\ResultadoFinal;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * CU16 - Dashboard del postulante (ver su estado final)
     */
    public function index()
    {
        $usuario = Auth::user();
        $postulante = Postulante::where('usuario_id', $usuario->id)->first();

        if (!$postulante) {
            return view('postulante.sin-postulante');
        }

        // Cargar relaciones importantes
        $postulante->load([
            'convocatoria',
            'carreraPref1',
            'carreraPref2',
            'resultadoFinal',
            'grupoPostulante.grupo',
        ]);

        // Obtener resultado final
        $resultado = $postulante->resultadoFinal ?? $postulante->obtenerResultadoFinal();

        // Estadísticas personales
        $notas = $postulante->notas()->get();
        $promedio = $postulante->calcularPromedio();
        $estado = $postulante->calcularEstado();

        $estadisticas = [
            'grupo' => $postulante->grupoPostulante?->grupo,
            'notas_registradas' => $notas->count(),
            'promedio' => $promedio,
            'estado' => $resultado?->estado_admision ?? $estado,
            'carrera_asignada' => $resultado?->carreraAsignada,
            'posicion_ranking' => $resultado?->ranking,
        ];

        return view('postulante.dashboard', compact('postulante', 'resultado', 'estadisticas'));
    }

    /**
     * Ver detalles de notas y desempeño
     */
    public function verNotas()
    {
        $usuario = Auth::user();
        $postulante = Postulante::where('usuario_id', $usuario->id)->firstOrFail();

        $notas = $postulante->notas()
            ->with('materia', 'docente')
            ->get()
            ->groupBy('materia_id');

        return view('postulante.notas', compact('postulante', 'notas'));
    }

    /**
     * Ver grupo y docentes asignados
     */
    public function verGrupo()
    {
        $usuario = Auth::user();
        $postulante = Postulante::where('usuario_id', $usuario->id)->firstOrFail();

        $grupo = $postulante->grupoPostulante?->grupo;

        if (!$grupo) {
            return view('postulante.sin-grupo');
        }

        $grupo->load('docentes', 'materias');

        return view('postulante.grupo', compact('postulante', 'grupo'));
    }
}
