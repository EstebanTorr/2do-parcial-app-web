<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AuthenticatedSessionController extends Controller
{
    public function create()
    {
        return view('auth.login');
    }

    public function store(Request $request)
    {
        $request->validate([
            'email'    => 'required|string',
            'password' => 'required|string',
        ], [
            'email.required'    => 'El correo o código es obligatorio.',
            'password.required' => 'La contraseña es obligatoria.',
        ]);

        // Buscar usuario por email o por código de postulante
        $usuario = DB::table('usuario')
            ->where('email', $request->email)
            ->orWhereExists(function ($query) use ($request) {
                $query->select('id')
                    ->from('postulante')
                    ->whereColumn('postulante.usuario_id', 'usuario.id')
                    ->where('postulante.codigo_estudiante', $request->email);
            })
            ->first();

        if (!$usuario) {
            return back()->withErrors([
                'email' => 'No encontramos una cuenta con esos datos.',
            ])->withInput($request->only('email'));
        }

        if (!$usuario->activo) {
            return back()->withErrors([
                'email' => 'Tu cuenta está desactivada. Contacta al administrador.',
            ])->withInput($request->only('email'));
        }

        // Intentar login
        $credenciales = ['email' => $usuario->email, 'password' => $request->password];

        // Verificar contraseña sin encriptación
        // ── DESPUÉS (lo que debes poner) ─────────────────────────
        // Verificar contraseña sin encriptación
        if ($usuario->password !== $request->password) {
            return back()->withErrors([
                'password' => 'La contraseña es incorrecta.',
            ])->withInput($request->only('email'));
        }

        // Verificar que el tab seleccionado coincide con el rol real del usuario
        $rolReal = DB::table('rol')->where('id', $usuario->rol_id)->value('nombre_rol');

        $rolEsperado = match($request->input('rol', 'postulante')) {
            'admin'      => 'ADMINISTRATIVO',
            'docente'    => 'DOCENTE',
            'postulante' => 'POSTULANTE',
            default      => null,
        };

        if ($rolReal !== $rolEsperado) {
            return back()->withErrors([
                'email' => 'Estas credenciales no corresponden a este tipo de acceso.',
            ])->withInput($request->only('email'));
        }

        // Login manual sin bcrypt
       Auth::loginUsingId($usuario->id, $request->boolean('remember'));
    

      
        // Registrar en log
        DB::table('log_actividad')->insert([
            'usuario_id'     => $usuario->id,
            'usuario_nombre' => $usuario->nombre . ' ' . $usuario->apellido,
            'usuario_email'  => $usuario->email,
            'accion'         => 'inicio_sesion',
            'descripcion'    => 'El usuario inició sesión correctamente.',
            'ip'             => $request->ip(),
            'modulo'         => 'auth',
            'resultado'      => 'ok',
            'fecha_hora'     => now(),
        ]);

        // Redirigir según rol
        $rol = DB::table('rol')->where('id', $usuario->rol_id)->value('nombre_rol');

        return match($rolReal) {
            'ADMINISTRATIVO' => redirect()->route('admin.dashboard'),
            'DOCENTE'        => redirect()->route('docente.dashboard'),
            'POSTULANTE'     => redirect()->route('postulante.dashboard'),
            default          => redirect('/'),
        };
    }

    public function destroy(Request $request)
    {
        $usuario = Auth::user();

        if ($usuario) {
            DB::table('log_actividad')->insert([
                'usuario_id'     => $usuario->id,
                'usuario_nombre' => $usuario->nombre . ' ' . $usuario->apellido,
                'usuario_email'  => $usuario->email,
                'accion'         => 'cierre_sesion',
                'descripcion'    => 'El usuario cerró sesión.',
                'ip'             => $request->ip(),
                'modulo'         => 'auth',
                'resultado'      => 'ok',
                'fecha_hora'     => now(),
            ]);
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}