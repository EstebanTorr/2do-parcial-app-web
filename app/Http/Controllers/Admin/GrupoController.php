<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Grupo;
use App\Models\Convocatoria;
use App\Models\Postulante;
use App\Models\Docente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * GrupoController
 *
 * Controlador responsable de gestionar los grupos académicos del CUP.
 * Cubre los casos de uso:
 *   - CU12: Listar grupos, asignar/desasignar postulantes APROBADOS y docentes ACTIVOS.
 *   - CU13: Consultar docentes por grupo (endpoint API).
 *   - Generar grupos automáticamente para una convocatoria.
 *   - Limpiar (eliminar) todos los grupos de una convocatoria.
 *   - Distribución automática de postulantes en grupos disponibles.
 *
 * Reglas de negocio clave:
 *   - Solo pueden asignarse postulantes con estado "APROBADO".
 *   - Solo pueden asignarse docentes con estado "ACTIVO".
 *   - La capacidad máxima por grupo es de 70 estudiantes (reglamento CUP).
 *   - Un postulante solo puede pertenecer a un grupo por convocatoria.
 */
class GrupoController extends Controller
{
    // =========================================================================
    // LISTADO Y DETALLE
    // =========================================================================

    /**
     * CU12 — Listar los grupos de una convocatoria.
     *
     * Muestra la pantalla principal de gestión de grupos. Si se pasa
     * el parámetro "convocatoria_id" en la URL, carga esa convocatoria;
     * de lo contrario, busca automáticamente la convocatoria con estado
     * "ACTIVA". Junto con los grupos, carga sus postulantes y docentes
     * relacionados para mostrar estadísticas en la vista.
     *
     * @param  Request $request  Objeto HTTP con posible query param "convocatoria_id".
     * @return \Illuminate\View\View  Vista "admin.grupos.index" con los grupos y convocatorias.
     */
    public function index(Request $request)
    {
        // Leer el filtro de convocatoria desde la URL (?convocatoria_id=X)
        $convocatoriaId = $request->query('convocatoria_id');

        if ($convocatoriaId) {
            // Si se especificó una convocatoria concreta, cargarla junto con sus grupos
            $convocatoria = Convocatoria::findOrFail($convocatoriaId);
            $grupos = $convocatoria->grupos()->with(['postulantes', 'docentes'])->get();
        } else {
            // Si no se filtró, mostrar la convocatoria que esté en estado ACTIVA
            $convocatoria = Convocatoria::where('estado', 'ACTIVA')->first();
            $grupos = $convocatoria ? $convocatoria->grupos()->with(['postulantes', 'docentes'])->get() : [];
        }

        // Obtener todas las convocatorias ACTIVAS o PLANIFICADAS para el selector del header
        $convocatorias = Convocatoria::whereIn('estado', ['ACTIVA', 'PLANIFICADA'])->get();

        return view('admin.grupos.index', compact('grupos', 'convocatoria', 'convocatorias', 'convocatoriaId'));
    }

