<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Modelo Docente
 *
 * Representa a un docente del CUP (Curso Pre-Universitario).
 * Un docente puede estar vinculado a un usuario del sistema (para login),
 * tener múltiples materias asignadas, pertenecer a varios grupos académicos
 * y haber registrado notas para los postulantes de esos grupos.
 *
 * Tabla en BD: "docente"
 * Estados posibles: ACTIVO (disponible para asignación), INACTIVO, LICENCIA.
 */
class Docente extends Model
{
    /** Nombre real de la tabla en la base de datos */
    protected $table = 'docente';

    /**
     * Campos permitidos para asignación masiva (mass assignment).
     * Incluye datos personales, de contacto, especialidad y estado.
     */
    protected $fillable = [
        'usuario_id',
        'nombre',
        'apellido',
        'ci',
        'email',
        'telefono',
        'especialidad',
        'estado',
    ];

    // =========================================================================
    // RELACIONES
    // =========================================================================

    /**
     * Relación: Un docente está vinculado a un usuario del sistema.
     *
     * Clave foránea: usuario_id en la tabla "docente".
     * Permite acceder al usuario de login asociado al docente:
     *   $docente->usuario->email
     *
     * NOTA: Puede ser null si el docente no tiene cuenta de acceso al sistema.
     *
     * @return BelongsTo
     */
    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    /**
     * Relación: Un docente puede impartir múltiples materias (muchos a muchos).
     *
     * Tabla pivot: "docente_materia"
     * Clave local: docente_id
     * Clave foránea: materia_id
     *
     * Permite consultar las materias habilitadas para un docente:
     *   $docente->materias  ← colección de Materia
     *
     * @return BelongsToMany
     */
    public function materias(): BelongsToMany
    {
        return $this->belongsToMany(
            Materia::class,
            'docente_materia',
            'docente_id',
            'materia_id'
        );
    }

    /**
     * Relación: Un docente puede estar asignado a múltiples grupos (muchos a muchos).
     *
     * Tabla pivot: "grupo_docente"
     * Clave local: docente_id
     * Clave foránea: grupo_id
     *
     * Permite consultar en qué grupos está enseñando este docente:
     *   $docente->grupos  ← colección de Grupo
     *
     * @return BelongsToMany
     */
    public function grupos(): BelongsToMany
    {
        return $this->belongsToMany(
            Grupo::class,
            'grupo_docente',
            'docente_id',
            'grupo_id'
        );
    }

    /**
     * Relación: Un docente puede haber registrado múltiples notas.
     *
     * Clave foránea: docente_id en la tabla "nota".
     * Permite ver todas las evaluaciones que ha cargado este docente:
     *   $docente->notas  ← colección de Nota
     *
     * @return HasMany
     */
    public function notas(): HasMany
    {
        return $this->hasMany(Nota::class);
    }

    // =========================================================================
    // ACCESSORS (Atributos Computados)
    // =========================================================================

    /**
     * ACCESSOR: Nombre completo del docente.
     *
     * Concatena "nombre" y "apellido" separados por un espacio.
     * Disponible como propiedad virtual en toda la aplicación:
     *   $docente->nombre_completo  →  "Juan Pérez"
     *
     * @return string  Nombre y apellido del docente.
     */
    public function getNombreCompletoAttribute(): string
    {
        return "{$this->nombre} {$this->apellido}";
    }

    /**
     * ACCESSOR: Iniciales del docente para mostrar en avatares.
     *
     * Toma la primera letra del nombre y la primera letra del apellido,
     * ambas en mayúsculas. Útil para generar avatares circulares en la UI.
     *
     * Ejemplo: "Juan Pérez" → "JP"
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
     * Verifica si el docente está activo y disponible para ser asignado a grupos.
     *
     * Un docente ACTIVO puede ser asignado a grupos del CUP.
     * Un docente INACTIVO o en LICENCIA no puede recibir nuevas asignaciones.
     *
     * @return bool  true si el estado es "ACTIVO", false en cualquier otro caso.
     */
    public function esActivo(): bool
    {
        return $this->estado === 'ACTIVO';
    }
}
