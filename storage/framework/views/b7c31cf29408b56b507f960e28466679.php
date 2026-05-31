<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CUP — Grupo no asignado</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Figtree:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Figtree', sans-serif; background: #f8fafc; color: #1e293b; min-height: 100vh; display: flex; }

        /* ── SIDEBAR ── */
        .sidebar {
            width: 260px; background: linear-gradient(180deg, #1e3a6e 0%, #0f2147 100%); color: #fff;
            display: flex; flex-direction: column; position: fixed; top: 0; bottom: 0; left: 0; z-index: 100;
        }
        .sidebar-brand {
            padding: 24px; font-size: 20px; font-weight: 700; display: flex; align-items: center; gap: 10px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        .sidebar-menu {
            flex: 1; padding: 24px 16px; display: flex; flex-direction: column; gap: 6px;
        }
        .menu-item {
            display: flex; align-items: center; gap: 12px; padding: 12px;
            color: rgba(255, 255, 255, 0.75); text-decoration: none; font-size: 14px; font-weight: 500; border-radius: 10px; transition: all 0.2s;
        }
        .menu-item:hover { background: rgba(255, 255, 255, 0.08); color: #fff; }
        .menu-item.active { background: #2563eb; color: #fff; box-shadow: 0 4px 12px rgba(37, 99, 235, 0.2); }
        .sidebar-footer { padding: 20px; border-top: 1px solid rgba(255, 255, 255, 0.1); font-size: 12px; color: rgba(255, 255, 255, 0.4); text-align: center; }

        /* ── MAIN CONTENT ── */
        .main-content { margin-left: 260px; flex: 1; display: flex; flex-direction: column; min-height: 100vh; }
        .topbar {
            height: 70px; background: #fff; border-bottom: 1px solid #e2e8f0;
            display: flex; align-items: center; justify-content: space-between; padding: 0 40px;
        }
        .welcome-msg h1 { font-size: 20px; font-weight: 700; color: #0f172a; }
        .welcome-msg p { font-size: 13px; color: #64748b; margin-top: 2px; }
        .user-profile { display: flex; align-items: center; gap: 12px; }
        .avatar {
            width: 40px; height: 40px; border-radius: 50%; background: #dbeafe; color: #2563eb;
            display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 14px;
        }
        .user-info .name { font-size: 14px; font-weight: 600; color: #0f172a; }
        .user-info .code { font-size: 11px; color: #64748b; }

        .btn-logout {
            background: none; border: 1px solid #e2e8f0; padding: 8px 16px; border-radius: 8px;
            font-size: 13px; font-weight: 500; color: #64748b; cursor: pointer; display: flex; align-items: center; gap: 6px;
            transition: all 0.2s; font-family: 'Figtree', sans-serif;
        }
        .btn-logout:hover { background: #fee2e2; border-color: #fca5a5; color: #ef4444; }

        .content-wrapper { padding: 40px; max-width: 1200px; width: 100%; margin: 0 auto; flex: 1; }

        .card {
            background: #fff; border: 1px solid #e2e8f0; border-radius: 16px; padding: 48px 32px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.01); text-align: center; max-width: 600px; margin: 0 auto;
        }
        .icon-box {
            width: 80px; height: 80px; border-radius: 50%; background: #eff6ff; color: #2563eb;
            display: flex; align-items: center; justify-content: center; font-size: 40px; margin: 0 auto 24px;
            border: 2px dashed #93c5fd;
        }
        .card h2 { font-size: 22px; font-weight: 700; color: #0f172a; margin-bottom: 12px; }
        .card p { font-size: 14px; color: #64748b; line-height: 1.6; }
    </style>
</head>
<body>

    
    <aside class="sidebar">
        <div class="sidebar-brand">
            <i class="ti ti-school"></i>
            <span>CUP — FICCT</span>
        </div>
        <nav class="sidebar-menu">
            <div class="menu-label">Mi Cuenta</div>
            <a href="<?php echo e(route('postulante.dashboard')); ?>" class="menu-item">
                <i class="ti ti-smart-home"></i>
                <span>Inicio</span>
            </a>
            <a href="<?php echo e(route('postulante.notas')); ?>" class="menu-item">
                <i class="ti ti-file-analytics"></i>
                <span>Mis Notas</span>
            </a>
            <a href="<?php echo e(route('postulante.grupo')); ?>" class="menu-item active">
                <i class="ti ti-users-group"></i>
                <span>Mi Grupo</span>
            </a>
        </nav>
        <div class="sidebar-footer">
            UAGRM &copy; <?php echo e(date('Y')); ?>

        </div>
    </aside>

    
    <div class="main-content">
        
        <header class="topbar">
            <div class="welcome-msg">
                <h1>Mi Grupo Asignado</h1>
                <p>Información detallada de tu grupo, docentes y horarios.</p>
            </div>
            <div class="user-profile">
                <div class="avatar">
                    <?php echo e($postulante->iniciales); ?>

                </div>
                <div class="user-info">
                    <div class="name"><?php echo e($postulante->nombre_completo); ?></div>
                    <div class="code">Reg: <?php echo e($postulante->codigo_estudiante); ?></div>
                </div>
                <form method="POST" action="<?php echo e(route('logout')); ?>" style="margin-left: 10px;">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="btn-logout">
                        <i class="ti ti-logout"></i>
                        <span>Cerrar Sesión</span>
                    </button>
                </form>
            </div>
        </header>

        
        <main class="content-wrapper">
            <div class="card">
                <div class="icon-box">
                    <i class="ti ti-users-group"></i>
                </div>
                <h2>Grupo No Asignado Aún</h2>
                <p>Aún no cuentas con un grupo asignado para tus materias. Los administradores están organizando los grupos y te notificarán en este panel apenas sea habilitado.</p>
            </div>
        </main>
    </div>

</body>
</html>
<?php /**PATH C:\Users\pc\Desktop\Jair torrico\semestre\sistemas de informacion\CUP-main\resources\views/postulante/sin-grupo.blade.php ENDPATH**/ ?>