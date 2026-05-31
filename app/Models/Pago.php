<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pago extends Model
{
    protected $table = 'pago';

    protected $fillable = [
        'postulante_id',
        'monto',
        'tipo_pago',
        'referencia_transaccion',
        'estado',
        'fecha_pago',
        'comprobante_ruta',
        'observaciones',
    ];

    protected $casts = [
        'monto' => 'decimal:2',
        'fecha_pago' => 'datetime',
    ];

    // ──────────────────────────────────────────
    // Relaciones
    // ──────────────────────────────────────────

    /** Pago de un postulante */
    public function postulante(): BelongsTo
    {
        return $this->belongsTo(Postulante::class);
    }

    // ──────────────────────────────────────────
    // Helpers
    // ──────────────────────────────────────────

    /** ¿Pago validado? */
    public function esValidado(): bool
    {
        return $this->estado === 'VALIDADO';
    }

    /** ¿Pago pendiente? */
    public function esPendiente(): bool
    {
        return $this->estado === 'PENDIENTE';
    }

    /** ¿Pago rechazado? */
    public function esRechazado(): bool
    {
        return $this->estado === 'RECHAZADO';
    }
}
