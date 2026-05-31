<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Nota extends Model
{
    protected $table = 'nota';

    protected $fillable = [
        'postulante_id',
        'grupo_id',
        'materia_id',
        'docente_id',
        'calificacion',
        'tipo_evaluacion',
        'fecha_registro',
        'observaciones',
    ];

    protected $casts = [
        'calificacion' => 'decimal:2',
        'fecha_registro' => 'datetime',
    ];

    // ──────────────────────────────────────────
    // Relaciones
    // ──────────────────────────────────────────

    /** Una nota pertenece a un postulante */
    public function postulante(): BelongsTo
    {
        return $this->belongsTo(Postulante::class);
    }

    /** Una nota pertenece a un grupo */
    public function grupo(): BelongsTo
    {
        return $this->belongsTo(Grupo::class);
    }

    /** Una nota pertenece a una materia */
    public function materia(): BelongsTo
    {
        return $this->belongsTo(Materia::class);
    }

    /** Una nota es registrada por un docente */
    public function docente(): BelongsTo
    {
        return $this->belongsTo(Docente::class);
    }

    // ──────────────────────────────────────────
    // Scopes
    // ──────────────────────────────────────────

    /** Notas aprobadas (>= 51) */
    public function scopeAprobadas($query)
    {
        return $query->where('calificacion', '>=', 51);
    }

    /** Notas reprobadas (< 51) */
    public function scopeReprobadas($query)
    {
        return $query->where('calificacion', '<', 51);
    }

    // ──────────────────────────────────────────
    // Helpers
    // ──────────────────────────────────────────

    /** ¿Nota aprobada? */
    public function esAprobada(): bool
    {
        return $this->calificacion >= 51;
    }

    /** Estado descriptivo */
    public function getEstadoDescriptivo(): string
    {
        return $this->esAprobada() ? 'Aprobado' : 'Reprobado';
    }
}
