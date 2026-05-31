<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Convocatoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ConvocatoriaController extends Controller
{
    /**
     * CU10 - Listar todas las convocatorias
     */
    public function index()
    {
        $convocatorias = Convocatoria::orderBy('fecha_inicio', 'desc')->paginate(15);
        return view('admin.convocatorias.index', compact('convocatorias'));
    }

    /**
     * CU10 - Mostrar formulario para editar convocatoria
     */
    public function edit($id)
    {
        $convocatoria = Convocatoria::findOrFail($id);

        // Solo se pueden editar convocatorias en estado PLANIFICADA o ACTIVA
        if ($convocatoria->estado === 'CONCLUIDA') {
            return redirect()->route('admin.convocatorias.index')
                ->with('error', 'No se puede editar una convocatoria CONCLUIDA.');
        }

        return view('admin.convocatorias.editar', compact('convocatoria'));
    }

    /**
     * CU10 - Actualizar convocatoria
     */
    public function update(Request $request, $id)
    {
        $convocatoria = Convocatoria::findOrFail($id);

        // No se puede editar una convocatoria CONCLUIDA
        if ($convocatoria->estado === 'CONCLUIDA') {
            return redirect()->route('admin.convocatorias.index')
                ->with('error', 'No se puede editar una convocatoria CONCLUIDA.');
        }

        $validated = $request->validate([
            'nombre'       => 'required|string|max:255',
            'fecha_inicio' => 'required|date',
            'fecha_fin'    => 'required|date|after:fecha_inicio',
            'monto_pago'   => 'required|numeric|min:0',
            'cupo_total'   => 'nullable|integer|min:1',
        ], [
            'nombre.required'       => 'El nombre es obligatorio.',
            'fecha_inicio.required' => 'La fecha de inicio es obligatoria.',
            'fecha_fin.after'       => 'La fecha de fin debe ser posterior a la de inicio.',
            'monto_pago.required'   => 'El monto de pago es obligatorio.',
            'cupo_total.min'        => 'El cupo debe ser al menos 1.',
        ]);

        // Guardar datos anteriores para el log
        $datosAnteriores = $convocatoria->only(array_keys($validated));

        // Actualizar
        $convocatoria->update($validated);

        // Registrar en log de actividad
        DB::table('log_actividad')->insert([
            'usuario_id'     => Auth::id(),
            'usuario_nombre' => Auth::user()->nombre . ' ' . Auth::user()->apellido,
            'usuario_email'  => Auth::user()->email,
            'accion'         => 'registro_actualizado',
            'descripcion'    => "Convocatoria editada: {$validated['nombre']}",
            'ip'             => request()->ip(),
            'modulo'         => 'convocatoria',
            'resultado'      => 'ok',
            'fecha_hora'     => now(),
        ]);

        return redirect()->route('admin.convocatorias.index')
            ->with('success', "✅ Convocatoria \"{$validated['nombre']}\" actualizada correctamente.");
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre'       => 'required|string|max:100',
            'fecha_inicio' => 'required|date',
            'fecha_fin'    => 'required|date|after:fecha_inicio',
            'monto_pago'   => 'required|numeric|min:0',
            'cupo_total'   => 'nullable|integer|min:1',
            'estado'       => 'required|in:ACTIVA,PLANIFICADA',
        ], [
            'nombre.required'       => 'El nombre es obligatorio.',
            'fecha_inicio.required' => 'La fecha de inicio es obligatoria.',
            'fecha_fin.required'    => 'La fecha de fin es obligatoria.',
            'fecha_fin.after'       => 'La fecha de fin debe ser posterior a la de inicio.',
            'monto_pago.required'   => 'El monto de pago es obligatorio.',
        ]);

        // Si se activa, desactivar las otras
        if ($request->estado === 'ACTIVA') {
            $yaActiva = Convocatoria::where('estado', 'ACTIVA')->exists();
            if ($yaActiva) {
                return back()
                    ->withErrors(['estado' => 'Ya existe una convocatoria ACTIVA. Primero concluye la actual antes de activar una nueva.'])
                    ->withInput();
            }
        }

        Convocatoria::create([
            'nombre'       => $request->nombre,
            'fecha_inicio' => $request->fecha_inicio,
            'fecha_fin'    => $request->fecha_fin,
            'monto_pago'   => $request->monto_pago,
            'cupo_total'   => $request->cupo_total ?? 300,
            'estado'       => $request->estado,
        ]);

        DB::table('log_actividad')->insert([
            'usuario_id'     => Auth::id(),
            'usuario_nombre' => Auth::user()->nombre . ' ' . Auth::user()->apellido,
            'usuario_email'  => Auth::user()->email,
            'accion'         => 'registro_creado',
            'descripcion'    => "Convocatoria creada: {$request->nombre} — Estado: {$request->estado}",
            'ip'             => request()->ip(),
            'modulo'         => 'convocatoria',
            'resultado'      => 'ok',
            'fecha_hora'     => now(),
        ]);

        return redirect()->route('admin.convocatorias.index')
            ->with('success', "✅ Convocatoria \"{$request->nombre}\" creada correctamente.");
    }

    public function activar($id)
    {
        $convocatoria = Convocatoria::findOrFail($id);

        // Desactivar cualquier convocatoria activa primero
        Convocatoria::where('estado', 'ACTIVA')->update(['estado' => 'PLANIFICADA']);

        $convocatoria->update(['estado' => 'ACTIVA']);

        DB::table('log_actividad')->insert([
            'usuario_id'     => Auth::id(),
            'usuario_nombre' => Auth::user()->nombre . ' ' . Auth::user()->apellido,
            'usuario_email'  => Auth::user()->email,
            'accion'         => 'registro_actualizado',
            'descripcion'    => "Convocatoria activada: {$convocatoria->nombre}",
            'ip'             => request()->ip(),
            'modulo'         => 'convocatoria',
            'resultado'      => 'ok',
            'fecha_hora'     => now(),
        ]);

        return back()->with('success', "✅ Convocatoria \"{$convocatoria->nombre}\" activada. El pre-registro ya está disponible.");
    }

    public function concluir($id)
    {
        $convocatoria = Convocatoria::findOrFail($id);
        $convocatoria->update(['estado' => 'CONCLUIDA']);

        DB::table('log_actividad')->insert([
            'usuario_id'     => Auth::id(),
            'usuario_nombre' => Auth::user()->nombre . ' ' . Auth::user()->apellido,
            'usuario_email'  => Auth::user()->email,
            'accion'         => 'registro_actualizado',
            'descripcion'    => "Convocatoria concluida: {$convocatoria->nombre}",
            'ip'             => request()->ip(),
            'modulo'         => 'convocatoria',
            'resultado'      => 'ok',
            'fecha_hora'     => now(),
        ]);

        return back()->with('success', "Convocatoria \"{$convocatoria->nombre}\" marcada como CONCLUIDA.");
    }
}