<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware para validar roles en las rutas
 * 
 * Uso: middleware('role:ADMINISTRATIVO') o middleware('role:ADMINISTRATIVO,DOCENTE')
 */
class CheckRole
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
if (!$request->user()) {
        return redirect('/login')->with('error', 'Debes iniciar sesión');
    }

    // Asegúrate de que el modelo User tenga la relación "rol" definida
    // Si tu usuario tiene un rol asociado, accedemos a su nombre_rol
    $nombreRolUsuario = $request->user()->rol?->nombre_rol;

    if (!$nombreRolUsuario) {
        abort(403, 'Tu usuario no tiene un rol válido asignado');
    }

    // Verificar si el nombre del rol está en los permitidos
    if (!in_array($nombreRolUsuario, $roles)) {
        abort(403, 'No tienes permiso para acceder a esta sección. Se requiere: ' . implode(', ', $roles));
    }

    return $next($request);
    }
}