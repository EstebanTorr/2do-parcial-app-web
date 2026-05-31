<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Nota;
use App\Models\Grupo;
use App\Models\Postulante;
use App\Models\Materia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class NotaController extends Controller
{
    /**
     * CU14 - Listar postulantes de un grupo para calificar
     */
    public function index(Request $request)
    {
        $grupoId = $request->query('grupo_id');

        if ($grupoId) {
            $grupo = Grupo::with('postulantes')->findOrFail($grupoId);
            $postulantes = $grupo->postulantes;
        } else {
            $postulantes = collect();
            $grupo = null;
        }

        $grupos = Grupo::with('convocatoria')
            ->whereHas('postulantes')
            ->get();

        return view('admin.notas.index', compact('grupos', 'grupo', 'postulantes'));
    }

    /**
     * CU14 - Mostrar formulario de calificación para un postulante
     */
    public function create(Postulante $postulante)
    {
        $grupo = $postulante->grupoPostulante()?->grupo;

        if (!$grupo) {
            return redirect()->back()->with('error', 'El postulante no tiene grupo asignado');
        }

        $materias = Materia::where('estado', 'ACTIVA')->get();
        $notasExistentes = $postulante->notas()
            ->where('grupo_id', $grupo->id)
            ->get()
            ->keyBy('materia_id');

        return view('admin.notas.crear', compact('postulante', 'grupo', 'materias', 'notasExistentes'));
    }

    /**
     * CU14 - Guardar nota
     */
    public function store(Request $request, Postulante $postulante)
    {
        $validated = $request->validate([
            'materia_id' => 'required|exists:materia,id',
            'calificacion' => 'required|numeric|min:0|max:100',
            'tipo_evaluacion' => 'required|in:EXAMEN,ACTIVIDAD,PROYECTO,PARTICIPACION',
            'observaciones' => 'nullable|string|max:500',
        ], [
            'calificacion.required' => 'La calificación es obligatoria',
            'calificacion.min' => 'La calificación debe ser mayor o igual a 0',
            'calificacion.max' => 'La calificación no puede exceder 100',
        ]);

        $grupo = $postulante->grupoPostulante()?->grupo;

        if (!$grupo) {
            return redirect()->back()->with('error', 'El postulante no tiene grupo asignado');
        }

        // Verificar si ya existe nota para esta materia y tipo de evaluación
        $notaExistente = Nota::where('postulante_id', $postulante->id)
            ->where('materia_id', $validated['materia_id'])
            ->where('tipo_evaluacion', $validated['tipo_evaluacion'])
            ->first();

        if ($notaExistente) {
            // Actualizar
            $notaExistente->update(array_merge(
                $validated,
                ['docente_id' => Auth::id()]
            ));
            $accion = 'actualizada';
        } else {
            // Crear
            Nota::create(array_merge(
                $validated,
                [
                    'postulante_id' => $postulante->id,
                    'grupo_id' => $grupo->id,
                    'docente_id' => Auth::id(),
                ]
            ));
            $accion = 'registrada';
        }

        // Registrar en log
        DB::table('log_actividad')->insert([
            'usuario_id' => Auth::id(),
            'usuario_nombre' => Auth::user()->nombre . ' ' . Auth::user()->apellido,
            'usuario_email' => Auth::user()->email,
            'accion' => 'nota_' . $accion,
            'descripcion' => "Nota {$accion}: {$postulante->nombre_completo} - " .
                             Materia::find($validated['materia_id'])->nombre . " - {$validated['calificacion']}",
            'ip' => request()->ip(),
            'modulo' => 'notas',
            'resultado' => 'ok',
            'fecha_hora' => now(),
        ]);

        return redirect()->route('admin.notas.create', $postulante)
            ->with('success', "Nota {$accion} exitosamente");
    }

    /**
     * CU14 - Ver notas de un postulante
     */
    public function show(Postulante $postulante)
    {
        $grupo = $postulante->grupoPostulante()?->grupo;
        $notas = $postulante->notas()
            ->when($grupo, fn($q) => $q->where('grupo_id', $grupo->id))
            ->with('materia')
            ->get();

        return view('admin.notas.show', compact('postulante', 'grupo', 'notas'));
    }

    /**
     * CU14 - Eliminar nota
     */
    public function destroy(Nota $nota)
    {
        $postulante = $nota->postulante;
        $materia = $nota->materia->nombre;

        $nota->delete();

        DB::table('log_actividad')->insert([
            'usuario_id' => Auth::id(),
            'usuario_nombre' => Auth::user()->nombre . ' ' . Auth::user()->apellido,
            'usuario_email' => Auth::user()->email,
            'accion' => 'nota_eliminada',
            'descripcion' => "Nota eliminada: {$postulante->nombre_completo} - {$materia}",
            'ip' => request()->ip(),
            'modulo' => 'notas',
            'resultado' => 'ok',
            'fecha_hora' => now(),
        ]);

        return redirect()->back()->with('success', 'Nota eliminada');
    }
}
