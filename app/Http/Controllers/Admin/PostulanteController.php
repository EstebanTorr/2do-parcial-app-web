<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Postulante;
use App\Models\Carrera;
use Illuminate\Http\Request;

class PostulanteController extends Controller
{
    /**
     * Listado con filtros y paginación.
     */
    public function index(Request $request)
    {
        $query = Postulante::with(['carreraPref1', 'carreraPref2', 'grupoPostulante.grupo'])
            ->orderBy('apellido')
            ->orderBy('nombre');

        // Filtro: búsqueda libre (nombre, apellido, CI, código)
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->whereRaw("LOWER(nombre || ' ' || apellido) LIKE ?", ['%'.strtolower($search).'%'])
                  ->orWhere('ci', 'like', '%'.$search.'%')
                  ->orWhere('codigo_estudiante', 'like', '%'.$search.'%')
                  ->orWhere('email', 'like', '%'.$search.'%');
            });
        }

        // Filtro: estado
        if ($estado = $request->input('estado')) {
            $query->where('estado', $estado);
        }

        // Filtro: turno
        if ($turno = $request->input('turno')) {
            $query->where('turno_asignado', $turno);
        }

        // Filtro: carrera preferida 1
        if ($carreraId = $request->input('carrera_id')) {
            $query->where('carrera_pref_1_id', $carreraId);
        }

        // Estadísticas rápidas para las tarjetas
        $stats = [
            'total'       => Postulante::count(),
            'registrados' => Postulante::where('estado', 'REGISTRADO')->count(),
            'con_pago'    => Postulante::where('estado', 'CON_PAGO')->count(),
            'retirados'   => Postulante::where('estado', 'RETIRADO')->count(),
        ];

        $postulantes = $query->paginate(15)->withQueryString();
        $carreras    = Carrera::where('activa', true)->orderBy('nombre')->get();

        return view('admin.postulantes.index', compact('postulantes', 'stats', 'carreras'));
    }

    /**
     * Vista de detalle de un postulante.
     */
    public function show(Postulante $postulante)
    {
        $postulante->load([
            'carreraPref1',
            'carreraPref2',
            'grupoPostulante.grupo',
            'pagos',
            'resultadoFinal.carreraAsignada',
            'notas.examen.materia',
        ]);

        return view('admin.postulantes.show', compact('postulante'));
    }

    /**
     * Formulario de edición.
     */
    public function edit(Postulante $postulante)
    {
        $carreras = Carrera::where('activa', true)->orderBy('nombre')->get();
        return view('admin.postulantes.edit', compact('postulante', 'carreras'));
    }

    /**
     * Actualizar datos del postulante.
     */
    public function update(Request $request, Postulante $postulante)
    {
        $validated = $request->validate([
            'nombre'           => 'required|string|max:100',
            'apellido'         => 'required|string|max:100',
            'email'            => 'required|email|max:150',
            'telefono'         => 'nullable|string|max:20',
            'direccion'        => 'nullable|string|max:200',
            'ciudad'           => 'nullable|string|max:100',
            'turno_asignado'   => 'required|in:MAÑANA,TARDE,NOCHE',
            'carrera_pref_1_id'=> 'nullable|exists:carrera,id',
            'carrera_pref_2_id'=> 'nullable|exists:carrera,id|different:carrera_pref_1_id',
            'estado'           => 'required|in:REGISTRADO,CON_PAGO,RETIRADO',
        ]);

        $postulante->update($validated);

        return redirect()
            ->route('admin.postulantes.index')
            ->with('success', 'Postulante actualizado correctamente.');
    }

    /**
     * Eliminar postulante.
     */
    public function destroy(Postulante $postulante)
    {
        $nombre = $postulante->nombre . ' ' . $postulante->apellido;
        $postulante->delete();

        return redirect()
            ->route('admin.postulantes.index')
            ->with('success', "Postulante \"{$nombre}\" eliminado.");
    }

    /**
     * Exportar lista en CSV simple.
     */
    public function export()
    {
        $postulantes = Postulante::with(['carreraPref1'])->orderBy('apellido')->get();

        $headers = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="postulantes_' . now()->format('Ymd_His') . '.csv"',
        ];

        $callback = function () use ($postulantes) {
            $handle = fopen('php://output', 'w');
            // BOM para Excel
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));

            // Cabecera
            fputcsv($handle, ['Código','Nombre','Apellido','CI','Email','Teléfono','Ciudad','Carrera','Turno','Estado']);

            foreach ($postulantes as $p) {
                fputcsv($handle, [
                    $p->codigo_estudiante,
                    $p->nombre,
                    $p->apellido,
                    $p->ci,
                    $p->email,
                    $p->telefono,
                    $p->ciudad,
                    $p->carreraPref1->nombre ?? '',
                    $p->turno_asignado,
                    $p->estado,
                ]);
            }
            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function create()
{
    $carreras = Carrera::where('activa', true)->orderBy('nombre')->get();
    return view('admin.postulantes.create', compact('carreras'));
}
}