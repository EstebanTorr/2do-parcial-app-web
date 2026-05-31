<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PreRegistroEstudianteController extends Controller
{
    /**
     * Muestra el formulario público de pre-registro.
     */
    public function create()
    {
        // Convocatoria activa
        $convocatoria = DB::table('convocatoria')
            ->where('estado', 'ACTIVA')
            ->first();

        // Carreras disponibles
        $carreras = DB::table('carrera')
            ->where('activa', true)
            ->orderBy('nombre')
            ->get();

        return view('pre-registro.estudiante', compact('convocatoria', 'carreras'));
    }

    /**
     * Procesa y guarda el pre-registro.
     */
    public function store(Request $request)
    {
        // ── BLOQUEO: sin convocatoria activa no se puede registrar ──
        $convActiva = DB::table('convocatoria')->where('estado', 'ACTIVA')->first();
        if (!$convActiva) {
            return back()
                ->withErrors(['general' => 'No hay convocatoria activa. El pre-registro está cerrado temporalmente.'])
                ->withInput();
        }

        // ── VALIDACIÓN ──────────────────────────────────────────
        $request->validate([
            'nombre'          => 'required|string|max:100',
            'apellido'        => 'required|string|max:100',
            'ci'              => 'required|string|max:20',
            'ci_extension'    => 'required|string|max:10',
            'fecha_nacimiento'=> 'required|date|before:today',
            'sexo'            => 'required|in:M,F,OTRO',
            'direccion'       => 'nullable|string|max:200',
            'ciudad'          => 'required|string|max:100',
            'telefono'        => 'nullable|string|max:20',
            'email'           => 'required|email|max:150',
            'colegio_nombre'  => 'required|string|max:150',
            'colegio_tipo'    => 'required|in:FISCAL,PARTICULAR,CONVENIO',
            'anio_egreso'     => 'required|integer|min:2000|max:' . date('Y'),
            'carrera_pref_1_id' => 'required|exists:carrera,id',
            'carrera_pref_2_id' => 'required|exists:carrera,id|different:carrera_pref_1_id',
            'turno_preferido' => 'required|in:MAÑANA,TARDE,NOCHE',
            // Documentos
            'doc_ci'           => 'required|file|mimes:pdf,jpg,jpeg,png|max:3072',
            'doc_titulo'       => 'required|file|mimes:pdf,jpg,jpeg,png|max:3072',
            'doc_boleta'       => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:3072',
            'doc_pago'         => 'required|file|mimes:pdf,jpg,jpeg,png|max:3072',
        ], [
            'nombre.required'           => 'El nombre es obligatorio.',
            'apellido.required'         => 'El apellido es obligatorio.',
            'ci.required'               => 'El CI es obligatorio.',
            'ci_extension.required'     => 'La extensión del CI es obligatoria.',
            'fecha_nacimiento.required' => 'La fecha de nacimiento es obligatoria.',
            'fecha_nacimiento.before'   => 'La fecha de nacimiento debe ser anterior a hoy.',
            'sexo.required'             => 'El sexo es obligatorio.',
            'ciudad.required'           => 'La ciudad es obligatoria.',
            'email.required'            => 'El correo electrónico es obligatorio.',
            'email.email'               => 'El correo electrónico no es válido.',
            'colegio_nombre.required'   => 'El nombre del colegio es obligatorio.',
            'colegio_tipo.required'     => 'El tipo de colegio es obligatorio.',
            'anio_egreso.required'      => 'El año de egreso es obligatorio.',
            'carrera_pref_1_id.required'=> 'Debes elegir tu primera carrera de preferencia.',
            'carrera_pref_2_id.required'=> 'Debes elegir tu segunda carrera de preferencia.',
            'carrera_pref_2_id.different'=> 'Las dos carreras deben ser diferentes.',
            'turno_preferido.required'  => 'El turno preferido es obligatorio.',
            'doc_ci.required'           => 'Debes subir tu CI escaneado.',
            'doc_ci.mimes'              => 'El CI debe ser PDF, JPG o PNG.',
            'doc_ci.max'                => 'El CI no debe superar 3 MB.',
            'doc_titulo.required'       => 'Debes subir tu Título de Bachiller.',
            'doc_titulo.mimes'          => 'El Título debe ser PDF, JPG o PNG.',
            'doc_titulo.max'            => 'El Título no debe superar 3 MB.',
            'doc_pago.required'         => 'Debes subir el comprobante de pago.',
            'doc_pago.mimes'            => 'El comprobante debe ser PDF, JPG o PNG.',
            'doc_pago.max'              => 'El comprobante no debe superar 3 MB.',
        ]);

        // ── VERIFICAR CI DUPLICADO ───────────────────────────────
        $convocatoria = DB::table('convocatoria')->where('estado', 'ACTIVA')->first();
        $convId = $convocatoria?->id;

        $existe = DB::table('pre_registro_estudiante')
            ->where('ci', $request->ci)
            ->where('ci_extension', $request->ci_extension)
            ->when($convId, fn($q) => $q->where('convocatoria_id', $convId))
            ->exists();

        if ($existe) {
            return back()
                ->withErrors(['ci' => 'Ya existe un pre-registro con ese CI en esta convocatoria.'])
                ->withInput();
        }

        // ── GUARDAR DOCUMENTOS ───────────────────────────────────
        $docs = [];
        foreach (['doc_ci' => 'CI', 'doc_titulo' => 'TITULO_BACHILLER', 'doc_boleta' => 'BOLETA_COLEGIO', 'doc_pago' => 'COMPROBANTE_PAGO'] as $field => $tipo) {
            if ($request->hasFile($field)) {
                $archivo = $request->file($field);
                $nombre  = time() . '_' . $tipo . '.' . $archivo->getClientOriginalExtension();
                $ruta    = $archivo->storeAs("pre_registro/estudiantes/{$request->ci}", $nombre, 'public');
                $docs[$tipo] = [
                    'nombre_archivo' => $nombre,
                    'ruta_servidor'  => $ruta,
                    'mime_type'      => $archivo->getMimeType(),
                    'tamanio_bytes'  => $archivo->getSize(),
                ];
            }
        }

        // ── INSERTAR PRE-REGISTRO ────────────────────────────────
        $preId = DB::table('pre_registro_estudiante')->insertGetId([
            'convocatoria_id'   => $convId,
            'nombre'            => strtoupper(trim($request->nombre)),
            'apellido'          => strtoupper(trim($request->apellido)),
            'ci'                => trim($request->ci),
            'ci_extension'      => strtoupper(trim($request->ci_extension)),
            'fecha_nacimiento'  => $request->fecha_nacimiento,
            'sexo'              => $request->sexo,
            'direccion'         => $request->direccion,
            'ciudad'            => strtoupper(trim($request->ciudad)),
            'telefono'          => $request->telefono,
            'email'             => strtolower(trim($request->email)),
            'colegio_nombre'    => strtoupper(trim($request->colegio_nombre)),
            'colegio_tipo'      => $request->colegio_tipo,
            'anio_egreso'       => $request->anio_egreso,
            'carrera_pref_1_id' => $request->carrera_pref_1_id,
            'carrera_pref_2_id' => $request->carrera_pref_2_id,
            'turno_preferido'   => $request->turno_preferido,
            'estado'            => 'PENDIENTE',
            'ip_registro'       => $request->ip(),
            'created_at'        => now(),
            'updated_at'        => now(),
        ]);

        // ── INSERTAR DOCUMENTOS ──────────────────────────────────
        foreach ($docs as $tipo => $info) {
            DB::table('doc_pre_estudiante')->insert([
                'pre_registro_id' => $preId,
                'tipo'            => $tipo,
                'nombre_archivo'  => $info['nombre_archivo'],
                'ruta_servidor'   => $info['ruta_servidor'],
                'mime_type'       => $info['mime_type'],
                'tamanio_bytes'   => $info['tamanio_bytes'],
                'created_at'      => now(),
            ]);
        }

        // ── REDIRIGIR CON ÉXITO ──────────────────────────────────
        return redirect()->route('pre-registro.estudiante.exito', [
            'nombre' => strtoupper($request->nombre),
            'ci'     => $request->ci,
        ]);
    }

    /**
     * Pantalla de éxito tras el registro.
     */
    public function exito(Request $request)
    {
        return view('pre-registro.estudiante-exito', [
            'nombre' => $request->query('nombre', 'Postulante'),
            'ci'     => $request->query('ci', '—'),
        ]);
    }
}