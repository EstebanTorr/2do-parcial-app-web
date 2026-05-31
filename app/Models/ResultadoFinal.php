<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ResultadoFinal extends Model
{
    protected $table = 'resultado_final';

    public $timestamps = false;

    protected $fillable = [
        'postulante_id',
        'convocatoria_id',
        'promedio_mat',
        'promedio_fis',
        'promedio_com',
        'promedio_ing',
        'promedio_total',
        'aprobado_general',
        'carrera_asignada_id',
        'ranking',
        'estado_admision',
        'calculado_en',
    ];

    protected $casts = [
        'promedio_total' => 'decimal:2',
        'ranking' => 'integer',
        'calculado_en' => 'datetime',
        'aprobado_general' => 'boolean',
    ];

    // ──────────────────────────────────────────
    // Relaciones
    // ──────────────────────────────────────────

    /** Resultado pertenece a un postulante */
    public function postulante(): BelongsTo
    {
        return $this->belongsTo(Postulante::class);
    }

    /** Resultado pertenece a una convocatoria */
    public function convocatoria(): BelongsTo
    {
        return $this->belongsTo(Convocatoria::class);
    }

    /** Carrera asignada */
    public function carreraAsignada(): BelongsTo
    {
        return $this->belongsTo(Carrera::class, 'carrera_asignada_id');
    }

    // ──────────────────────────────────────────
    // Helpers
    // ──────────────────────────────────────────

    /** ¿Postulante admitido? */
    public function esAdmitido(): bool
    {
        return $this->estado_admision === 'ADMITIDO';
    }

    /** ¿Postulante rechazado? */
    public function esRechazado(): bool
    {
        return $this->estado_admision === 'RECHAZADO';
    }
}
