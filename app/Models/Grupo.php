<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Modelo Grupo
 *
 * Representa un grupo académico del CUP (Curso Pre-Universitario).
 * Cada grupo pertenece a una convocatoria, tiene un turno (MAÑANA/TARDE/NOCHE),
 * una capacidad máxima de estudiantes y puede tener múltiples postulantes
 * y docentes asignados mediante relaciones muchos-a-muchos.
 *
 * Tabla en BD: "grupo"
 * Columnas reales: id, convocatoria_id, codigo_grupo, turno, activo, capacidad
 *
 * NOTA: Este modelo usa accessors y mutators para mapear nombres más intuitivos:
 *   - "numero_grupo"   ← → BD: "codigo_grupo"
 *   - "capacidad_maxima" ← → BD: "capacidad"
 *   - "estado"         ← → BD: "activo" (boolean → string ACTIVO/INACTIVO)
 */
class Grupo extends Model
{
    /** Nombre real de la tabla en la base de datos */
    protected $table = 'grupo';

    /**
     * El campo updated_at no existe en esta tabla, se desactiva
     * para evitar errores al guardar/actualizar registros.
     */
    const UPDATED_AT = null;

    /**
     * Campos permitidos para asignación masiva (mass assignment).
     * Incluye los campos reales de la BD más los alias virtuales
     * que son manejados por los mutators (setters).
     */
    protected $fillable = [
        'convocatoria_id',
        'codigo_grupo',
        'turno',
        'activo',
        'capacidad',

        // Campos virtuales que disparan mutators durante mass assignment
        'numero_grupo',
        'capacidad_maxima',
        'estado',
    ];

    /**
     * Conversión automática de tipos para los campos de la BD.
     * "capacidad" se castea a integer y "activo" a boolean.
     */
    protected $casts = [
        'capacidad' => 'integer',
        'activo'    => 'boolean',
    ];

    // =========================================================================
    // ACCESSORS & MUTATORS (Mapeo Virtual)
    // Permiten usar nombres amigables en el código aunque la BD use otros nombres.
    // =========================================================================

    /**
     * ACCESSOR: Obtener el número de grupo.
     * Lee la columna real "codigo_grupo" de la BD y la expone como "numero_grupo".
     *
     * Uso: $grupo->numero_grupo  →  retorna el valor de codigo_grupo
     *
     * @return int|null  Número del grupo, o null si no está definido.
     */
    public function getNumeroGrupoAttribute()
    {
        return $this->attributes['codigo_grupo'] ?? null;
    }

    /**
     * MUTATOR: Guardar el número de grupo.
     * Cuando se asigna "numero_grupo" al modelo, escribe en la columna "codigo_grupo".
     *
     * Uso: $grupo->numero_grupo = 3  →  guarda en codigo_grupo = 3
     *
     * @param  mixed $value  Valor numérico del grupo.
     */
    public function setNumeroGrupoAttribute($value)
    {
        $this->attributes['codigo_grupo'] = $value;
    }

    /**
     * ACCESSOR: Obtener la capacidad máxima del grupo.
     * Lee la columna real "capacidad" de la BD y la expone como "capacidad_maxima".
     *
     * Uso: $grupo->capacidad_maxima  →  retorna el valor de capacidad
     *
     * @return int|null  Número máximo de estudiantes permitidos.
     */
    public function getCapacidadMaximaAttribute()
    {
        return $this->attributes['capacidad'] ?? null;
    }

    /**
     * MUTATOR: Guardar la capacidad máxima del grupo.
     * Cuando se asigna "capacidad_maxima" al modelo, escribe en la columna "capacidad".
     *
     * Uso: $grupo->capacidad_maxima = 70  →  guarda en capacidad = 70
     *
     * @param  mixed $value  Número máximo de estudiantes.
     */
    public function setCapacidadMaximaAttribute($value)
    {
        $this->attributes['capacidad'] = $value;
    }

    /**
     * ACCESSOR: Obtener el estado del grupo como texto.
     * Convierte el booleano "activo" de la BD en los strings "ACTIVO" o "INACTIVO"
     * para facilitar su uso en vistas y lógica de negocio.
     *
     * Uso: $grupo->estado  →  "ACTIVO" o "INACTIVO"
     *
     * @return string  "ACTIVO" si activo=true, "INACTIVO" si activo=false o no definido.
     */
    public function getEstadoAttribute()
    {
        if (!isset($this->attributes['activo'])) {
            return 'INACTIVO';
        }
        return $this->attributes['activo'] ? 'ACTIVO' : 'INACTIVO';
    }

