<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CUP — {{ $titulo }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Figtree:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Figtree', sans-serif; background: #f1f5f9; color: #1e293b; }

        .topbar {
            background: #1e3a6e; padding: 0 24px; height: 56px;
            display: flex; align-items: center; justify-content: space-between;
            position: fixed; top: 0; left: 0; right: 0; z-index: 200;
        }
        .topbar-brand { color: #fff; font-size: 16px; font-weight: 600; display: flex; align-items: center; gap: 10px; }
        .topbar-right { display: flex; align-items: center; gap: 16px; }
        .topbar-user { color: #a8c8f0; font-size: 13px; display: flex; align-items: center; gap: 6px; }
        .btn-logout {
            background: rgba(255,255,255,0.12); border: 1px solid rgba(255,255,255,0.22);
            color: #fff; padding: 6px 14px; border-radius: 8px; font-size: 12px;
            cursor: pointer; text-decoration: none; display: flex; align-items: center; gap: 6px;
        }
        .btn-logout:hover { background: rgba(255,255,255,0.22); }

        .sidebar {
            width: 224px; height: calc(100vh - 56px);
            background: #1e3a6e; position: fixed; top: 56px; left: 0;
            overflow-y: auto; padding: 20px 12px 24px;
            display: flex; flex-direction: column; gap: 2px;
        }
        .nav-label { font-size: 10px; font-weight: 700; color: rgba(168,200,240,0.55); text-transform: uppercase; letter-spacing: .1em; padding: 16px 10px 6px; }
        .nav-label:first-child { padding-top: 4px; }
        .nav-item { padding: 9px 12px; font-size: 13px; color: rgba(168,200,240,0.85); text-decoration: none; border-radius: 8px; display: flex; align-items: center; gap: 10px; transition: background .15s, color .15s; }
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

        .main { margin-left: 224px; padding-top: 56px; min-height: 100vh; display: flex; align-items: center; justify-content: center; }
        .empty-state { text-align: center; padding: 60px 32px; }
        .empty-icon { width: 72px; height: 72px; border-radius: 20px; background: #dbeafe; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px; }
        .empty-icon i { font-size: 34px; color: #1e40af; }
        .empty-state h2 { font-size: 20px; font-weight: 600; color: #1e293b; margin-bottom: 8px; }
        .empty-state p { font-size: 14px; color: #64748b; max-width: 360px; margin: 0 auto 24px; line-height: 1.6; }
        .btn-back { display: inline-flex; align-items: center; gap: 8px; background: #1e3a6e; color: #fff; padding: 10px 20px; border-radius: 8px; font-size: 13px; text-decoration: none; font-weight: 500; }
        .btn-back:hover { background: #0f2147; }
        .badge-soon { display: inline-flex; align-items: center; gap: 6px; background: #fef3c7; color: #92400e; border: 1px solid #fde68a; border-radius: 99px; padding: 4px 12px; font-size: 12px; font-weight: 500; margin-bottom: 16px; }
    </style>
</head>
<body>

{{-- TOPBAR --}}
<div class="topbar">
    <div class="topbar-brand">
        <i class="ti ti-school" style="font-size:20px"></i>
        CUP — FICCT
    </div>
    <div class="topbar-right">
        <span class="topbar-user">
            <i class="ti ti-user-circle" style="font-size:16px"></i>
            {{ Auth::user()->nombre }} {{ Auth::user()->apellido }}
        </span>
        <form method="POST" action="{{ route('logout') }}" style="display:inline">
            @csrf
            <button type="submit" class="btn-logout">
                <i class="ti ti-logout"></i> Salir
            </button>
        </form>
    </div>
</div>

{{-- SIDEBAR --}}
<aside class="sidebar">
    <div class="nav-label">Menú</div>
    <a href="{{ route('admin.dashboard') }}" class="nav-item c-sky">
        <i class="ti ti-home"></i> Inicio
    </a>
    <a href="{{ route('admin.pre-registros.index') }}" class="nav-item c-amber {{ $titulo === 'Pre-registros' ? 'active' : '' }}">
        <i class="ti ti-clock"></i> Pre-registros
    </a>
    <a href="{{ route('admin.postulantes.index') }}" class="nav-item c-blue {{ $titulo === 'Postulantes' ? 'active' : '' }}">
        <i class="ti ti-id-badge"></i> Postulantes
    </a>
    <a href="{{ route('admin.docentes.index') }}" class="nav-item c-purple {{ $titulo === 'Docentes' ? 'active' : '' }}">
        <i class="ti ti-chalkboard"></i> Docentes
    </a>

    <div class="nav-label">Académico</div>
    <a href="{{ route('admin.grupos.index') }}" class="nav-item c-teal {{ $titulo === 'Grupos' ? 'active' : '' }}">
        <i class="ti ti-layout-grid"></i> Grupos
    </a>
    <a href="{{ route('admin.examenes.index') }}" class="nav-item c-rose {{ $titulo === 'Exámenes' ? 'active' : '' }}">
        <i class="ti ti-file-text"></i> Exámenes
    </a>
    <a href="{{ route('admin.convocatorias.index') }}" class="nav-item c-blue {{ $titulo === 'Convocatorias' ? 'active' : '' }}">
        <i class="ti ti-building"></i> Convocatoria
    </a>

    <div class="nav-label">Sistema</div>
    <a href="{{ route('admin.reportes.index') }}" class="nav-item c-purple {{ $titulo === 'Reportes' ? 'active' : '' }}">
        <i class="ti ti-chart-bar"></i> Reportes
    </a>

    <div class="sidebar-footer">UAGRM &copy; {{ date('Y') }}</div>
</aside>

{{-- CONTENIDO --}}
<main class="main">
    <div class="empty-state">
        <div class="empty-icon">
            <i class="ti {{ $icono }}"></i>
        </div>
        <div class="badge-soon">
            <i class="ti ti-clock" style="font-size:13px"></i>
            En desarrollo
        </div>
        <h2>Módulo de {{ $titulo }}</h2>
        <p>Este módulo está siendo desarrollado. Estará disponible en la próxima entrega del sistema.</p>
        <a href="{{ route('admin.dashboard') }}" class="btn-back">
            <i class="ti ti-arrow-left"></i>
            Volver al Dashboard
        </a>
    </div>
</main>

</body>
</html>