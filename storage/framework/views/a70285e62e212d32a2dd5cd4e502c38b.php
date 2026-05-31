<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle Pre-registro — CUP FICCT</title>
    <link href="https://fonts.googleapis.com/css2?family=Figtree:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Figtree', sans-serif; background: #f1f5f9; color: #1e293b; }

        /* ── TOPBAR ── */
        .topbar {
            background: #1e3a6e; padding: 0 24px; height: 56px;
            display: flex; align-items: center; justify-content: space-between;
            position: fixed; top: 0; left: 0; right: 0; z-index: 200;
        }
        .topbar-brand { color: #fff; font-size: 16px; font-weight: 600; display: flex; align-items: center; gap: 10px; text-decoration: none; }
        .topbar-right { display: flex; align-items: center; gap: 16px; }
        .topbar-user { color: #a8c8f0; font-size: 13px; display: flex; align-items: center; gap: 6px; }
        .btn-logout { background: rgba(255,255,255,0.12); border: 1px solid rgba(255,255,255,0.22); color: #fff; padding: 6px 14px; border-radius: 8px; font-size: 12px; cursor: pointer; text-decoration: none; display: flex; align-items: center; gap: 6px; transition: background .2s; }
        .btn-logout:hover { background: rgba(255,255,255,0.22); }

        /* ── SIDEBAR ── */
        .sidebar {
            width: 224px; height: calc(100vh - 56px);
            background: #1e3a6e; position: fixed; top: 56px; left: 0;
            overflow-y: auto; padding: 20px 12px 24px;
            display: flex; flex-direction: column; gap: 2px;
        }
        .nav-label { font-size: 10px; font-weight: 700; color: rgba(168,200,240,0.55); text-transform: uppercase; letter-spacing: .1em; padding: 16px 10px 6px; }
        .nav-label:first-child { padding-top: 4px; }
        .nav-item { padding: 9px 12px; font-size: 13px; color: rgba(168,200,240,0.85); text-decoration: none; border-radius: 8px; display: flex; align-items: center; gap: 10px; transition: background .15s, color .15s; font-weight: 400; }
        .nav-item i { font-size: 16px; flex-shrink: 0; }
        .nav-item:hover { background: rgba(255,255,255,0.1); color: #fff; }
        .nav-item.active { background: rgba(255,255,255,0.15); color: #fff; font-weight: 500; }
        .nav-item.active i { color: #7dd3fc; }
        .nav-item.c-blue   i { color: #93c5fd; }
        .nav-item.c-amber  i { color: #fcd34d; }
        .nav-item.c-teal   i { color: #6ee7b7; }
        .nav-item.c-purple i { color: #c4b5fd; }
        .nav-item.c-rose   i { color: #fda4af; }
        .nav-item.c-sky    i { color: #7dd3fc; }
        .sidebar-footer { margin-top: auto; padding: 16px 10px 0; font-size: 11px; color: rgba(168,200,240,0.4); }

        /* ── MAIN ── */
        .main { margin-left: 224px; padding-top: 56px; min-height: 100vh; }
        .page { padding: 28px; max-width: 960px; }

        /* ── BREADCRUMB ── */
        .breadcrumb { display: flex; align-items: center; gap: 6px; font-size: 13px; color: #94a3b8; margin-bottom: 20px; }
        .breadcrumb a { color: #94a3b8; text-decoration: none; }
        .breadcrumb a:hover { color: #1e3a6e; }
        .breadcrumb span { color: #1e293b; font-weight: 500; }

        /* ── PAGE HEADER ── */
        .page-header { display: flex; align-items: flex-start; justify-content: space-between; margin-bottom: 24px; flex-wrap: wrap; gap: 12px; }
        .header-left { display: flex; align-items: center; gap: 16px; }
        .avatar-lg { width: 52px; height: 52px; border-radius: 50%; background: #dbeafe; color: #1e40af; display: flex; align-items: center; justify-content: center; font-size: 20px; font-weight: 600; flex-shrink: 0; }
        .page-title { font-size: 20px; font-weight: 600; color: #1e293b; }
        .page-sub { font-size: 13px; color: #94a3b8; margin-top: 3px; display: flex; align-items: center; gap: 8px; }
        .header-actions { display: flex; gap: 8px; align-items: center; flex-wrap: wrap; }

        /* ── BADGES ── */
        .badge { display: inline-flex; align-items: center; padding: 4px 12px; border-radius: 99px; font-size: 12px; font-weight: 500; }
        .badge-pend  { background: #fef3c7; color: #92400e; }
        .badge-aprov { background: #d1fae5; color: #065f46; }
        .badge-rech  { background: #fee2e2; color: #991b1b; }

        /* ── BOTONES ── */
        .btn { padding: 8px 16px; border-radius: 8px; font-size: 13px; cursor: pointer; border: none; font-family: 'Figtree', sans-serif; text-decoration: none; display: inline-flex; align-items: center; gap: 6px; font-weight: 500; }
        .btn-back   { background: #f1f5f9; color: #475569; border: 1px solid #e2e8f0; }
        .btn-back:hover { background: #e2e8f0; }
        .btn-ok   { background: #d1fae5; color: #065f46; border: 1px solid #6ee7b7; }
        .btn-ok:hover { background: #a7f3d0; }
        .btn-err  { background: #fee2e2; color: #991b1b; border: 1px solid #fca5a5; }
        .btn-err:hover { background: #fecaca; }

        /* ── CARDS ── */
        .card { background: #fff; border-radius: 12px; border: 1px solid #e2e8f0; margin-bottom: 16px; overflow: hidden; }
        .card-header { padding: 14px 20px; border-bottom: 1px solid #f1f5f9; display: flex; align-items: center; gap: 8px; }
        .card-header-title { font-size: 13px; font-weight: 600; color: #374151; }
        .card-header i { font-size: 16px; color: #94a3b8; }
        .card-body { padding: 20px; }

        /* ── GRID DE CAMPOS ── */
        .fields-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; }
        .fields-grid.cols-2 { grid-template-columns: repeat(2, 1fr); }
        .field { display: flex; flex-direction: column; gap: 3px; }
        .field-label { font-size: 10px; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: .05em; }
        .field-value { font-size: 13px; color: #1e293b; font-weight: 500; }
        .field-value.empty { color: #cbd5e1; font-weight: 400; font-style: italic; }

        /* ── DOCUMENTOS ── */
        .docs-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 12px; }
        .doc-card { border: 1px solid #e2e8f0; border-radius: 10px; padding: 14px 16px; display: flex; align-items: center; gap: 12px; transition: border-color .15s; }
        .doc-card:hover { border-color: #93c5fd; }
        .doc-icon { width: 38px; height: 38px; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 20px; flex-shrink: 0; }
        .doc-icon.pdf { background: #fee2e2; color: #991b1b; }
        .doc-icon.img { background: #dbeafe; color: #1e40af; }
        .doc-info { flex: 1; min-width: 0; }
        .doc-tipo { font-size: 11px; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: .04em; margin-bottom: 2px; }
        .doc-nombre { font-size: 12px; color: #374151; font-weight: 500; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .doc-size { font-size: 11px; color: #94a3b8; }
        .doc-link { color: #1e40af; font-size: 12px; text-decoration: none; display: flex; align-items: center; gap: 4px; flex-shrink: 0; }
        .doc-link:hover { text-decoration: underline; }
        .doc-missing { background: #f8fafc; color: #94a3b8; border-style: dashed; }
        .doc-missing .doc-icon { background: #f1f5f9; color: #cbd5e1; }
        .doc-missing .doc-nombre { color: #94a3b8; font-style: italic; }

        /* ── OBSERVACION ── */
        .obs-box { background: #fef3c7; border: 1px solid #fcd34d; border-radius: 8px; padding: 12px 14px; font-size: 13px; color: #92400e; }

        /* ── ALERTAS ── */
        .alert { padding: 12px 16px; border-radius: 10px; margin-bottom: 16px; font-size: 13px; display: flex; align-items: center; gap: 8px; }
        .alert-ok  { background: #d1fae5; color: #065f46; border: 1px solid #6ee7b7; }
        .alert-err { background: #fee2e2; color: #991b1b; border: 1px solid #fca5a5; }

        /* ── MODAL ── */
        .modal-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.45); z-index: 999; align-items: center; justify-content: center; }
        .modal-overlay.open { display: flex; }
        .modal { background: #fff; border-radius: 14px; padding: 24px; width: 100%; max-width: 460px; }
        .modal-title { font-size: 15px; font-weight: 600; color: #1e293b; margin-bottom: 16px; display: flex; align-items: center; gap: 8px; }
        .modal textarea { width: 100%; padding: 10px 14px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 13px; font-family: 'Figtree', sans-serif; resize: vertical; min-height: 80px; }
        .modal-footer { display: flex; gap: 8px; margin-top: 16px; justify-content: flex-end; }
        .btn-cancel  { padding: 8px 18px; background: #f1f5f9; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 13px; cursor: pointer; font-family: 'Figtree', sans-serif; }
        .btn-rechazar { padding: 8px 18px; background: #991b1b; color: #fff; border: none; border-radius: 8px; font-size: 13px; cursor: pointer; font-family: 'Figtree', sans-serif; }

        /* ── MODAL IMAGEN ── */
        .img-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.85); z-index: 1000; align-items: center; justify-content: center; flex-direction: column; gap: 16px; }
        .img-overlay.open { display: flex; }
        .img-overlay img { max-width: 90vw; max-height: 82vh; border-radius: 8px; box-shadow: 0 25px 50px rgba(0,0,0,0.4); }
        .img-overlay-close { color: #fff; font-size: 13px; cursor: pointer; display: flex; align-items: center; gap: 6px; opacity: .8; }
        .img-overlay-close:hover { opacity: 1; }

        @media(max-width: 700px) {
            .fields-grid, .fields-grid.cols-2 { grid-template-columns: 1fr 1fr; }
            .docs-grid { grid-template-columns: 1fr; }
        }
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
            <button type="submit" class="btn-logout">
                <i class="ti ti-logout"></i> Salir
            </button>
        </form>
    </div>
</div>


<aside class="sidebar">
    <div class="nav-label">Menú</div>
    <a href="<?php echo e(route('admin.dashboard')); ?>" class="nav-item c-sky">
        <i class="ti ti-home"></i> Inicio
    </a>
    <a href="<?php echo e(route('admin.pre-registros.index')); ?>" class="nav-item active">
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


<main class="main">
<div class="page">

    
    <div class="breadcrumb">
        <a href="<?php echo e(route('admin.pre-registros.index')); ?>"><i class="ti ti-arrow-left" style="font-size:14px"></i> Pre-registros</a>
        <span style="color:#cbd5e1">/</span>
        <span>Detalle Estudiante #<?php echo e($pre->id); ?></span>
    </div>

    
    <?php if(session('success')): ?>
        <div class="alert alert-ok"><i class="ti ti-circle-check"></i> <?php echo e(session('success')); ?></div>
    <?php endif; ?>
    <?php if(session('error')): ?>
        <div class="alert alert-err"><i class="ti ti-alert-circle"></i> <?php echo e(session('error')); ?></div>
    <?php endif; ?>

    
    <div class="page-header">
        <div class="header-left">
            <div class="avatar-lg">
                <?php echo e(strtoupper(substr($pre->nombre,0,1))); ?><?php echo e(strtoupper(substr($pre->apellido,0,1))); ?>

            </div>
            <div>
                <div class="page-title"><?php echo e($pre->nombre); ?> <?php echo e($pre->apellido); ?></div>
                <div class="page-sub">
                    <span><?php echo e($pre->email); ?></span>
                    <span class="badge <?php echo e($pre->estado=='PENDIENTE'?'badge-pend':($pre->estado=='APROBADO'?'badge-aprov':'badge-rech')); ?>">
                        <?php echo e($pre->estado); ?>

                    </span>
                </div>
            </div>
        </div>
        <div class="header-actions">
            <a href="<?php echo e(route('admin.pre-registros.index')); ?>" class="btn btn-back">
                <i class="ti ti-arrow-left"></i> Volver
            </a>
            <?php if($pre->estado === 'PENDIENTE'): ?>
                <form method="POST" action="<?php echo e(route('admin.pre-registros.estudiante.aprobar', $pre->id)); ?>"
                      onsubmit="return confirm('¿Aprobar a <?php echo e($pre->nombre); ?> <?php echo e($pre->apellido); ?>?')">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="btn btn-ok">
                        <i class="ti ti-check"></i> Aprobar
                    </button>
                </form>
                <button class="btn btn-err" onclick="abrirModal()">
                    <i class="ti ti-x"></i> Rechazar
                </button>
            <?php endif; ?>
        </div>
    </div>

    
    <div class="card">
        <div class="card-header">
            <i class="ti ti-user"></i>
            <div class="card-header-title">Datos Personales</div>
        </div>
        <div class="card-body">
            <div class="fields-grid">
                <div class="field">
                    <div class="field-label">Nombres</div>
                    <div class="field-value"><?php echo e($pre->nombre); ?></div>
                </div>
                <div class="field">
                    <div class="field-label">Apellidos</div>
                    <div class="field-value"><?php echo e($pre->apellido); ?></div>
                </div>
                <div class="field">
                    <div class="field-label">CI</div>
                    <div class="field-value"><?php echo e($pre->ci); ?>-<?php echo e($pre->ci_extension); ?></div>
                </div>
                <div class="field">
                    <div class="field-label">Fecha de Nacimiento</div>
                    <div class="field-value">
                        <?php echo e($pre->fecha_nacimiento ? \Carbon\Carbon::parse($pre->fecha_nacimiento)->format('d/m/Y') : '—'); ?>

                    </div>
                </div>
                <div class="field">
                    <div class="field-label">Sexo</div>
                    <div class="field-value"><?php echo e($pre->sexo ?? '—'); ?></div>
                </div>
                <div class="field">
                    <div class="field-label">Teléfono</div>
                    <div class="field-value <?php echo e(!$pre->telefono ? 'empty' : ''); ?>"><?php echo e($pre->telefono ?? 'No indicado'); ?></div>
                </div>
                <div class="field">
                    <div class="field-label">Correo Electrónico</div>
                    <div class="field-value"><?php echo e($pre->email); ?></div>
                </div>
                <div class="field">
                    <div class="field-label">Ciudad</div>
                    <div class="field-value"><?php echo e($pre->ciudad ?? '—'); ?></div>
                </div>
                <div class="field">
                    <div class="field-label">Dirección</div>
                    <div class="field-value <?php echo e(!$pre->direccion ? 'empty' : ''); ?>"><?php echo e($pre->direccion ?? 'No indicada'); ?></div>
                </div>
            </div>
        </div>
    </div>

    
    <div class="card">
        <div class="card-header">
            <i class="ti ti-school"></i>
            <div class="card-header-title">Datos Académicos</div>
        </div>
        <div class="card-body">
            <div class="fields-grid">
                <div class="field">
                    <div class="field-label">Colegio de Procedencia</div>
                    <div class="field-value"><?php echo e($pre->colegio_nombre ?? '—'); ?></div>
                </div>
                <div class="field">
                    <div class="field-label">Tipo de Colegio</div>
                    <div class="field-value"><?php echo e($pre->colegio_tipo ?? '—'); ?></div>
                </div>
                <div class="field">
                    <div class="field-label">Año de Egreso</div>
                    <div class="field-value"><?php echo e($pre->anio_egreso ?? '—'); ?></div>
                </div>
                <div class="field">
                    <div class="field-label">Carrera 1ª Opción</div>
                    <div class="field-value"><?php echo e($c1?->nombre ?? '—'); ?></div>
                </div>
                <div class="field">
                    <div class="field-label">Carrera 2ª Opción</div>
                    <div class="field-value"><?php echo e($c2?->nombre ?? '—'); ?></div>
                </div>
                <div class="field">
                    <div class="field-label">Turno Preferido</div>
                    <div class="field-value"><?php echo e($pre->turno_preferido ?? '—'); ?></div>
                </div>
            </div>
        </div>
    </div>

    
    <div class="card">
        <div class="card-header">
            <i class="ti ti-paperclip"></i>
            <div class="card-header-title">Documentos adjuntos (<?php echo e($docs->count()); ?>/4)</div>
        </div>
        <div class="card-body">
            <?php
                $tiposEsperados = [
                    'CI'               => ['label' => 'Cédula de Identidad',    'icon' => 'ti-id'],
                    'TITULO_BACHILLER' => ['label' => 'Título de Bachiller',    'icon' => 'ti-certificate'],
                    'BOLETA_COLEGIO'   => ['label' => 'Boleta del Colegio',     'icon' => 'ti-file-description'],
                    'COMPROBANTE_PAGO' => ['label' => 'Comprobante de Pago',    'icon' => 'ti-receipt'],
                ];
                $docsPorTipo = $docs->keyBy('tipo');
            ?>

            <div class="docs-grid">
                <?php $__currentLoopData = $tiposEsperados; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tipo => $meta): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php $doc = $docsPorTipo->get($tipo); ?>

                    <?php if($doc): ?>
                        <?php
                            $ext = strtolower(pathinfo($doc->nombre_archivo, PATHINFO_EXTENSION));
                            $esPdf = $ext === 'pdf';
                            $urlDoc = Storage::url($doc->ruta_servidor);
                            $tamano = $doc->tamanio_bytes ? round($doc->tamanio_bytes / 1024, 1) . ' KB' : '';
                        ?>
                        <div class="doc-card">
                            <div class="doc-icon <?php echo e($esPdf ? 'pdf' : 'img'); ?>">
                                <i class="ti <?php echo e($esPdf ? 'ti-file-type-pdf' : 'ti-photo'); ?>"></i>
                            </div>
                            <div class="doc-info">
                                <div class="doc-tipo"><?php echo e($meta['label']); ?></div>
                                <div class="doc-nombre"><?php echo e($doc->nombre_archivo); ?></div>
                                <?php if($tamano): ?>
                                    <div class="doc-size"><?php echo e($tamano); ?></div>
                                <?php endif; ?>
                            </div>
                            <?php if($esPdf): ?>
                                <a href="<?php echo e($urlDoc); ?>" target="_blank" class="doc-link">
                                    <i class="ti ti-external-link"></i> Ver
                                </a>
                            <?php else: ?>
                                <a href="#" class="doc-link" onclick="verImagen('<?php echo e($urlDoc); ?>', '<?php echo e($meta['label']); ?>'); return false;">
                                    <i class="ti ti-eye"></i> Ver
                                </a>
                            <?php endif; ?>
                        </div>
                    <?php else: ?>
                        <div class="doc-card doc-missing">
                            <div class="doc-icon">
                                <i class="ti <?php echo e($meta['icon']); ?>"></i>
                            </div>
                            <div class="doc-info">
                                <div class="doc-tipo"><?php echo e($meta['label']); ?></div>
                                <div class="doc-nombre">No adjuntado</div>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    </div>

    
    <?php if($pre->estado === 'RECHAZADO' && $pre->observacion_admin): ?>
    <div class="card">
        <div class="card-header">
            <i class="ti ti-alert-circle" style="color:#991b1b"></i>
            <div class="card-header-title" style="color:#991b1b">Motivo del rechazo</div>
        </div>
        <div class="card-body">
            <div class="obs-box" style="background:#fee2e2;border-color:#fca5a5;color:#991b1b">
                <?php echo e($pre->observacion_admin); ?>

            </div>
        </div>
    </div>
    <?php endif; ?>

    
    <div class="card">
        <div class="card-header">
            <i class="ti ti-info-circle"></i>
            <div class="card-header-title">Información del Registro</div>
        </div>
        <div class="card-body">
            <div class="fields-grid cols-2">
                <div class="field">
                    <div class="field-label">Fecha de Solicitud</div>
                    <div class="field-value">
                        <?php echo e($pre->created_at ? \Carbon\Carbon::parse($pre->created_at)->format('d/m/Y H:i') : '—'); ?>

                    </div>
                </div>
                <div class="field">
                    <div class="field-label">IP de Registro</div>
                    <div class="field-value <?php echo e(!$pre->ip_registro ? 'empty' : ''); ?>"><?php echo e($pre->ip_registro ?? 'No registrada'); ?></div>
                </div>
                <?php if($pre->revisado_en): ?>
                <div class="field">
                    <div class="field-label">Revisado el</div>
                    <div class="field-value">
                        <?php echo e(\Carbon\Carbon::parse($pre->revisado_en)->format('d/m/Y H:i')); ?>

                    </div>
                </div>
                <?php endif; ?>
                <div class="field">
                    <div class="field-label">Estado Actual</div>
                    <div class="field-value">
                        <span class="badge <?php echo e($pre->estado=='PENDIENTE'?'badge-pend':($pre->estado=='APROBADO'?'badge-aprov':'badge-rech')); ?>">
                            <?php echo e($pre->estado); ?>

                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
</main>


<div class="modal-overlay" id="modal-rechazo">
    <div class="modal">
        <div class="modal-title"><i class="ti ti-alert-circle" style="color:#991b1b"></i> Motivo del rechazo</div>
        <form method="POST" action="<?php echo e(route('admin.pre-registros.estudiante.rechazar', $pre->id)); ?>">
            <?php echo csrf_field(); ?>
            <textarea name="observacion" placeholder="Indica el motivo del rechazo..." required></textarea>
            <div class="modal-footer">
                <button type="button" class="btn-cancel" onclick="cerrarModal()">Cancelar</button>
                <button type="submit" class="btn-rechazar">Confirmar rechazo</button>
            </div>
        </form>
    </div>
</div>


<div class="img-overlay" id="img-overlay" onclick="cerrarImagen()">
    <div class="img-overlay-close"><i class="ti ti-x"></i> Cerrar</div>
    <img id="img-preview" src="" alt="Documento">
</div>

<script>
function abrirModal() {
    document.getElementById('modal-rechazo').classList.add('open');
}
function cerrarModal() {
    document.getElementById('modal-rechazo').classList.remove('open');
}
function verImagen(url, titulo) {
    document.getElementById('img-preview').src = url;
    document.getElementById('img-overlay').classList.add('open');
}
function cerrarImagen() {
    document.getElementById('img-overlay').classList.remove('open');
    document.getElementById('img-preview').src = '';
}
// Cerrar con ESC
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        cerrarModal();
        cerrarImagen();
    }
});
</script>

</body>
</html><?php /**PATH C:\Users\pc\Desktop\Jair torrico\semestre\sistemas de informacion\CUP-main\resources\views/admin/pre-registros/detalle-estudiante.blade.php ENDPATH**/ ?>