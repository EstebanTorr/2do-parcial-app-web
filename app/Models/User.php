<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $table = 'usuario';        // nuestra tabla
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'nombre',
        'apellido',
        'email',
        'password',
        'rol_id',
        'activo',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function rol()
    {
        return $this->belongsTo(Rol::class, 'rol_id');
    }

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = $value; // sin bcrypt
    }
}