<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Carrera extends Model
{
    protected $table = 'carrera';

    protected $fillable = [
        'nombre',
        'codigo',
        'cupo_max',
        'activa',
    ];

    protected $casts = [
        'activa' => 'boolean',
    ];
}