    /**
     * CU12 — Mostrar el detalle de un grupo con sus postulantes y docentes asignados.
     *
     * Carga toda la información del grupo para la vista de gestión individual:
     *   - Lista de postulantes ya asignados al grupo.
     *   - Lista de postulantes APROBADOS de la convocatoria que AÚN no tienen grupo
     *     (disponibles para asignar).
     *   - Lista de docentes ACTIVOS que no están asignados todavía a este grupo.
     *   - Estadísticas globales de la convocatoria: total de aprobados y cuántos
     *     ya tienen grupo asignado.
     *
     * @param  Grupo $grupo  Instancia del grupo resuelto por Route Model Binding.
     * @return \Illuminate\View\View  Vista "admin.grupos.show" con todos los datos necesarios.
     */
    public function show(Grupo $grupo)
    {
        // Cargar relaciones del grupo para evitar N+1 queries en la vista
        $grupo->load(['postulantes', 'docentes', 'convocatoria']);

        // Obtener todos los IDs de postulantes que ya tienen algún grupo en toda la BD,
        // para excluirlos de la lista de disponibles (un postulante solo va a un grupo)
        $postulanteYaAsignados = DB::table('grupo_postulante')->pluck('postulante_id');

        // Postulantes disponibles: deben ser APROBADOS, pertenecer a esta convocatoria
        // y no tener grupo asignado aún. Se ordenan alfabéticamente.
        $postulantesSinGrupo = Postulante::where('convocatoria_id', $grupo->convocatoria_id)
            ->where('estado', 'APROBADO')
            ->whereNotIn('id', $postulanteYaAsignados)
            ->orderBy('apellido')
            ->orderBy('nombre')
            ->get();

        // Docentes disponibles: deben estar ACTIVOS y no estar ya en este grupo
        $docentesDisponibles = Docente::where('estado', 'ACTIVO')
            ->whereNotIn('id', $grupo->docentes()->pluck('docente_id'))
            ->orderBy('apellido')
            ->orderBy('nombre')
            ->get();

        // Estadística 1: total de postulantes APROBADOS en esta convocatoria
        $totalAprobados = Postulante::where('convocatoria_id', $grupo->convocatoria_id)
            ->where('estado', 'APROBADO')->count();

        // Estadística 2: cuántos de esos aprobados ya tienen grupo en esta convocatoria
        $totalAsignados = DB::table('grupo_postulante')
            ->join('grupo', 'grupo.id', '=', 'grupo_postulante.grupo_id')
            ->where('grupo.convocatoria_id', $grupo->convocatoria_id)
            ->count();

        return view('admin.grupos.show', compact(
            'grupo',
            'postulantesSinGrupo',
            'docentesDisponibles',
            'totalAprobados',
            'totalAsignados'
        ));
    }

    // =========================================================================
    // ASIGNACIÓN DE POSTULANTES
    // =========================================================================

