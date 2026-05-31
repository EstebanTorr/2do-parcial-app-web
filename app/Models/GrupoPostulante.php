<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GrupoPostulante extends Model
{
    protected $table = 'grupo_postulante';

    protected $fillable = [
        'grupo_id',
        'postulante_id',
        'fecha_asignacion',
        'estado',
    ];

    protected $casts = [
        'fecha_asignacion' => 'datetime',
    ];

    // ──────────────────────────────────────────
    // Relaciones
    // ──────────────────────────────────────────

    /** Pertenece a un grupo */
    public function grupo(): BelongsTo
    {
        return $this->belongsTo(Grupo::class);
    }

    /** Pertenece a un postulante */
    public function postulante(): BelongsTo
    {
        return $this->belongsTo(Postulante::class);
    }
}
