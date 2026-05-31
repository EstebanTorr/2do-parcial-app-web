<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Convocatoria — CUP FICCT</title>
    <link href="https://fonts.googleapis.com/css2?family=Figtree:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Figtree', sans-serif; background: #f1f5f9; color: #1e293b; }

        /* ── TOPBAR ── */
        .topbar { background: #1e3a6e; padding: 0 24px; height: 56px; display: flex; align-items: center; justify-content: space-between; position: fixed; top: 0; left: 0; right: 0; z-index: 200; }
        .topbar-brand { color: #fff; font-size: 16px; font-weight: 600; display: flex; align-items: center; gap: 10px; text-decoration: none; }
        .topbar-right { display: flex; align-items: center; gap: 16px; }
        .topbar-user { color: #a8c8f0; font-size: 13px; display: flex; align-items: center; gap: 6px; }
        .btn-logout { background: rgba(255,255,255,0.12); border: 1px solid rgba(255,255,255,0.22); color: #fff; padding: 6px 14px; border-radius: 8px; font-size: 12px; cursor: pointer; text-decoration: none; display: flex; align-items: center; gap: 6px; }
        .btn-logout:hover { background: rgba(255,255,255,0.22); }

        /* ── SIDEBAR ── */
        .sidebar { width: 224px; height: calc(100vh - 56px); background: #1e3a6e; position: fixed; top: 56px; left: 0; overflow-y: auto; padding: 20px 12px 24px; display: flex; flex-direction: column; gap: 2px; }
        .nav-label { font-size: 10px; font-weight: 700; color: rgba(168,200,240,0.55); text-transform: uppercase; letter-spacing: .1em; padding: 16px 10px 6px; }
        .nav-label:first-child { padding-top: 4px; }
        .nav-item { padding: 9px 12px; font-size: 13px; color: rgba(168,200,240,0.85); text-decoration: none; border-radius: 8px; display: flex; align-items: center; gap: 10px; transition: background .15s; font-weight: 400; }
        .nav-item i { font-size: 16px; flex-shrink: 0; }
        .nav-item:hover { background: rgba(255,255,255,0.1); color: #fff; }
        .nav-item.active { background: rgba(255,255,255,0.15); color: #fff; font-weight: 500; }
        .nav-item.c-blue   i { color: #93c5fd; }
        .nav-item.c-amber  i { color: #fcd34d; }
        .nav-item.c-teal   i { color: #6ee7b7; }
        .nav-item.c-purple i { color: #c4b5fd; }
        .nav-item.c-rose   i { color: #fda4af; }
        .nav-item.c-sky    i { color: #7dd3fc; }
        .sidebar-footer { margin-top: auto; padding: 16px 10px 0; font-size: 11px; color: rgba(168,200,240,0.4); }

        /* ── LAYOUT ── */
        .main { margin-left: 224px; padding-top: 56px; min-height: 100vh; }
        .page { padding: 28px; max-width: 760px; }

        /* ── MIGAS ── */
        .breadcrumb { display: flex; align-items: center; gap: 6px; font-size: 12px; color: #94a3b8; margin-bottom: 18px; }
        .breadcrumb a { color: #94a3b8; text-decoration: none; }
        .breadcrumb a:hover { color: #1e3a6e; }
        .breadcrumb i { font-size: 10px; }

        /* ── PAGE HEADER ── */
        .page-header { margin-bottom: 24px; }
        .page-title { font-size: 20px; font-weight: 600; color: #1e293b; display: flex; align-items: center; gap: 8px; }
        .page-sub { font-size: 13px; color: #94a3b8; margin-top: 3px; }

        /* ── BADGE ESTADO ── */
        .badge { display: inline-flex; align-items: center; gap: 5px; padding: 4px 12px; border-radius: 99px; font-size: 12px; font-weight: 600; }
        .badge-activa    { background: #d1fae5; color: #065f46; border: 1px solid #6ee7b7; }
        .badge-planif    { background: #dbeafe; color: #1e40af; border: 1px solid #93c5fd; }
        .badge-concluida { background: #f1f5f9; color: #64748b; border: 1px solid #e2e8f0; }

        /* ── ALERTAS ── */
        .alert { padding: 12px 16px; border-radius: 10px; margin-bottom: 20px; font-size: 13px; display: flex; align-items: center; gap: 8px; }
        .alert-ok   { background: #d1fae5; color: #065f46; border: 1px solid #6ee7b7; }
        .alert-err  { background: #fee2e2; color: #991b1b; border: 1px solid #fca5a5; }
        .alert-info { background: #eff6ff; color: #1e40af; border: 1px solid #bfdbfe; }
        .alert-warn { background: #fffbeb; color: #92400e; border: 1px solid #fde68a; }

        /* ── FORMULARIO ── */
        .form-card { background: #fff; border-radius: 12px; border: 1px solid #e2e8f0; padding: 28px; }
        .form-card h3 { font-size: 15px; font-weight: 600; margin-bottom: 22px; padding-bottom: 14px; border-bottom: 1px solid #f1f5f9; display: flex; align-items: center; gap: 8px; color: #1e293b; }

        .grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
        .grid-3 { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 16px; }
        .span-2 { grid-column: span 2; }

        .field { display: flex; flex-direction: column; gap: 5px; margin-bottom: 16px; }
        .field label { font-size: 11px; font-weight: 700; color: #475569; text-transform: uppercase; letter-spacing: .05em; }
        .field input, .field select, .field textarea {
            padding: 9px 13px; border: 1px solid #e2e8f0; border-radius: 8px;
            font-size: 13px; color: #1e293b; font-family: 'Figtree', sans-serif;
            background: #f8fafc; width: 100%; transition: border-color .15s, background .15s;
        }
        .field input:focus, .field select:focus, .field textarea:focus {
            outline: none; border-color: #1e3a6e; background: #fff; box-shadow: 0 0 0 3px rgba(30,58,110,0.08);
        }
        .field textarea { resize: vertical; min-height: 80px; }
        .field-error { font-size: 11px; color: #ef4444; margin-top: 2px; }

        /* ── ESTADO VISUAL READONLY ── */
        .estado-box { background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px; padding: 14px 16px; display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px; }
        .estado-box-label { font-size: 12px; color: #64748b; font-weight: 500; }

        /* ── ACCIONES ── */
        .form-actions { display: flex; align-items: center; gap: 10px; margin-top: 24px; padding-top: 20px; border-top: 1px solid #f1f5f9; }
        .btn-primary { padding: 10px 24px; background: #1e3a6e; color: #fff; border: none; border-radius: 8px; font-size: 13px; font-weight: 600; cursor: pointer; font-family: 'Figtree', sans-serif; display: inline-flex; align-items: center; gap: 8px; transition: background .15s; }
        .btn-primary:hover { background: #0f2147; }
        .btn-cancel { padding: 10px 20px; background: #f1f5f9; border: 1px solid #e2e8f0; color: #475569; border-radius: 8px; font-size: 13px; font-weight: 500; cursor: pointer; font-family: 'Figtree', sans-serif; text-decoration: none; display: inline-flex; align-items: center; gap: 6px; transition: background .15s; }
        .btn-cancel:hover { background: #e2e8f0; }
    </style>
</head>
<body>


<div class="topbar">
    <a href="<?php echo e(route('admin.dashboard')); ?>" class="topbar-brand">
        <i class="ti ti-school" style="font-size:20px"></i> CUP — FICCT
    </a>
    <div class="topbar-right">
        <span class="topbar-user">
            <i class="ti ti-user-circle" style="font-size:16px"></i>
            <?php echo e(Auth::user()->nombre); ?> <?php echo e(Auth::user()->apellido); ?>

        </span>
        <form method="POST" action="<?php echo e(route('logout')); ?>" style="display:inline">
            <?php echo csrf_field(); ?>
            <button type="submit" class="btn-logout"><i class="ti ti-logout"></i> Salir</button>
        </form>
    </div>
</div>


<aside class="sidebar">
    <div class="nav-label">Menú</div>
    <a href="<?php echo e(route('admin.dashboard')); ?>" class="nav-item c-sky"><i class="ti ti-home"></i> Inicio</a>
    <a href="<?php echo e(route('admin.pre-registros.index')); ?>" class="nav-item c-amber"><i class="ti ti-clock"></i> Pre-registros</a>
    <a href="<?php echo e(route('admin.postulantes.index')); ?>" class="nav-item c-blue"><i class="ti ti-id-badge"></i> Postulantes</a>
    <a href="<?php echo e(route('admin.docentes.index')); ?>" class="nav-item c-purple"><i class="ti ti-chalkboard"></i> Docentes</a>
    <div class="nav-label">Académico</div>
    <a href="<?php echo e(route('admin.grupos.index')); ?>" class="nav-item c-teal"><i class="ti ti-layout-grid"></i> Grupos</a>
    <a href="<?php echo e(route('admin.examenes.index')); ?>" class="nav-item c-rose"><i class="ti ti-file-text"></i> Exámenes</a>
    <a href="<?php echo e(route('admin.convocatorias.index')); ?>" class="nav-item active"><i class="ti ti-building"></i> Convocatoria</a>
    <div class="nav-label">Sistema</div>
    <a href="<?php echo e(route('admin.reportes.index')); ?>" class="nav-item c-purple"><i class="ti ti-chart-bar"></i> Reportes</a>
    <div class="sidebar-footer">UAGRM &copy; <?php echo e(date('Y')); ?></div>
</aside>


<main class="main">
<div class="page">

    
    <div class="breadcrumb">
        <a href="<?php echo e(route('admin.convocatorias.index')); ?>">Convocatorias</a>
        <i class="ti ti-chevron-right"></i>
        <span>Editar</span>
    </div>

    
    <div class="page-header">
        <div class="page-title">
            <i class="ti ti-building" style="color:#1e3a6e"></i>
            Editar Convocatoria
        </div>
        <div class="page-sub">Modifica los datos de la convocatoria seleccionada</div>
    </div>

    
    <?php if(session('success')): ?>
        <div class="alert alert-ok"><i class="ti ti-circle-check"></i> <?php echo e(session('success')); ?></div>
    <?php endif; ?>
    <?php if(session('error')): ?>
        <div class="alert alert-err"><i class="ti ti-alert-circle"></i> <?php echo e(session('error')); ?></div>
    <?php endif; ?>
    <?php if($errors->any()): ?>
        <div class="alert alert-err">
            <i class="ti ti-alert-triangle"></i>
            <div>
                <strong>Corrige los siguientes errores:</strong>
                <ul style="margin-top:6px;padding-left:16px;font-size:12px">
                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li><?php echo e($error); ?></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>
        </div>
    <?php endif; ?>

    
    <?php if($convocatoria->estado === 'ACTIVA'): ?>
        <div class="alert alert-warn">
            <i class="ti ti-alert-triangle"></i>
            <div>
                <strong>Convocatoria ACTIVA:</strong> Puedes modificar los datos, pero ten cuidado porque los postulantes ya pueden estar inscritos.
            </div>
        </div>
    <?php elseif($convocatoria->estado === 'CONCLUIDA'): ?>
        <div class="alert alert-info">
            <i class="ti ti-info-circle"></i>
            Esta convocatoria está <strong>CONCLUIDA</strong>. Solo puedes consultar sus datos.
        </div>
    <?php endif; ?>

    
    <div class="form-card">
        <h3><i class="ti ti-pencil" style="color:#1e3a6e"></i> Datos de la Convocatoria</h3>

        
        <div class="estado-box">
            <span class="estado-box-label">Estado actual de la convocatoria:</span>
            <span class="badge <?php echo e($convocatoria->estado === 'ACTIVA' ? 'badge-activa' : ($convocatoria->estado === 'PLANIFICADA' ? 'badge-planif' : 'badge-concluida')); ?>">
                <i class="ti <?php echo e($convocatoria->estado === 'ACTIVA' ? 'ti-circle-check' : ($convocatoria->estado === 'PLANIFICADA' ? 'ti-clock' : 'ti-circle-x')); ?>"></i>
                <?php echo e($convocatoria->estado); ?>

            </span>
        </div>

        <?php if($convocatoria->estado !== 'CONCLUIDA'): ?>
        <form method="POST" action="<?php echo e(route('admin.convocatorias.update', $convocatoria->id)); ?>">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PATCH'); ?>

            
            <div class="field">
                <label>Nombre de la convocatoria *</label>
                <input type="text" name="nombre" value="<?php echo e(old('nombre', $convocatoria->nombre)); ?>" required placeholder="Ej: Gestión 2026 — Primer Semestre">
                <?php $__errorArgs = ['nombre'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="field-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            
            <div class="grid-3">
                <div class="field">
                    <label>Fecha inicio *</label>
                    <input type="date" name="fecha_inicio" value="<?php echo e(old('fecha_inicio', \Carbon\Carbon::parse($convocatoria->fecha_inicio)->format('Y-m-d'))); ?>" required>
                    <?php $__errorArgs = ['fecha_inicio'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="field-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div class="field">
                    <label>Fecha fin *</label>
                    <input type="date" name="fecha_fin" value="<?php echo e(old('fecha_fin', \Carbon\Carbon::parse($convocatoria->fecha_fin)->format('Y-m-d'))); ?>" required>
                    <?php $__errorArgs = ['fecha_fin'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="field-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div class="field">
                    <label>Monto de pago (Bs.) *</label>
                    <input type="number" name="monto_pago" value="<?php echo e(old('monto_pago', $convocatoria->monto_pago)); ?>" step="0.01" min="0" required>
                    <?php $__errorArgs = ['monto_pago'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="field-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>

            
            <div class="grid-2">
                <div class="field">
                    <label>Cupo total</label>
                    <input type="number" name="cupo_total" value="<?php echo e(old('cupo_total', $convocatoria->cupo_total)); ?>" min="1">
                    <?php $__errorArgs = ['cupo_total'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="field-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>

            
            <div class="form-actions">
                <button type="submit" class="btn-primary">
                    <i class="ti ti-device-floppy"></i> Guardar Cambios
                </button>
                <a href="<?php echo e(route('admin.convocatorias.index')); ?>" class="btn-cancel">
                    <i class="ti ti-x"></i> Cancelar
                </a>
            </div>
        </form>
        <?php else: ?>
            <div class="alert alert-info" style="margin-top:16px">
                <i class="ti ti-lock"></i>
                Esta convocatoria no puede editarse porque está <strong>CONCLUIDA</strong>.
            </div>
            <div style="margin-top:16px">
                <a href="<?php echo e(route('admin.convocatorias.index')); ?>" class="btn-cancel">
                    <i class="ti ti-arrow-left"></i> Volver a Convocatorias
                </a>
            </div>
        <?php endif; ?>
    </div>

</div>
</main>

</body>
</html>
<?php /**PATH C:\Users\pc\Desktop\Jair torrico\semestre\sistemas de informacion\CUP-main\resources\views/admin/convocatorias/editar.blade.php ENDPATH**/ ?>