    /**
     * CU12 — Asignar un postulante APROBADO a un grupo.
     *
     * Realiza todas las validaciones del caso de uso antes de persistir la asignación:
     *   1. El campo postulante_id debe existir en la tabla "postulante".
     *   2. El postulante debe tener estado "APROBADO" (precondición del CU).
     *   3. El postulante debe pertenecer a la misma convocatoria que el grupo.
     *   4. El postulante NO debe estar ya asignado a ningún otro grupo.
     *   5. El grupo no debe haber superado la capacidad máxima permitida (mín entre
     *      capacidad_maxima del grupo y el límite reglamentario de 70 estudiantes).
     * Si todas las validaciones pasan, se crea la relación en "grupo_postulante"
     * y se registra la acción en el log de actividad.
     *
     * @param  Request $request  Contiene "postulante_id" (integer, requerido).
     * @param  Grupo   $grupo    Grupo destino resuelto por Route Model Binding.
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     *         Redirige con mensaje de éxito/error, o JSON si la petición lo pide.
     */
    public function asignarPostulante(Request $request, Grupo $grupo)
    {
        // Validar que se envió un ID de postulante existente en la BD
        $validated = $request->validate([
            'postulante_id' => 'required|exists:postulante,id',
        ]);

        // Cargar el postulante para acceder a sus atributos (estado, convocatoria_id, etc.)
        $postulante = Postulante::findOrFail($validated['postulante_id']);

        // Regla 1: El postulante debe estar APROBADO para poder ingresar a un grupo
        if ($postulante->estado !== 'APROBADO') {
            $msg = "El postulante {$postulante->nombre_completo} no tiene estado APROBADO (estado actual: {$postulante->estado}). Solo se pueden asignar postulantes aprobados.";
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => $msg], 422);
            }
            return back()->with('error', $msg);
        }

        // Regla 2: El postulante debe pertenecer a la convocatoria del grupo
        if ($postulante->convocatoria_id !== $grupo->convocatoria_id) {
            $msg = 'El postulante no pertenece a la convocatoria de este grupo.';
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => $msg], 422);
            }
            return back()->with('error', $msg);
        }

        // Regla 3: Un postulante no puede estar en más de un grupo simultáneamente
        $yaEnGrupo = DB::table('grupo_postulante')->where('postulante_id', $postulante->id)->exists();
        if ($yaEnGrupo) {
            $msg = "El postulante {$postulante->nombre_completo} ya está asignado a un grupo.";
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => $msg], 409);
            }
            return back()->with('error', $msg);
        }

        // Regla 4: La capacidad efectiva es el mínimo entre la capacidad del grupo
        // y el límite máximo reglamentario del CUP (70 estudiantes por grupo)
        $capacidadEfectiva = min($grupo->capacidad_maxima, 70);
        $ocupados = $grupo->postulantes()->count();
        if ($ocupados >= $capacidadEfectiva) {
            $msg = "El grupo {$grupo->numero_grupo} ha alcanzado su capacidad máxima de {$capacidadEfectiva} estudiantes. Libera cupos antes de continuar.";
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => $msg], 409);
            }
            return back()->with('error', $msg);
        }

        // Todas las validaciones pasaron: crear la relación en la tabla pivot grupo_postulante
        $grupo->postulantes()->attach($postulante->id);

        // Registrar la acción en el log de auditoría del sistema
        DB::table('log_actividad')->insert([
            'usuario_id'     => Auth::id(),
            'usuario_nombre' => Auth::user()->nombre . ' ' . Auth::user()->apellido,
            'usuario_email'  => Auth::user()->email,
            'accion'         => 'asignacion_creada',
            'descripcion'    => "Postulante {$postulante->nombre_completo} (CI: {$postulante->ci}) asignado al grupo {$grupo->numero_grupo}",
            'ip'             => $request->ip(),
            'modulo'         => 'grupos',
            'resultado'      => 'ok',
            'fecha_hora'     => now(),
        ]);

        $msg = "Postulante {$postulante->nombre_completo} asignado al Grupo {$grupo->numero_grupo} exitosamente.";
        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'message' => $msg]);
        }
        return back()->with('success', $msg);
    }

    /**
     * CU12 — Desasignar (remover) un postulante de un grupo.
     *
     * Elimina la relación entre el postulante y el grupo en la tabla pivot
     * "grupo_postulante". No modifica el estado del postulante, solo lo libera
     * del grupo para que pueda ser reasignado o dejado sin grupo. Registra la
     * acción en el log de actividad.
     *
     * @param  Request $request  Contiene "postulante_id" (integer, requerido).
     * @param  Grupo   $grupo    Grupo del que se desasignará el postulante.
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function desasignarPostulante(Request $request, Grupo $grupo)
    {
        // Validar que el postulante exista en la BD antes de intentar desasignarlo
        $validated = $request->validate([
            'postulante_id' => 'required|exists:postulante,id',
        ]);

        // Eliminar el registro de la tabla pivot grupo_postulante
        $grupo->postulantes()->detach($validated['postulante_id']);

        // Registrar la desasignación en el log de auditoría
        DB::table('log_actividad')->insert([
            'usuario_id'     => Auth::id(),
            'usuario_nombre' => Auth::user()->nombre . ' ' . Auth::user()->apellido,
            'usuario_email'  => Auth::user()->email,
            'accion'         => 'asignacion_eliminada',
            'descripcion'    => "Postulante desasignado del grupo {$grupo->numero_grupo}",
            'ip'             => request()->ip(),
            'modulo'         => 'grupos',
            'resultado'      => 'ok',
            'fecha_hora'     => now(),
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Postulante desasignado',
            ]);
        }
        return back()->with('success', 'Postulante desasignado exitosamente');
    }

    // =========================================================================
    // ASIGNACIÓN DE DOCENTES
    // =========================================================================

    /**
     * CU12 — Asignar un docente ACTIVO a un grupo.
     *
     * Verifica las precondiciones antes de crear la relación:
     *   1. El campo docente_id debe existir en la tabla "docente".
     *   2. El docente debe tener estado "ACTIVO" (contratado y disponible).
     *   3. El docente no debe estar ya asignado a este mismo grupo.
     * Si todo es correcto, se inserta el registro en la tabla pivot "grupo_docente"
     * y se deja constancia en el log de actividad.
     *
     * @param  Request $request  Contiene "docente_id" (integer, requerido).
     * @param  Grupo   $grupo    Grupo destino resuelto por Route Model Binding.
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function asignarDocente(Request $request, Grupo $grupo)
    {
        // Validar que el docente_id enviado exista en la base de datos
        $validated = $request->validate([
            'docente_id' => 'required|exists:docente,id',
        ]);

        // Cargar el docente para verificar su estado antes de asignarlo
        $docente = Docente::findOrFail($validated['docente_id']);

        // Regla 1: Solo docentes con estado ACTIVO pueden ser asignados a grupos.
        // Docentes en LICENCIA o INACTIVO no pueden impartir clases.
        if ($docente->estado !== 'ACTIVO') {
            $msg = "El docente {$docente->nombre_completo} no está disponible (estado: {$docente->estado}). Solo se pueden asignar docentes con estado ACTIVO.";
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => $msg], 422);
            }
            return back()->with('error', $msg);
        }

        // Regla 2: El docente no puede estar asignado dos veces al mismo grupo
        if ($grupo->docentes()->where('docente_id', $docente->id)->exists()) {
            $msg = "El docente {$docente->nombre_completo} ya está asignado al Grupo {$grupo->numero_grupo}.";
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => $msg], 409);
            }
            return back()->with('error', $msg);
        }

        // Crear la relación en la tabla pivot grupo_docente
        $grupo->docentes()->attach($docente->id);

        // Registrar la asignación en el log de auditoría
        DB::table('log_actividad')->insert([
            'usuario_id'     => Auth::id(),
            'usuario_nombre' => Auth::user()->nombre . ' ' . Auth::user()->apellido,
            'usuario_email'  => Auth::user()->email,
            'accion'         => 'asignacion_creada',
            'descripcion'    => "Docente {$docente->nombre_completo} asignado al grupo {$grupo->numero_grupo}",
            'ip'             => $request->ip(),
            'modulo'         => 'grupos',
            'resultado'      => 'ok',
            'fecha_hora'     => now(),
        ]);

        $msg = "Docente {$docente->nombre_completo} asignado al Grupo {$grupo->numero_grupo} exitosamente.";
        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'message' => $msg]);
        }
        return back()->with('success', $msg);
    }

    /**
     * CU12 — Desasignar (remover) un docente de un grupo.
     *
     * Elimina la relación entre el docente y el grupo en la tabla pivot
     * "grupo_docente". El docente sigue existiendo en el sistema y puede
     * ser asignado nuevamente. Registra la operación en el log de actividad.
     *
     * @param  Request $request  Contiene "docente_id" (integer, requerido).
     * @param  Grupo   $grupo    Grupo del que se desasignará el docente.
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function desasignarDocente(Request $request, Grupo $grupo)
    {
        // Validar que el docente_id exista en la BD antes de desasignar
        $validated = $request->validate([
            'docente_id' => 'required|exists:docente,id',
        ]);

        // Eliminar el registro de la tabla pivot grupo_docente
        $grupo->docentes()->detach($validated['docente_id']);

        // Registrar la desasignación en el log de auditoría
        DB::table('log_actividad')->insert([
            'usuario_id'     => Auth::id(),
            'usuario_nombre' => Auth::user()->nombre . ' ' . Auth::user()->apellido,
            'usuario_email'  => Auth::user()->email,
            'accion'         => 'asignacion_eliminada',
            'descripcion'    => "Docente desasignado del grupo {$grupo->numero_grupo}",
            'ip'             => request()->ip(),
            'modulo'         => 'grupos',
            'resultado'      => 'ok',
            'fecha_hora'     => now(),
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Docente desasignado',
            ]);
        }
        return back()->with('success', 'Docente desasignado exitosamente');
    }

    // =========================================================================
    // API / CONSULTAS
    // =========================================================================

    /**
     * CU13 — Obtener la lista de docentes asignados a un grupo (endpoint API).
     *
     * Devuelve en formato JSON los docentes vinculados a un grupo específico.
     * Este endpoint es utilizado por scripts del frontend para consultar
     * dinámicamente los docentes de un grupo sin recargar la página.
     *
     * @param  Grupo $grupo  Grupo consultado resuelto por Route Model Binding.
     * @return \Illuminate\Http\JsonResponse  JSON con número de grupo y lista de docentes.
     */
    public function docentesPorGrupo(Grupo $grupo)
    {
        // Obtener los docentes relacionados con este grupo
        $docentes = $grupo->docentes()->get();

        // Retornar la información formateada en JSON
        return response()->json([
            'grupo'    => $grupo->numero_grupo,
            'docentes' => $docentes->map(fn($d) => [
                'id'          => $d->id,
                'nombre'      => $d->nombre . ' ' . $d->apellido,
                'email'       => $d->email,
                'especialidad'=> $d->especialidad,
            ]),
        ]);
    }

    // =========================================================================
    // GENERACIÓN Y LIMPIEZA DE GRUPOS
    // =========================================================================

    /**
     * Generar grupos académicos automáticamente para una convocatoria.
     *
     * Crea N grupos de una sola vez para la convocatoria indicada, todos
     * con el mismo turno y capacidad configurados en el formulario. Evita
     * crear grupos si la convocatoria ya tiene grupos para no duplicarlos.
     * Al finalizar, registra la creación masiva en el log de actividad.
     *
     * Parámetros del formulario (POST):
     *   - convocatoria_id : ID de la convocatoria (requerido, debe existir).
     *   - cantidad        : Número de grupos a crear (entre 1 y 20).
     *   - capacidad       : Capacidad máxima por grupo (entre 5 y 100).
     *   - turno           : Turno de los grupos (MAÑANA, TARDE o NOCHE).
     *
     * @param  Request $request  Datos del formulario de generación.
     * @return \Illuminate\Http\RedirectResponse  Redirige con mensaje de éxito o error.
     */
    public function generar(Request $request)
    {
        // Validar todos los campos del formulario de generación
        $validated = $request->validate([
            'convocatoria_id' => 'required|exists:convocatoria,id',
            'cantidad'        => 'required|integer|min:1|max:20',
            'capacidad'       => 'required|integer|min:5|max:100',
            'turno'           => 'required|string|in:MAÑANA,TARDE,NOCHE',
        ]);

        $convocatoria = Convocatoria::findOrFail($validated['convocatoria_id']);

        // Precaución: si ya existen grupos, evitar duplicarlos
        if ($convocatoria->grupos()->exists()) {
            return back()->with('error', 'Esta convocatoria ya tiene grupos creados. Límpialos primero si deseas regenerarlos.');
        }

        // Crear la cantidad de grupos solicitada en secuencia (Grupo 1, Grupo 2, ...)
        for ($i = 1; $i <= $validated['cantidad']; $i++) {
            Grupo::create([
                'convocatoria_id' => $convocatoria->id,
                'numero_grupo'    => $i,
                'turno'           => $validated['turno'],
                'estado'          => 'ACTIVO',
                'capacidad_maxima'=> $validated['capacidad'],
                'descripcion'     => "Grupo {$i} - {$convocatoria->nombre}",
            ]);
        }

        // Registrar la creación masiva en el log de auditoría
        DB::table('log_actividad')->insert([
            'usuario_id'     => Auth::id(),
            'usuario_nombre' => Auth::user()->nombre . ' ' . Auth::user()->apellido,
            'usuario_email'  => Auth::user()->email,
            'accion'         => 'grupo_creado',
            'descripcion'    => "Creados automáticamente {$validated['cantidad']} grupos para la convocatoria {$convocatoria->nombre}",
            'ip'             => $request->ip(),
            'modulo'         => 'grupos',
            'resultado'      => 'ok',
            'fecha_hora'     => now(),
        ]);

        return back()->with('success', "¡Se crearon {$validated['cantidad']} grupos exitosamente!");
    }

    /**
     * Eliminar todos los grupos de una convocatoria y sus asignaciones.
     *
     * Operación destructiva que borra en cascada:
     *   1. Los registros de "grupo_postulante" (postulantes asignados).
     *   2. Los registros de "grupo_docente" (docentes asignados).
     *   3. Las notas de los postulantes que estaban en esos grupos.
     *   4. Los propios registros de grupo.
     * Si no hay grupos, retorna error. Al finalizar, registra en el log.
     *
     * @param  Request $request  Contiene "convocatoria_id" (requerido).
     * @return \Illuminate\Http\RedirectResponse  Redirige con mensaje de resultado.
     */
    public function limpiar(Request $request)
    {
        // Validar que se envió un ID de convocatoria válido
        $validated = $request->validate([
            'convocatoria_id' => 'required|exists:convocatoria,id',
        ]);

        $convocatoria = Convocatoria::findOrFail($validated['convocatoria_id']);

        // Obtener los IDs de todos los grupos de esta convocatoria
        $gruposIds = $convocatoria->grupos()->pluck('id');

        // Verificar que existan grupos para limpiar
        if ($gruposIds->isEmpty()) {
            return back()->with('error', 'No hay grupos creados para esta convocatoria.');
        }

        // Paso 1: Guardar IDs de postulantes afectados antes de borrar relaciones
        // (necesarios para borrar las notas asociadas en el paso 3)
        $postulantesIds = DB::table('grupo_postulante')->whereIn('grupo_id', $gruposIds)->pluck('postulante_id');

        // Paso 2: Eliminar todas las asignaciones de postulantes a estos grupos
        DB::table('grupo_postulante')->whereIn('grupo_id', $gruposIds)->delete();

        // Paso 3: Eliminar todas las asignaciones de docentes a estos grupos
        DB::table('grupo_docente')->whereIn('grupo_id', $gruposIds)->delete();

        // Paso 4: Eliminar notas de los postulantes que estaban en los grupos borrados
        if ($postulantesIds->isNotEmpty()) {
            DB::table('nota')->whereIn('postulante_id', $postulantesIds)->delete();
        }

        // Paso 5: Eliminar los grupos en sí
        $convocatoria->grupos()->delete();

        // Registrar la operación de limpieza en el log de auditoría
        DB::table('log_actividad')->insert([
            'usuario_id'     => Auth::id(),
            'usuario_nombre' => Auth::user()->nombre . ' ' . Auth::user()->apellido,
            'usuario_email'  => Auth::user()->email,
            'accion'         => 'grupo_eliminado',
            'descripcion'    => "Se eliminaron todos los grupos y sus asignaciones de la convocatoria {$convocatoria->nombre}",
            'ip'             => $request->ip(),
            'modulo'         => 'grupos',
            'resultado'      => 'ok',
            'fecha_hora'     => now(),
        ]);

        return back()->with('success', 'Se eliminaron todos los grupos y asignaciones correctamente.');
    }

    // =========================================================================
    // DISTRIBUCIÓN AUTOMÁTICA
    // =========================================================================

    /**
     * Distribución automática de postulantes APROBADOS en grupos disponibles.
     *
     * Recorre todos los postulantes con estado "APROBADO" que aún no tienen
     * grupo asignado en la convocatoria indicada, y los asigna uno a uno al
     * primer grupo activo que tenga cupo disponible (algoritmo greedy/secuencial).
     *
     * Reglas aplicadas:
     *   - Solo se distribuyen postulantes con estado "APROBADO".
     *   - Respeta la capacidad efectiva de cada grupo: min(capacidad_maxima, 70).
     *   - Si un grupo está lleno, pasa al siguiente.
     *   - Si ningún grupo tiene cupo, el postulante queda sin asignar.
     *   - Al finalizar informa cuántos se asignaron y cuántos no pudieron asignarse.
     *
     * @param  Request $request  Contiene "convocatoria_id" (requerido).
     * @return \Illuminate\Http\RedirectResponse  Redirige con resumen del proceso.
     */
    public function autoAsignar(Request $request)
    {
        // Validar que se envió un ID de convocatoria válido
        $validated = $request->validate([
            'convocatoria_id' => 'required|exists:convocatoria,id',
        ]);

        $convocatoria = Convocatoria::findOrFail($validated['convocatoria_id']);

        // Obtener todos los grupos ACTIVOS de la convocatoria para distribuir postulantes
        $grupos = $convocatoria->grupos()->where('activo', true)->get();

        // Precondición: debe haber al menos un grupo activo donde asignar
        if ($grupos->isEmpty()) {
            return back()->with('error', 'Debes crear grupos activos primero para poder realizar la asignación automática.');
        }

        // Obtener los IDs de postulantes que ya tienen grupo (para excluirlos)
        $postulantesAsignadosIds = DB::table('grupo_postulante')->pluck('postulante_id');

        // Filtrar: solo postulantes APROBADOS sin grupo, ordenados alfabéticamente
        $postulantesSinGrupo = Postulante::where('convocatoria_id', $convocatoria->id)
            ->where('estado', 'APROBADO')
            ->whereNotIn('id', $postulantesAsignadosIds)
            ->orderBy('apellido')
            ->orderBy('nombre')
            ->get();

        // Verificar que haya postulantes para distribuir
        if ($postulantesSinGrupo->isEmpty()) {
            return back()->with('error', 'No hay postulantes APROBADOS sin grupo para asignar.');
        }

        // Contadores para el informe final
        $asignadosCount  = 0; // postulantes asignados exitosamente
        $noAsignadoCount = 0; // postulantes que no encontraron cupo

        // Algoritmo greedy: por cada postulante, buscar el primer grupo con cupo
        foreach ($postulantesSinGrupo as $postulante) {
            $asignado = false;

            foreach ($grupos as $grupo) {
                // Calcular capacidad efectiva respetando el límite CUP de 70
                $capacidadEfectiva = min($grupo->capacidad_maxima, 70);
                $actual = $grupo->postulantes()->count();

                // Si el grupo tiene cupo, asignar el postulante y continuar con el siguiente
                if ($actual < $capacidadEfectiva) {
                    $grupo->postulantes()->attach($postulante->id);
                    $asignadosCount++;
                    $asignado = true;
                    break; // Pasar al siguiente postulante
                }
            }

            // Si ningún grupo pudo recibirlo, contarlo como no asignado
            if (!$asignado) {
                $noAsignadoCount++;
            }
        }

        // Si al menos uno fue asignado, registrar en log y retornar éxito
        if ($asignadosCount > 0) {
            DB::table('log_actividad')->insert([
                'usuario_id'     => Auth::id(),
                'usuario_nombre' => Auth::user()->nombre . ' ' . Auth::user()->apellido,
                'usuario_email'  => Auth::user()->email,
                'accion'         => 'asignacion_masiva',
                'descripcion'    => "Distribución automática: {$asignadosCount} postulantes APROBADOS distribuidos en grupos de la convocatoria {$convocatoria->nombre}",
                'ip'             => $request->ip(),
                'modulo'         => 'grupos',
                'resultado'      => 'ok',
                'fecha_hora'     => now(),
            ]);

            // Construir mensaje de resumen informativo
            $mensaje = "Se distribuyeron {$asignadosCount} postulantes aprobados exitosamente.";
            if ($noAsignadoCount > 0) {
                $mensaje .= " {$noAsignadoCount} postulante(s) no pudieron asignarse por falta de cupo (capacidad máxima 70 por grupo).";
            }
            return back()->with('success', $mensaje);
        }

        // Si ninguno pudo asignarse, informar el problema
        return back()->with('error', 'No se pudieron asignar postulantes. Verifique que los grupos tengan cupos disponibles (máximo 70 por grupo).');
    }
}
