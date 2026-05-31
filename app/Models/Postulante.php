<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Traits\CalculaPromedio;

/**
 * Modelo Postulante
 *
 * Representa a un estudiante que ha completado su pre-registro y fue admitido
 * formalmente al CUP (Curso Pre-Universitario). Un postulante puede tener
 * diferentes estados a lo largo del proceso:
 *   - PENDIENTE: En espera de revisión.
 *   - APROBADO : Pre-registro aprobado; puede ser asignado a un grupo.
 *   - RECHAZADO: Pre-registro rechazado; no puede asignarse a grupos.
 *
 * Solo los postulantes con estado "APROBADO" son elegibles para
 * ser asignados a un grupo académico (regla CU12).
 *
 * Tabla en BD: "postulante"
 *
 * Usa el trait CalculaPromedio para calcular el promedio de notas del postulante.
 */
class Postulante extends Model
{
    use CalculaPromedio;

    /** Nombre real de la tabla en la base de datos */
    protected $table = 'postulante';

    /**
     * Campos permitidos para asignación masiva (mass assignment).
     * Incluye datos personales, de contacto, preferencias de carrera y estado.
     */
    protected $fillable = [
        'usuario_id', 'convocatoria_id', 'pre_registro_id',
        'codigo_estudiante', 'ci', 'nombre', 'apellido',
        'fecha_nacimiento', 'sexo', 'email', 'telefono',
        'direccion', 'ciudad', 'colegio_nombre',
        'carrera_pref_1_id', 'carrera_pref_2_id',
        'turno_asignado', 'estado',
    ];

    /**
     * Conversión automática de tipos para atributos de la BD.
     * "fecha_nacimiento" se convierte automáticamente a objeto Carbon (fecha).
     */
    protected $casts = [
        'fecha_nacimiento' => 'date',
    ];

    // =========================================================================
    // RELACIONES
    // =========================================================================

    /**
     * Relación: El postulante prefiere la carrera 1 como primera opción.
     *
     * Clave foránea: carrera_pref_1_id en la tabla "postulante".
     * Permite acceder al nombre de la carrera elegida como primera preferencia:
     *   $postulante->carreraPref1->nombre
     *
     * @return BelongsTo
     */
    public function carreraPref1(): BelongsTo
    {
        return $this->belongsTo(Carrera::class, 'carrera_pref_1_id');
    }

    /**
     * Relación: El postulante prefiere la carrera 2 como segunda opción.
     *
     * Clave foránea: carrera_pref_2_id en la tabla "postulante".
     * Permite acceder al nombre de la carrera elegida como segunda preferencia:
     *   $postulante->carreraPref2->nombre
     *
     * @return BelongsTo
     */
    public function carreraPref2(): BelongsTo
    {
        return $this->belongsTo(Carrera::class, 'carrera_pref_2_id');
    }

    /**
     * Relación: El postulante pertenece a una convocatoria.
     *
     * Clave foránea: convocatoria_id en la tabla "postulante".
     * Permite acceder a la convocatoria en la que está inscrito:
     *   $postulante->convocatoria->nombre
     *
     * @return BelongsTo
     */
    public function convocatoria(): BelongsTo
    {
        return $this->belongsTo(Convocatoria::class);
    }

    /**
     * Relación: El postulante está vinculado a un usuario del sistema.
     *
     * Clave foránea: usuario_id en la tabla "postulante".
     * Permite acceder a las credenciales de login del postulante:
     *   $postulante->usuario->email
     *
     * @return BelongsTo
     */
    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    /**
     * Relación: El postulante tiene un registro en la tabla pivot grupo_postulante.
     *
     * Permite consultar directamente el registro de asignación del postulante
     * a un grupo, incluyendo la fecha de asignación y el estado del registro:
     *   $postulante->grupoPostulante->fecha_asignacion
     *
     * @return HasOne
     */
    public function grupoPostulante(): HasOne
    {
        return $this->hasOne(GrupoPostulante::class);
    }

    /**
     * Relación: El postulante puede tener múltiples pagos registrados.
     *
     * Clave foránea: postulante_id en la tabla "pago".
     * Permite consultar el historial de pagos del postulante:
     *   $postulante->pagos  ← colección de Pago
     *
     * @return HasMany
     */
    public function pagos(): HasMany
    {
        return $this->hasMany(Pago::class);
    }

    /**
     * Relación: El postulante puede tener múltiples notas registradas.
     *
     * Clave foránea: postulante_id en la tabla "nota".
     * Permite consultar todas las calificaciones del postulante:
     *   $postulante->notas  ← colección de Nota
     *
     * @return HasMany
     */
    public function notas(): HasMany
    {
        return $this->hasMany(Nota::class);
    }

    /**
     * Relación: El postulante tiene un único resultado final del proceso.
     *
     * Clave foránea: postulante_id en la tabla "resultado_final".
     * Permite acceder al dictamen final (admitido/no admitido) del postulante:
     *   $postulante->resultadoFinal->estado
     *
     * @return HasOne
     */
    public function resultadoFinal(): HasOne
    {
        return $this->hasOne(ResultadoFinal::class);
    }

    /**
     * Relación: El postulante puede tener múltiples registros de asistencia.
     *
     * Clave foránea: postulante_id en la tabla "asistencia".
     * Permite consultar el historial de asistencias del postulante:
     *   $postulante->asistencias  ← colección de Asistencia
     *
     * @return HasMany
     */
    public function asistencias(): HasMany
    {
        return $this->hasMany(Asistencia::class);
    }

    // =========================================================================
    // ACCESSORS (Atributos Computados)
    // =========================================================================

    /**
     * ACCESSOR: Nombre completo del postulante.
     *
     * Concatena "nombre" y "apellido" separados por un espacio.
     * Disponible como propiedad virtual en toda la aplicación:
     *   $postulante->nombre_completo  →  "Ana García"
     *
     * @return string  Nombre y apellido del postulante.
     */
    public function getNombreCompletoAttribute(): string
    {
        return "{$this->nombre} {$this->apellido}";
    }

    /**
     * ACCESSOR: Iniciales del postulante para mostrar en avatares de la UI.
     *
     * Toma la primera letra del nombre y la primera letra del apellido,
     * ambas en mayúsculas. Útil para generar avatares circulares en la interfaz.
     *
     * Ejemplo: "Ana García" → "AG"
     *
     * @return string  Dos iniciales en mayúsculas.
     */
    public function getInicialesAttribute(): string
    {
        return strtoupper(substr($this->nombre, 0, 1) . substr($this->apellido, 0, 1));
    }

    // =========================================================================
    // MÉTODOS HELPER
    // =========================================================================

    /**
     * Verifica si el postulante tiene al menos un pago con estado "VALIDADO".
     *
     * Un pago validado es requisito para que el postulante complete
     * su inscripción. Se usa en vistas y lógica de negocio para
     * mostrar el estado de pago del postulante.
     *
     * @return bool  true si tiene pago validado, false en caso contrario.
     */
    public function tienePagoValidado(): bool
    {
        return $this->pagos()->where('estado', 'VALIDADO')->exists();
    }
}