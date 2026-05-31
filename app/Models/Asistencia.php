<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Asistencia extends Model
{
    protected $table = 'asistencia';

    protected $fillable = [
        'postulante_id',
        'grupo_id',
        'fecha_evento',
        'estado',
        'observaciones',
    ];

    protected $casts = [
        'fecha_evento' => 'datetime',
    ];

    // ──────────────────────────────────────────
    // Relaciones
    // ──────────────────────────────────────────

    /** Asistencia de un postulante */
    public function postulante(): BelongsTo
    {
        return $this->belongsTo(Postulante::class);
    }

    /** Asistencia en un grupo */
    public function grupo(): BelongsTo
    {
        return $this->belongsTo(Grupo::class);
    }

    // ──────────────────────────────────────────
    // Helpers
    // ──────────────────────────────────────────

    /** ¿Postulante asistió? */
    public function asistio(): bool
    {
        return $this->estado === 'PRESENTE';
    }

    /** ¿Postulante ausente? */
    public function ausente(): bool
    {
        return $this->estado === 'AUSENTE';
    }
}
