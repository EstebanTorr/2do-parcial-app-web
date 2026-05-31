<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIRT — Panel Administrador</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Figtree:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Figtree', sans-serif; background: #f1f5f9; color: #1e293b; }

        /* ── TOPBAR ── */
        .topbar {
            background: #1e3a6e;
            padding: 0 24px;
            height: 56px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: fixed; top: 0; left: 0; right: 0; z-index: 200;
        }
        .topbar-brand { color: #fff; font-size: 16px; font-weight: 600; display: flex; align-items: center; gap: 10px; }
        .topbar-right { display: flex; align-items: center; gap: 16px; }
        .topbar-user { color: #a8c8f0; font-size: 13px; display: flex; align-items: center; gap: 6px; }
        .btn-logout {
            background: rgba(255,255,255,0.12);
            border: 1px solid rgba(255,255,255,0.22);
            color: #fff; padding: 6px 14px;
            border-radius: 8px; font-size: 12px;
            cursor: pointer; text-decoration: none;
            display: flex; align-items: center; gap: 6px;
            transition: background .2s;
        }
        .btn-logout:hover { background: rgba(255,255,255,0.22); }

        /* ── SIDEBAR ── */
        .sidebar {
            width: 224px;
            height: calc(100vh - 56px);
            background: #1e3a6e;
            position: fixed;
            top: 56px; left: 0;
            overflow-y: auto;
            padding: 20px 12px 24px;
            display: flex;
            flex-direction: column;
            gap: 2px;
        }

        .nav-label {
            font-size: 10px; font-weight: 700;
            color: rgba(168,200,240,0.55);
            text-transform: uppercase;
            letter-spacing: .1em;
            padding: 16px 10px 6px;
            margin-top: 4px;
        }
        .nav-label:first-child { padding-top: 4px; }

        .nav-item {
            padding: 9px 12px;
            font-size: 13px;
            color: rgba(168,200,240,0.85);
            text-decoration: none;
            border-radius: 8px;
            display: flex; align-items: center; gap: 10px;
            transition: background .15s, color .15s;
            font-weight: 400;
        }
        .nav-item i { font-size: 16px; flex-shrink: 0; }
        .nav-item:hover {
            background: rgba(255,255,255,0.1);
            color: #fff;
        }
        .nav-item.active {
            background: rgba(255,255,255,0.15);
            color: #fff;
            font-weight: 500;
        }
        .nav-item.active i { color: #7dd3fc; }

        /* colores de ícono por sección */
        .nav-item.c-blue   i { color: #93c5fd; }
        .nav-item.c-amber  i { color: #fcd34d; }
        .nav-item.c-teal   i { color: #6ee7b7; }
        .nav-item.c-purple i { color: #c4b5fd; }
        .nav-item.c-rose   i { color: #fda4af; }
        .nav-item.c-sky    i { color: #7dd3fc; }

        .sidebar-footer {
            margin-top: auto;
            padding: 16px 10px 0;
            border-top: 1px solid rgba(255,255,255,0.08);
            font-size: 11px;
            color: rgba(168,200,240,0.4);
            text-align: center;
        }

        /* ── LAYOUT ── */
        .layout {
            margin-left: 224px;
            margin-top: 56px;
            padding: 24px;
            min-height: calc(100vh - 56px);
        }

        .page-title {
            font-size: 11px; color: #94a3b8;
            text-transform: uppercase; letter-spacing: .08em;
            margin-bottom: 16px; font-weight: 600;
        }

        /* ── MÉTRICAS ── */
        .metrics { display: grid; grid-template-columns: repeat(4,1fr); gap: 12px; margin-bottom: 24px; }
        .metric {
            background: #fff; border-radius: 12px;
            padding: 18px 20px;
            border: 1px solid #e2e8f0;
        }
        .metric-label { font-size: 12px; color: #64748b; margin-bottom: 8px; display: flex; align-items: center; gap: 6px; }
        .metric-value { font-size: 28px; font-weight: 600; color: #1e293b; }
        .metric-sub { font-size: 11px; color: #94a3b8; margin-top: 4px; }
        .metric.blue   .metric-value { color: #1e3a6e; }
        .metric.amber  .metric-value { color: #92400e; }
        .metric.teal   .metric-value { color: #065f46; }
        .metric.purple .metric-value { color: #4c1d95; }

        /* ── GRID 2 COLS ── */
        .two-col { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px; }

        /* ── CARD ── */
        .card {
            background: #fff; border-radius: 12px;
            border: 1px solid #e2e8f0; padding: 20px;
        }
        .card-title {
            font-size: 13px; font-weight: 500; color: #1e293b;
            margin-bottom: 16px; display: flex;
            justify-content: space-between; align-items: center;
        }
        .card-title a { font-size: 12px; color: #1e3a6e; text-decoration: none; font-weight: 400; }

        /* ── TABLA ── */
        table { width: 100%; border-collapse: collapse; font-size: 12px; }
        th { text-align: left; padding: 8px; font-size: 11px; font-weight: 500; color: #94a3b8; border-bottom: 1px solid #f1f5f9; }
        td { padding: 10px 8px; border-bottom: 1px solid #f8fafc; color: #374151; vertical-align: middle; }
        tr:last-child td { border-bottom: none; }

        /* ── BADGES ── */
        .badge { display: inline-flex; align-items: center; padding: 2px 10px; border-radius: 99px; font-size: 11px; font-weight: 500; }
        .badge-pend  { background: #fef3c7; color: #92400e; }
        .badge-ok    { background: #d1fae5; color: #065f46; }
        .badge-err   { background: #fee2e2; color: #991b1b; }
        .badge-blue  { background: #dbeafe; color: #1e40af; }
        .badge-doc   { background: #ede9fe; color: #5b21b6; }

        /* ── AVATAR ── */
        .avatar { width: 30px; height: 30px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 11px; font-weight: 600; flex-shrink: 0; }
        .av-blue   { background: #dbeafe; color: #1e40af; }
        .av-purple { background: #ede9fe; color: #5b21b6; }

        /* ── BOTONES ── */
        .btn { padding: 6px 14px; border-radius: 8px; font-size: 12px; cursor: pointer; border: none; font-family: 'Figtree', sans-serif; text-decoration: none; display: inline-flex; align-items: center; gap: 4px; }
        .btn-sm-ok  { background: #d1fae5; color: #065f46; border: 1px solid #6ee7b7; }
        .btn-sm-err { background: #fee2e2; color: #991b1b; border: 1px solid #fca5a5; }

        /* ── ACCIONES RÁPIDAS ── */
        .acciones { display: grid; grid-template-columns: 1fr 1fr; gap: 8px; }
        .accion-btn {
            padding: 12px 14px; border-radius: 10px;
            border: 1px solid #e2e8f0; background: #f8fafc;
            font-size: 12px; color: #374151; cursor: pointer;
            text-align: left; font-family: 'Figtree', sans-serif;
            display: flex; align-items: center; gap: 8px;
            text-decoration: none;
            transition: background .15s, border-color .15s;
        }
        .accion-btn:hover { background: #f1f5f9; border-color: #cbd5e1; }

        /* ── VACÍO ── */
        .empty { text-align: center; padding: 32px; color: #94a3b8; font-size: 13px; }
    </style>
</head>
<body>


<div class="topbar">
    <div class="topbar-brand">
        <i class="ti ti-school" style="font-size:20px"></i>
        CUP — FICCT
    </div>
    <div class="topbar-right">
        <span class="topbar-user">
            <i class="ti ti-user-circle" style="font-size:16px"></i>
            <?php echo e(Auth::user()->nombre); ?> <?php echo e(Auth::user()->apellido); ?>

        </span>
        <form method="POST" action="<?php echo e(route('logout')); ?>" style="display:inline">
            <?php echo csrf_field(); ?>
            <button type="submit" class="btn-logout">
                <i class="ti ti-logout"></i> Salir
            </button>
        </form>
    </div>
</div>


<aside class="sidebar">

    <div class="nav-label">Menú</div>
    <a href="<?php echo e(route('admin.dashboard')); ?>" class="nav-item active c-sky">
        <i class="ti ti-home"></i> Inicio
    </a>
    <a href="<?php echo e(route('admin.pre-registros.index')); ?>" class="nav-item c-amber">
        <i class="ti ti-clock"></i> Pre-registros
    </a>
    <a href="<?php echo e(route('admin.postulantes.index')); ?>" class="nav-item c-blue">
        <i class="ti ti-id-badge"></i> Postulantes
    </a>
    <a href="<?php echo e(route('admin.docentes.index')); ?>" class="nav-item c-purple">
        <i class="ti ti-chalkboard"></i> Docentes
    </a>

    <div class="nav-label">Académico</div>
    <a href="<?php echo e(route('admin.grupos.index')); ?>" class="nav-item c-teal">
        <i class="ti ti-layout-grid"></i> Grupos
    </a>
    <a href="<?php echo e(route('admin.examenes.index')); ?>" class="nav-item c-rose">
        <i class="ti ti-file-text"></i> Exámenes
    </a>
    <a href="<?php echo e(route('admin.convocatorias.index')); ?>" class="nav-item c-blue">
        <i class="ti ti-building"></i> Convocatoria
    </a>

    <div class="nav-label">Sistema</div>
    <a href="<?php echo e(route('admin.reportes.index')); ?>" class="nav-item c-purple">
        <i class="ti ti-chart-bar"></i> Reportes
    </a>

    <div class="sidebar-footer">UAGRM &copy; <?php echo e(date('Y')); ?></div>
</aside>


<div class="layout">

    <p class="page-title">Convocatoria activa — Gestión 2025</p>

    
    <div class="metrics">
        <div class="metric blue">
            <div class="metric-label"><i class="ti ti-users"></i> Postulantes</div>
            <div class="metric-value"><?php echo e($stats['total_postulantes']); ?></div>
            <div class="metric-sub">Inscritos en el sistema</div>
        </div>
        <div class="metric amber">
            <div class="metric-label"><i class="ti ti-clock"></i> Pre-registros</div>
            <div class="metric-value"><?php echo e($stats['pre_reg_pendientes']); ?></div>
            <div class="metric-sub" style="color:#b45309">Pendientes de revisión</div>
        </div>
        <div class="metric teal">
            <div class="metric-label"><i class="ti ti-layout-grid"></i> Grupos activos</div>
            <div class="metric-value"><?php echo e($stats['total_grupos']); ?></div>
            <div class="metric-sub">Creados en el sistema</div>
        </div>
        <div class="metric purple">
            <div class="metric-label"><i class="ti ti-chalkboard"></i> Docentes</div>
            <div class="metric-value"><?php echo e($stats['total_docentes']); ?></div>
            <div class="metric-sub">Registrados</div>
        </div>
    </div>

    <div class="two-col">

        
        <div class="card">
            <div class="card-title">
                Pre-registros pendientes
                <a href="<?php echo e(route('admin.pre-registros.index')); ?>">Ver todos →</a>
            </div>
            <?php if($pendientes->isEmpty()): ?>
                <div class="empty">
                    <i class="ti ti-circle-check" style="font-size:32px;display:block;margin-bottom:8px"></i>
                    No hay pre-registros pendientes
                </div>
            <?php else: ?>
                <table>
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Turno</th>
                            <th>Docs</th>
                            <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $pendientes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pre): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td>
                                <div style="display:flex;align-items:center;gap:8px">
                                    <div class="avatar av-blue">
                                        <?php echo e(strtoupper(substr($pre->nombre,0,1))); ?><?php echo e(strtoupper(substr($pre->apellido,0,1))); ?>

                                    </div>
                                    <div>
                                        <div style="font-weight:500"><?php echo e($pre->nombre); ?> <?php echo e($pre->apellido); ?></div>
                                        <div style="color:#94a3b8;font-size:11px"><?php echo e($pre->email); ?></div>
                                    </div>
                                </div>
                            </td>
                            <td><span class="badge badge-blue"><?php echo e($pre->turno_preferido); ?></span></td>
                            <td style="color:#64748b">—/3</td>
                            <td><a href="<?php echo e(route('admin.pre-registros.estudiante.show', $pre->id)); ?>" class="btn btn-sm-ok">Ver / Aprobar</a></td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>

        
        <div class="card">
            <div class="card-title">Acciones rápidas</div>
            <div class="acciones">
                <a href="<?php echo e(route('admin.grupos.index')); ?>" class="accion-btn">
                    <i class="ti ti-layout-grid" style="color:#1e3a6e;font-size:18px"></i>
                    Generar grupos
                </a>
                <a href="<?php echo e(route('admin.docentes.index')); ?>" class="accion-btn">
                    <i class="ti ti-user-check" style="color:#065f46;font-size:18px"></i>
                    Asignar docentes
                </a>
                <a href="<?php echo e(route('admin.reportes.index')); ?>" class="accion-btn">
                    <i class="ti ti-chart-bar" style="color:#5b21b6;font-size:18px"></i>
                    Ver reportes
                </a>
                <a href="<?php echo e(route('admin.resultados.admision')); ?>" class="accion-btn">
                    <i class="ti ti-trophy" style="color:#92400e;font-size:18px"></i>
                    Ejecutar admisión
                </a>
                <a href="<?php echo e(route('admin.carga-masiva.index')); ?>" class="accion-btn">
                    <i class="ti ti-upload" style="color:#1e3a6e;font-size:18px"></i>
                    Carga masiva CSV
                </a>
                <a href="<?php echo e(route('admin.convocatorias.index')); ?>" class="accion-btn">
                    <i class="ti ti-building" style="color:#065f46;font-size:18px"></i>
                    Nueva convocatoria
                </a>
            </div>
        </div>
    </div>

    
    <div class="card">
        <div class="card-title">
            Registro de actividad reciente
            <a href="#">Ver todo →</a>
        </div>
        <?php if($logs->isEmpty()): ?>
            <div class="empty">
                <i class="ti ti-list" style="font-size:32px;display:block;margin-bottom:8px"></i>
                No hay actividad registrada aún
            </div>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Fecha y hora</th>
                        <th>Usuario</th>
                        <th>Rol</th>
                        <th>Acción</th>
                        <th>Descripción</th>
                        <th>IP</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $logs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td style="color:#94a3b8"><?php echo e($log->id); ?></td>
                        <td>
                            <?php echo e(\Carbon\Carbon::parse($log->fecha_hora)->format('d/m/Y')); ?><br>
                            <span style="color:#94a3b8;font-size:11px"><?php echo e(\Carbon\Carbon::parse($log->fecha_hora)->format('H:i:s')); ?></span>
                        </td>
                        <td>
                            <?php echo e($log->usuario_nombre); ?><br>
                            <span style="color:#94a3b8;font-size:11px"><?php echo e($log->usuario_email); ?></span>
                        </td>
                        <td>
                            <span class="badge <?php echo e($log->rol == 'ADMINISTRATIVO' ? 'badge-blue' : ($log->rol == 'DOCENTE' ? 'badge-doc' : 'badge-ok')); ?>">
                                <?php echo e($log->rol); ?>

                            </span>
                        </td>
                        <td>
                            <span class="badge <?php echo e($log->resultado == 'ok' ? 'badge-ok' : 'badge-err'); ?>">
                                <?php echo e(str_replace('_', ' ', $log->accion)); ?>

                            </span>
                        </td>
                        <td style="color:#64748b;max-width:200px"><?php echo e($log->descripcion); ?></td>
                        <td style="color:#94a3b8"><?php echo e($log->ip); ?></td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>

</div>
</body>
</html><?php /**PATH C:\Users\pc\Desktop\Jair torrico\semestre\sistemas de informacion\CUP-main\resources\views/admin/dashboard.blade.php ENDPATH**/ ?>