<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Materia extends Model
{
    protected $table = 'materia';

    protected $fillable = [
        'nombre',
        'codigo',
        'descripcion',
        'creditos',
        'estado',
    ];

    protected $casts = [
        'creditos' => 'integer',
    ];

    // ──────────────────────────────────────────
    // Relaciones
    // ──────────────────────────────────────────

    /** Una materia tiene muchos docentes */
    public function docentes(): BelongsToMany
    {
        return $this->belongsToMany(
            Docente::class,
            'docente_materia',
            'materia_id',
            'docente_id'
        );
    }

    /** Una materia tiene muchas notas */
    public function notas(): HasMany
    {
        return $this->hasMany(Nota::class);
    }

    // ──────────────────────────────────────────
    // Helpers
    // ──────────────────────────────────────────

    /** ¿Materia activa? */
    public function esActiva(): bool
    {
        return $this->estado === 'ACTIVA';
    }
}
