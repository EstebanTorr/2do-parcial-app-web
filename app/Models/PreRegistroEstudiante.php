<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PreRegistroEstudiante extends Model
{
    protected $table = 'pre_registro_estudiante';

    protected $fillable = [
        'convocatoria_id',
        'nombre',
        'apellido',
        'ci',
        'ci_extension',
        'fecha_nacimiento',
        'sexo',
        'direccion',
        'ciudad',
        'telefono',
        'email',
        'colegio_nombre',
        'colegio_tipo',
        'anio_egreso',
        'carrera_pref_1_id',
        'carrera_pref_2_id',
        'turno_preferido',
        'estado',
        'ip_registro',
    ];

    protected $casts = [
        'fecha_nacimiento' => 'date',
        'anio_egreso' => 'integer',
    ];

    // ──────────────────────────────────────────
    // Relaciones
    // ──────────────────────────────────────────

    /** Pre-registro pertenece a una convocatoria */
    public function convocatoria(): BelongsTo
    {
        return $this->belongsTo(Convocatoria::class);
    }

    /** Primera carrera preferida */
    public function carreraPref1(): BelongsTo
    {
        return $this->belongsTo(Carrera::class, 'carrera_pref_1_id');
    }

    /** Segunda carrera preferida */
    public function carreraPref2(): BelongsTo
    {
        return $this->belongsTo(Carrera::class, 'carrera_pref_2_id');
    }

    // ──────────────────────────────────────────
    // Helpers
    // ──────────────────────────────────────────

    /** Nombre completo */
    public function getNombreCompletoAttribute(): string
    {
        return "{$this->nombre} {$this->apellido}";
    }

    /** ¿Pre-registro aprobado? */
    public function esAprobado(): bool
    {
        return $this->estado === 'APROBADO';
    }

    /** ¿Pre-registro pendiente? */
    public function esPendiente(): bool
    {
        return $this->estado === 'PENDIENTE';
    }
}
