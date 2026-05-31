<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PreRegistroController extends Controller
{
    // Lista de pre-registros
    public function index(Request $request)
    {
        $estado = $request->get('estado', 'PENDIENTE');
        $tipo   = $request->get('tipo', 'todos');

        $estudiantes = DB::table('pre_registro_estudiante')
            ->when($estado !== 'todos', fn($q) => $q->where('estado', $estado))
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($item) {
                $item->tipo     = 'ESTUDIANTE';
                $item->docs     = DB::table('doc_pre_estudiante')
                                    ->where('pre_registro_id', $item->id)
                                    ->count();
                $item->docs_req = 3;
                return $item;
            });

        $docentes = DB::table('pre_registro_docente')
            ->when($estado !== 'todos', fn($q) => $q->where('estado', $estado))
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($item) {
                $item->tipo     = 'DOCENTE';
                $item->docs     = DB::table('doc_pre_docente')
                                    ->where('pre_reg_docente_id', $item->id)
                                    ->count();
                $item->docs_req = 7;
                return $item;
            });

        $registros = $tipo === 'estudiante' ? $estudiantes
                   : ($tipo === 'docente'   ? $docentes
                   : $estudiantes->concat($docentes)->sortByDesc('created_at'));

        $conteos = [
            'pendientes' => DB::table('pre_registro_estudiante')->where('estado','PENDIENTE')->count()
                         + DB::table('pre_registro_docente')->where('estado','PENDIENTE')->count(),
            'aprobados'  => DB::table('pre_registro_estudiante')->where('estado','APROBADO')->count()
                         + DB::table('pre_registro_docente')->where('estado','APROBADO')->count(),
            'rechazados' => DB::table('pre_registro_estudiante')->where('estado','RECHAZADO')->count()
                         + DB::table('pre_registro_docente')->where('estado','RECHAZADO')->count(),
        ];

        return view('admin.pre-registros.index', compact('registros','estado','tipo','conteos'));
    }

    // Ver detalle de un pre-registro estudiante
    public function showEstudiante($id)
    {
        $pre  = DB::table('pre_registro_estudiante')->where('id', $id)->firstOrFail();
        $docs = DB::table('doc_pre_estudiante')->where('pre_registro_id', $id)->get();
        $c1   = DB::table('carrera')->where('id', $pre->carrera_pref_1_id)->first();
        $c2   = DB::table('carrera')->where('id', $pre->carrera_pref_2_id)->first();
        return view('admin.pre-registros.detalle-estudiante', compact('pre','docs','c1','c2'));
    }

    // Ver detalle de un pre-registro docente
    public function showDocente($id)
    {
        $pre      = DB::table('pre_registro_docente')->where('id', $id)->firstOrFail();
        $docs     = DB::table('doc_pre_docente')->where('pre_reg_docente_id', $id)->get();
        $materias = DB::table('materia_pre_docente as mpd')
                      ->join('materia as m', 'm.id', '=', 'mpd.materia_id')
                      ->where('mpd.pre_reg_docente_id', $id)
                      ->pluck('m.nombre');
        return view('admin.pre-registros.detalle-docente', compact('pre','docs','materias'));
    }

    // Aprobar estudiante
    public function aprobarEstudiante($id)
    {
        $pre = DB::table('pre_registro_estudiante')->where('id', $id)->first();
        if (!$pre || $pre->estado !== 'PENDIENTE') {
            return back()->with('error', 'Este registro ya fue procesado.');
        }

        // Obtener rol POSTULANTE
        $rolId = DB::table('rol')->where('nombre_rol', 'POSTULANTE')->value('id');

        // Generar código correlativo
        $conv = DB::table('convocatoria')->where('estado', 'ACTIVA')->first();
        $convId = $conv?->id;
        $correlativo = DB::table('postulante')
                         ->when($convId, fn($q) => $q->where('convocatoria_id', $convId))
                         ->count() + 1;
        $codigo = 'POST-' . date('Y') . '-' . str_pad($correlativo, 6, '0', STR_PAD_LEFT);

        // Crear usuario (contraseña = CI)
        $usuarioId = DB::table('usuario')->insertGetId([
            'nombre'     => $pre->nombre,
            'apellido'   => $pre->apellido,
            'email'      => $pre->email,
            'password'   => $pre->ci,
            'rol_id'     => $rolId,
            'activo'     => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Crear postulante
        DB::table('postulante')->insert([
            'usuario_id'        => $usuarioId,
            'convocatoria_id'   => $convId,
            'pre_registro_id'   => $pre->id,
            'codigo_estudiante' => $codigo,
            'ci'                => $pre->ci,
            'nombre'            => $pre->nombre,
            'apellido'          => $pre->apellido,
            'fecha_nacimiento'  => $pre->fecha_nacimiento,
            'sexo'              => $pre->sexo,
            'email'             => $pre->email,
            'telefono'          => $pre->telefono,
            'direccion'         => $pre->direccion,
            'ciudad'            => $pre->ciudad,
            'colegio_nombre'    => $pre->colegio_nombre,
            'carrera_pref_1_id' => $pre->carrera_pref_1_id,
            'carrera_pref_2_id' => $pre->carrera_pref_2_id,
            'turno_asignado'    => $pre->turno_preferido,
            'estado'            => 'REGISTRADO',
            'created_at'        => now(),
            'updated_at'        => now(),
        ]);

        // Guardar credencial temporal
        DB::table('credencial_temporal')->insert([
            'pre_registro_id'  => $pre->id,
            'email'            => $pre->email,
            'codigo_registro'  => $codigo,
            'contrasena_correo'=> $pre->ci,
            'correo_enviado'   => false,
            'created_at'       => now(),
        ]);

        // Actualizar estado del pre-registro
        DB::table('pre_registro_estudiante')->where('id', $id)->update([
            'estado'      => 'APROBADO',
            'revisado_por'=> Auth::id(),
            'revisado_en' => now(),
            'updated_at'  => now(),
        ]);

        // Log
        DB::table('log_actividad')->insert([
            'usuario_id'     => Auth::id(),
            'usuario_nombre' => Auth::user()->nombre . ' ' . Auth::user()->apellido,
            'usuario_email'  => Auth::user()->email,
            'accion'         => 'aprobacion',
            'descripcion'    => "Pre-registro aprobado: {$pre->nombre} {$pre->apellido}. Código: {$codigo}",
            'ip'             => request()->ip(),
            'modulo'         => 'pre_registro',
            'resultado'      => 'ok',
            'fecha_hora'     => now(),
        ]);

        return back()->with('success', "✅ Aprobado. Código: {$codigo} — Contraseña: {$pre->ci}");
    }

    // Rechazar estudiante
    public function rechazarEstudiante(Request $request, $id)
    {
        $request->validate([
            'observacion' => 'required|string|max:300',
        ], [
            'observacion.required' => 'Debes indicar el motivo del rechazo.',
        ]);

        DB::table('pre_registro_estudiante')->where('id', $id)->update([
            'estado'           => 'RECHAZADO',
            'observacion_admin'=> $request->observacion,
            'revisado_por'     => Auth::id(),
            'revisado_en'      => now(),
            'updated_at'       => now(),
        ]);

        DB::table('log_actividad')->insert([
            'usuario_id'     => Auth::id(),
            'usuario_nombre' => Auth::user()->nombre . ' ' . Auth::user()->apellido,
            'usuario_email'  => Auth::user()->email,
            'accion'         => 'rechazo',
            'descripcion'    => "Pre-registro rechazado. ID: {$id}. Motivo: {$request->observacion}",
            'ip'             => request()->ip(),
            'modulo'         => 'pre_registro',
            'resultado'      => 'ok',
            'fecha_hora'     => now(),
        ]);

        return back()->with('success', '❌ Pre-registro rechazado correctamente.');
    }

    // Rechazar docente
    public function rechazarDocente(Request $request, $id)
    {
        $request->validate([
            'observacion' => 'required|string|max:300',
        ], [
            'observacion.required' => 'Debes indicar el motivo del rechazo.',
        ]);

        DB::table('pre_registro_docente')->where('id', $id)->update([
            'estado'           => 'RECHAZADO',
            'observacion_admin'=> $request->observacion,
            'revisado_por'     => Auth::id(),
            'revisado_en'      => now(),
            'updated_at'       => now(),
        ]);

        DB::table('log_actividad')->insert([
            'usuario_id'     => Auth::id(),
            'usuario_nombre' => Auth::user()->nombre . ' ' . Auth::user()->apellido,
            'usuario_email'  => Auth::user()->email,
            'accion'         => 'rechazo',
            'descripcion'    => "Pre-registro docente rechazado. ID: {$id}. Motivo: {$request->observacion}",
            'ip'             => request()->ip(),
            'modulo'         => 'pre_registro',
            'resultado'      => 'ok',
            'fecha_hora'     => now(),
        ]);

        return back()->with('success', '❌ Pre-registro docente rechazado.');
    }
}