<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIRT — Iniciar sesión</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Figtree:wght@400;500;600;700&display=swap');

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Figtree', sans-serif;
            min-height: 100vh;
            display: flex;
            background: #f1f5f9;
        }

        /* ── LADO IZQUIERDO ── */
        .left {
            width: 420px;
            min-height: 100vh;
            background: linear-gradient(160deg, #1e3a6e 0%, #0f2147 60%, #091630 100%);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 48px 40px;
            position: relative;
            overflow: hidden;
            flex-shrink: 0;
        }

        /* círculos decorativos */
        .left::before {
            content: '';
            position: absolute;
            width: 340px; height: 340px;
            border-radius: 50%;
            border: 1px solid rgba(99,153,220,0.15);
            top: -80px; left: -80px;
        }
        .left::after {
            content: '';
            position: absolute;
            width: 260px; height: 260px;
            border-radius: 50%;
            border: 1px solid rgba(99,153,220,0.1);
            bottom: -60px; right: -60px;
        }

        .left-inner { position: relative; z-index: 1; text-align: center; width: 100%; }

        .brand-icon {
            width: 64px; height: 64px;
            background: rgba(99,153,220,0.15);
            border: 1px solid rgba(99,153,220,0.3);
            border-radius: 16px;
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 24px;
            font-size: 28px;
        }

        .brand-name {
            font-size: 26px; font-weight: 700;
            color: #fff; letter-spacing: -0.5px;
            margin-bottom: 6px;
        }

        .brand-sub {
            font-size: 12px; color: #7aadd4;
            line-height: 1.6; margin-bottom: 40px;
        }

        .divider {
            width: 48px; height: 1px;
            background: rgba(99,153,220,0.3);
            margin: 0 auto 32px;
        }

        .left-desc {
            font-size: 13px; color: #7aadd4;
            line-height: 1.7; margin-bottom: 40px;
        }

        .pre-registro-label {
            font-size: 11px; color: #5a80a8;
            text-transform: uppercase; letter-spacing: .08em;
            margin-bottom: 12px;
        }

        .btn-outline {
            display: block; width: 100%;
            padding: 11px 20px;
            border: 1px solid rgba(99,153,220,0.35);
            border-radius: 10px;
            color: #a8c8f0; font-size: 13px;
            text-decoration: none; text-align: center;
            margin-bottom: 10px;
            transition: background .2s, border-color .2s;
        }
        .btn-outline:hover {
            background: rgba(99,153,220,0.1);
            border-color: rgba(99,153,220,0.6);
        }

        .left-footer {
            position: absolute; bottom: 24px;
            font-size: 11px; color: #3a5a80;
        }

        /* ── LADO DERECHO ── */
        .right {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 24px;
        }

        .form-card {
            width: 100%; max-width: 420px;
        }

        .form-title {
            font-size: 24px; font-weight: 700;
            color: #0f2147; margin-bottom: 6px;
            letter-spacing: -0.3px;
        }

        .form-subtitle {
            font-size: 13px; color: #64748b;
            margin-bottom: 32px;
        }

        /* tabs de rol */
        .rol-tabs {
            display: flex; gap: 6px;
            background: #e8eef6;
            border-radius: 10px;
            padding: 4px;
            margin-bottom: 28px;
        }
        .rol-tab {
            flex: 1; padding: 8px 12px;
            border-radius: 8px;
            border: none; background: none;
            font-size: 12px; font-weight: 500;
            color: #64748b; cursor: pointer;
            transition: all .2s;
            font-family: 'Figtree', sans-serif;
        }
        .rol-tab.active {
            background: #fff;
            color: #1e3a6e;
            box-shadow: 0 1px 4px rgba(0,0,0,0.08);
        }

        /* errores */
        .error-box {
            background: #fef2f2; border: 1px solid #fecaca;
            border-radius: 10px; padding: 12px 16px;
            margin-bottom: 20px; font-size: 13px; color: #b91c1c;
        }

        /* campos */
        .field { margin-bottom: 18px; }

        label {
            display: block; font-size: 12px;
            font-weight: 500; color: #374151;
            margin-bottom: 6px;
        }

        input[type="email"],
        input[type="text"],
        input[type="password"] {
            width: 100%; padding: 11px 14px;
            border: 1px solid #d1d5db;
            border-radius: 10px;
            font-size: 13px; color: #111827;
            background: #fff;
            outline: none;
            transition: border-color .2s, box-shadow .2s;
            font-family: 'Figtree', sans-serif;
        }
        input:focus {
            border-color: #1e3a6e;
            box-shadow: 0 0 0 3px rgba(30,58,110,0.08);
        }

        .input-hint {
            font-size: 11px; color: #9ca3af;
            margin-top: 4px;
        }

        /* checkbox */
        .check-row {
            display: flex; align-items: center;
            gap: 8px; margin-bottom: 24px;
        }
        .check-row input[type="checkbox"] {
            width: 16px; height: 16px;
            accent-color: #1e3a6e;
        }
        .check-row label {
            margin: 0; font-size: 13px;
            color: #374151; font-weight: 400;
        }

        /* botón principal */
        .btn-primary {
            width: 100%; padding: 13px;
            background: #1e3a6e;
            color: #fff; font-size: 14px;
            font-weight: 600; border: none;
            border-radius: 10px; cursor: pointer;
            font-family: 'Figtree', sans-serif;
            transition: background .2s, transform .1s;
            letter-spacing: .01em;
        }
        .btn-primary:hover { background: #162d58; }
        .btn-primary:active { transform: scale(.99); }

        .form-footer {
            margin-top: 20px; text-align: center;
            font-size: 12px; color: #9ca3af;
            line-height: 1.6;
        }

        /* responsive */
        @media (max-width: 768px) {
            .left { display: none; }
        }
    </style>
</head>
<body>

    
    <div class="left">
        <div class="left-inner">
            <div class="brand-icon">🎓</div>
            <div class="brand-name">CUP — FICCT</div>
            <div class="brand-sub">
                Sistema de Ingreso y Registro<br>
                Fac. de Ciencias de la Computación<br>
                y Telecomunicaciones — UAGRM
            </div>

            <div class="divider"></div>

            <p class="left-desc">
                Gestión 2025 · Convocatoria abierta.<br>
                Si ya tienes tus credenciales,<br>
                inicia sesión para continuar.
            </p>

            <p class="pre-registro-label">¿Aún no tienes cuenta?</p>
            <a href="<?php echo e(route('pre-registro.estudiante')); ?>" class="btn-outline">
                📋 &nbsp;Pre-registro estudiante
            </a>
            <a href="<?php echo e(route('pre-registro.docente')); ?>" class="btn-outline">
                🧑‍🏫 &nbsp;Pre-registro docente
            </a>
        </div>
        <div class="left-footer">UAGRM &copy; <?php echo e(date('Y')); ?></div>
    </div>

    
    <div class="right">
        <div class="form-card">

            <h1 class="form-title">Iniciar sesión</h1>
            <p class="form-subtitle">Ingresa con las credenciales enviadas a tu correo</p>

            
            <div class="rol-tabs">
                <button type="button" class="rol-tab active" onclick="setRol(this, 'postulante')">
                    👤 Postulante
                </button>
                <button type="button" class="rol-tab" onclick="setRol(this, 'docente')">
                    🧑‍🏫 Docente
                </button>
                <button type="button" class="rol-tab" onclick="setRol(this, 'admin')">
                    ⚙️ Admin
                </button>
            </div>

            
            <?php if($errors->any()): ?>
                <div class="error-box">
                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div><?php echo e($error); ?></div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            <?php endif; ?>

            
            <form method="POST" action="<?php echo e(route('login')); ?>">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="rol" id="rol_input" value="postulante">

                <div class="field">
                    <label for="email">Registro</label>
                    <input
                        type="text"
                        id="email"
                        name="email"
                        value="<?php echo e(old('email')); ?>"
                        placeholder="Codigo"
                        required
                        autofocus
                    >
                    <p class="input-hint">Usa el código que recibiste al ser aprobado</p>
                </div>

                <div class="field">
                    <label for="password">Contraseña</label>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        placeholder="Contraseña"
                        required
                    >
                </div>

                <div class="check-row">
                    <input type="checkbox" name="remember" id="remember">
                    <label for="remember">Recordar mi sesión</label>
                </div>

                <button type="submit" class="btn-primary">
                    Ingresar al sistema →
                </button>
            </form>

            <div class="form-footer">
                ¿Olvidaste tu contraseña?<br>
                Contacta al administrador de la FICCT<br><br>
                <span style="color:#d1d5db">Si es tu primer ingreso, usa la contraseña temporal enviada a tu correo</span>
            </div>

        </div>
    </div>

    <script>
        function setRol(btn, rol) {
            document.querySelectorAll('.rol-tab').forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            document.getElementById('rol_input').value = rol;
        }
    </script>

</body>
</html><?php /**PATH C:\Users\pc\Desktop\Jair torrico\semestre\sistemas de informacion\CUP-main\resources\views/auth/login.blade.php ENDPATH**/ ?>