    /**
     * MUTATOR: Guardar el estado del grupo desde texto o boolean.
     * Acepta tanto strings ("ACTIVO", "INACTIVO") como booleanos/enteros,
     * y los convierte al boolean que espera la columna "activo" en la BD.
     *
     * Uso: $grupo->estado = 'ACTIVO'  →  guarda activo = true
     *
     * @param  mixed $value  "ACTIVO", true, 1 o "1" → true; cualquier otro → false.
     */
    public function setEstadoAttribute($value)
    {
        $this->attributes['activo'] = ($value === 'ACTIVO' || $value === true || $value === 1 || $value === '1');
    }

    // =========================================================================
    // RELACIONES
    // =========================================================================

    /**
     * Relación: Un grupo pertenece a una convocatoria.
     *
     * Clave foránea: convocatoria_id en la tabla "grupo".
     * Permite acceder a los datos de la convocatoria desde el grupo:
     *   $grupo->convocatoria->nombre
     *
     * @return BelongsTo
     */
    public function convocatoria(): BelongsTo
    {
        return $this->belongsTo(Convocatoria::class);
    }

    /**
     * Relación: Un grupo tiene muchos postulantes (muchos a muchos).
     *
     * Tabla pivot: "grupo_postulante"
     * Clave local: grupo_id
     * Clave foránea: postulante_id
     *
     * Permite asignar/desasignar postulantes:
     *   $grupo->postulantes()->attach($id)
     *   $grupo->postulantes()->detach($id)
     *   $grupo->postulantes  ← colección de Postulante
     *
     * @return BelongsToMany
     */
    public function postulantes(): BelongsToMany
    {
        return $this->belongsToMany(
            Postulante::class,
            'grupo_postulante',
            'grupo_id',
            'postulante_id'
        );
    }

    /**
     * Relación: Un grupo tiene muchos docentes (muchos a muchos).
     *
     * Tabla pivot: "grupo_docente"
     * Clave local: grupo_id
     * Clave foránea: docente_id
     *
     * Permite asignar/desasignar docentes:
     *   $grupo->docentes()->attach($id)
     *   $grupo->docentes()->detach($id)
     *   $grupo->docentes  ← colección de Docente
     *
     * @return BelongsToMany
     */
    public function docentes(): BelongsToMany
    {
        return $this->belongsToMany(
            Docente::class,
            'grupo_docente',
            'grupo_id',
            'docente_id'
        );
    }

    /**
     * Relación: Un grupo tiene muchas notas.
     *
     * Clave foránea: grupo_id en la tabla "nota".
     * Permite consultar todas las notas registradas en este grupo.
     *
     * @return HasMany
     */
    public function notas(): HasMany
    {
        return $this->hasMany(Nota::class);
    }

    // =========================================================================
    // MÉTODOS HELPER
    // =========================================================================

    /**
     * Cuenta cuántos postulantes tiene actualmente asignados el grupo.
     *
     * Ejecuta un COUNT en la relación muchos-a-muchos con postulantes.
     * Úsalo cuando necesites el número exacto en tiempo real (no en caché).
     *
     * @return int  Número de postulantes actualmente en el grupo.
     */
    public function countPostulantes(): int
    {
        return $this->postulantes()->count();
    }

    /**
     * Determina si el grupo ha alcanzado o superado su capacidad máxima.
     *
     * Compara la cantidad actual de postulantes con la capacidad máxima
     * almacenada en la BD. NO aplica el límite reglamentario de 70
     * (esa lógica está en el controlador).
     *
     * @return bool  true si el grupo está lleno, false si aún tiene cupo.
     */
    public function estaLleno(): bool
    {
        return $this->countPostulantes() >= $this->capacidad_maxima;
    }

    /**
     * Calcula cuántos cupos quedan disponibles en el grupo.
     *
     * Resta los postulantes actuales de la capacidad máxima.
     * Retorna 0 si el grupo está lleno (nunca retorna negativos).
     *
     * @return int  Número de vacantes disponibles (mínimo 0).
     */
    public function espaciosDisponibles(): int
    {
        return max(0, $this->capacidad_maxima - $this->countPostulantes());
    }
}
