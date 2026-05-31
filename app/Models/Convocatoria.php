<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Convocatoria extends Model
{
    protected $table = 'convocatoria';

    protected $fillable = [
        'nombre',
        'fecha_inicio',
        'fecha_fin',
        'monto_pago',
        'cupo_total',
        'estado',
        'descripcion',
        'año',
    ];

    protected $casts = [
        'fecha_inicio' => 'datetime',
        'fecha_fin' => 'datetime',
        'monto_pago' => 'decimal:2',
        'cupo_total' => 'integer',
    ];

    // ──────────────────────────────────────────
    // Relaciones
    // ──────────────────────────────────────────

    /** Una convocatoria tiene muchos postulantes */
    public function postulantes(): HasMany
    {
        return $this->hasMany(Postulante::class);
    }

    /** Una convocatoria tiene muchos grupos */
    public function grupos(): HasMany
    {
        return $this->hasMany(Grupo::class);
    }

    /** Una convocatoria tiene muchos pre-registros */
    public function preRegistros(): HasMany
    {
        return $this->hasMany(PreRegistroEstudiante::class);
    }

    // ──────────────────────────────────────────
    // Scopes
    // ──────────────────────────────────────────

    /** Convocatorias activas */
    public function scopeActivas($query)
    {
        return $query->where('estado', 'ACTIVA');
    }

    /** Convocatorias no concluidas */
    public function scopeEnCurso($query)
    {
        return $query->whereIn('estado', ['PLANIFICADA', 'ACTIVA']);
    }

    // ──────────────────────────────────────────
    // Helpers
    // ──────────────────────────────────────────

    /** ¿Convocatoria vigente? */
    public function esVigente(): bool
    {
        return $this->estado === 'ACTIVA';
    }

    /** ¿Cupos disponibles? */
    public function tieneCapacidad(): bool
    {
        $postulantesAsignados = $this->postulantes()
            ->whereNotNull('carrera_asignada_id')
            ->count();
        return $postulantesAsignados < $this->cupo_total;
    }
}
