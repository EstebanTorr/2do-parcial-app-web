<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Convocatorias — CUP FICCT</title>
    <link href="https://fonts.googleapis.com/css2?family=Figtree:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Figtree', sans-serif; background: #f1f5f9; color: #1e293b; }

        .topbar { background: #1e3a6e; padding: 0 24px; height: 56px; display: flex; align-items: center; justify-content: space-between; position: fixed; top: 0; left: 0; right: 0; z-index: 200; }
        .topbar-brand { color: #fff; font-size: 16px; font-weight: 600; display: flex; align-items: center; gap: 10px; text-decoration: none; }
        .topbar-right { display: flex; align-items: center; gap: 16px; }
        .topbar-user { color: #a8c8f0; font-size: 13px; display: flex; align-items: center; gap: 6px; }
        .btn-logout { background: rgba(255,255,255,0.12); border: 1px solid rgba(255,255,255,0.22); color: #fff; padding: 6px 14px; border-radius: 8px; font-size: 12px; cursor: pointer; text-decoration: none; display: flex; align-items: center; gap: 6px; }
        .btn-logout:hover { background: rgba(255,255,255,0.22); }

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

        .main { margin-left: 224px; padding-top: 56px; min-height: 100vh; }
        .page { padding: 28px; max-width: 1000px; }
        .page-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 24px; }
        .page-title { font-size: 20px; font-weight: 600; }
        .page-sub { font-size: 13px; color: #94a3b8; margin-top: 3px; }

        .alert { padding: 12px 16px; border-radius: 10px; margin-bottom: 16px; font-size: 13px; display: flex; align-items: center; gap: 8px; }
        .alert-ok  { background: #d1fae5; color: #065f46; border: 1px solid #6ee7b7; }
        .alert-err { background: #fee2e2; color: #991b1b; border: 1px solid #fca5a5; }

        /* Formulario nueva convocatoria */
        .form-card { background: #fff; border-radius: 12px; border: 1px solid #e2e8f0; padding: 24px; margin-bottom: 24px; }
        .form-card h3 { font-size: 15px; font-weight: 600; margin-bottom: 18px; padding-bottom: 12px; border-bottom: 1px solid #f1f5f9; display: flex; align-items: center; gap: 8px; }
        .grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
        .grid-3 { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 16px; }
        .field { display: flex; flex-direction: column; gap: 5px; }
        .field label { font-size: 12px; font-weight: 600; color: #475569; text-transform: uppercase; letter-spacing: .04em; }
        .field input, .field select { padding: 9px 12px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 13px; color: #1e293b; font-family: 'Figtree', sans-serif; background: #f8fafc; width: 100%; }
        .field input:focus, .field select:focus { outline: none; border-color: #1e3a6e; background: #fff; }
        .field-error { font-size: 11px; color: #ef4444; }
        .btn-primary { padding: 10px 24px; background: #1e3a6e; color: #fff; border: none; border-radius: 8px; font-size: 13px; font-weight: 500; cursor: pointer; font-family: 'Figtree', sans-serif; display: inline-flex; align-items: center; gap: 8px; }
        .btn-primary:hover { background: #0f2147; }

        /* Tabla convocatorias */
        .card { background: #fff; border-radius: 12px; border: 1px solid #e2e8f0; overflow: hidden; }
        table { width: 100%; border-collapse: collapse; font-size: 13px; }
        th { text-align: left; padding: 12px 16px; font-size: 11px; font-weight: 600; color: #94a3b8; background: #f8fafc; border-bottom: 1px solid #f1f5f9; text-transform: uppercase; letter-spacing: .04em; }
        td { padding: 14px 16px; border-bottom: 1px solid #f8fafc; color: #374151; vertical-align: middle; }
        tr:last-child td { border-bottom: none; }
        .badge { display: inline-flex; align-items: center; gap: 4px; padding: 3px 10px; border-radius: 99px; font-size: 11px; font-weight: 500; }
        .badge-activa    { background: #d1fae5; color: #065f46; }
        .badge-planif    { background: #dbeafe; color: #1e40af; }
        .badge-concluida { background: #f1f5f9; color: #64748b; }
        .btn-sm { padding: 5px 12px; border-radius: 7px; font-size: 12px; cursor: pointer; border: none; font-family: 'Figtree', sans-serif; text-decoration: none; display: inline-flex; align-items: center; gap: 4px; font-weight: 500; }
        .btn-activar { background: #d1fae5; color: #065f46; border: 1px solid #6ee7b7; }
        .btn-activar:hover { background: #a7f3d0; }
        .btn-concluir { background: #fef3c7; color: #92400e; border: 1px solid #fde68a; }
        .btn-concluir:hover { background: #fde68a; }
        .btn-editar-planif { background: #e0e7ff; color: #3730a3; border: 1px solid #c7d2fe; }
        .btn-editar-planif:hover { background: #c7d2fe; }
        .btn-editar-activa { background: #fff7ed; color: #92400e; border: 1px solid #fed7aa; }
        .btn-editar-activa:hover { background: #fed7aa; }
        .empty { text-align: center; padding: 40px; color: #94a3b8; }
    </style>
</head>
<body>

<div class="topbar">
    <a href="{{ route('admin.dashboard') }}" class="topbar-brand">
        <i class="ti ti-school" style="font-size:20px"></i> CUP — FICCT
    </a>
    <div class="topbar-right">
        <span class="topbar-user">
            <i class="ti ti-user-circle" style="font-size:16px"></i>
            {{ Auth::user()->nombre }} {{ Auth::user()->apellido }}
        </span>
        <form method="POST" action="{{ route('logout') }}" style="display:inline">
            @csrf
            <button type="submit" class="btn-logout"><i class="ti ti-logout"></i> Salir</button>
        </form>
    </div>
</div>

<aside class="sidebar">
    <div class="nav-label">Menú</div>
    <a href="{{ route('admin.dashboard') }}" class="nav-item c-sky"><i class="ti ti-home"></i> Inicio</a>
    <a href="{{ route('admin.pre-registros.index') }}" class="nav-item c-amber"><i class="ti ti-clock"></i> Pre-registros</a>
    <a href="{{ route('admin.postulantes.index') }}" class="nav-item c-blue"><i class="ti ti-id-badge"></i> Postulantes</a>
    <a href="{{ route('admin.docentes.index') }}" class="nav-item c-purple"><i class="ti ti-chalkboard"></i> Docentes</a>
    <div class="nav-label">Académico</div>
    <a href="{{ route('admin.grupos.index') }}" class="nav-item c-teal"><i class="ti ti-layout-grid"></i> Grupos</a>
    <a href="{{ route('admin.examenes.index') }}" class="nav-item c-rose"><i class="ti ti-file-text"></i> Exámenes</a>
    <a href="{{ route('admin.convocatorias.index') }}" class="nav-item active"><i class="ti ti-building"></i> Convocatoria</a>
    <div class="nav-label">Sistema</div>
    <a href="{{ route('admin.reportes.index') }}" class="nav-item c-purple"><i class="ti ti-chart-bar"></i> Reportes</a>
    <div class="sidebar-footer">UAGRM &copy; {{ date('Y') }}</div>
</aside>

<main class="main">
<div class="page">

    <div class="page-header">
        <div>
            <div class="page-title">Convocatorias</div>
            <div class="page-sub">Crea y gestiona las convocatorias del curso preuniversitario</div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-ok"><i class="ti ti-circle-check"></i> {{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-err"><i class="ti ti-alert-circle"></i> {{ session('error') }}</div>
    @endif

    {{-- FORMULARIO NUEVA CONVOCATORIA --}}
    <div class="form-card">
        <h3><i class="ti ti-plus" style="color:#1e3a6e"></i> Nueva convocatoria</h3>
        <form method="POST" action="{{ route('admin.convocatorias.store') }}">
            @csrf
            <div class="grid-2" style="margin-bottom:16px">
                <div class="field" style="grid-column: span 2">
                    <label>Nombre de la convocatoria *</label>
                    <input type="text" name="nombre" value="{{ old('nombre', 'Gestión ' . date('Y')) }}" required placeholder="Ej: Gestión 2025 — Primer Semestre">
                    @error('nombre')<div class="field-error">{{ $message }}</div>@enderror
                </div>
            </div>
            <div class="grid-3" style="margin-bottom:16px">
                <div class="field">
                    <label>Fecha inicio *</label>
                    <input type="date" name="fecha_inicio" value="{{ old('fecha_inicio') }}" required>
                    @error('fecha_inicio')<div class="field-error">{{ $message }}</div>@enderror
                </div>
                <div class="field">
                    <label>Fecha fin *</label>
                    <input type="date" name="fecha_fin" value="{{ old('fecha_fin') }}" required>
                    @error('fecha_fin')<div class="field-error">{{ $message }}</div>@enderror
                </div>
                <div class="field">
                    <label>Monto de pago (Bs.) *</label>
                    <input type="number" name="monto_pago" value="{{ old('monto_pago', '700.00') }}" step="0.01" min="0" required>
                    @error('monto_pago')<div class="field-error">{{ $message }}</div>@enderror
                </div>
            </div>
            <div class="grid-2" style="margin-bottom:20px">
                <div class="field">
                    <label>Cupo total</label>
                    <input type="number" name="cupo_total" value="{{ old('cupo_total', '300') }}" min="1">
                </div>
                <div class="field">
                    <label>Estado inicial</label>
                    <select name="estado">
                        <option value="ACTIVA">ACTIVA — habilitada para registro</option>
                        <option value="PLANIFICADA">PLANIFICADA — no visible aún</option>
                    </select>
                </div>
            </div>
            <button type="submit" class="btn-primary">
                <i class="ti ti-device-floppy"></i> Crear convocatoria
            </button>
        </form>
    </div>

    {{-- LISTA DE CONVOCATORIAS --}}
    <div class="card">
        @if($convocatorias->isEmpty())
            <div class="empty">
                <i class="ti ti-building" style="font-size:36px;display:block;margin-bottom:10px"></i>
                No hay convocatorias creadas aún
            </div>
        @else
        <table>
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Fecha inicio</th>
                    <th>Fecha fin</th>
                    <th>Monto</th>
                    <th>Cupo</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($convocatorias as $conv)
                <tr>
                    <td style="font-weight:500">{{ $conv->nombre }}</td>
                    <td>{{ \Carbon\Carbon::parse($conv->fecha_inicio)->format('d/m/Y') }}</td>
                    <td>{{ \Carbon\Carbon::parse($conv->fecha_fin)->format('d/m/Y') }}</td>
                    <td>Bs. {{ number_format($conv->monto_pago, 2) }}</td>
                    <td>{{ $conv->cupo_total }}</td>
                    <td>
                        <span class="badge {{ $conv->estado === 'ACTIVA' ? 'badge-activa' : ($conv->estado === 'PLANIFICADA' ? 'badge-planif' : 'badge-concluida') }}">
                            <i class="ti {{ $conv->estado === 'ACTIVA' ? 'ti-circle-check' : ($conv->estado === 'PLANIFICADA' ? 'ti-clock' : 'ti-circle-x') }}"></i>
                            {{ $conv->estado }}
                        </span>
                    </td>
                    <td>
                        <div style="display:flex;gap:6px;flex-wrap:wrap">
                            @if($conv->estado === 'PLANIFICADA')
                            <a href="{{ route('admin.convocatorias.edit', $conv->id) }}" class="btn-sm btn-editar-planif">
                                <i class="ti ti-pencil"></i> Editar
                            </a>
                            <form method="POST" action="{{ route('admin.convocatorias.activar', $conv->id) }}"
                                  onsubmit="return confirm('¿Activar esta convocatoria? Solo puede haber una activa a la vez.')">
                                @csrf
                                <button class="btn-sm btn-activar"><i class="ti ti-player-play"></i> Activar</button>
                            </form>
                            @endif
                            @if($conv->estado === 'ACTIVA')
                            <a href="{{ route('admin.convocatorias.edit', $conv->id) }}" class="btn-sm btn-editar-activa">
                                <i class="ti ti-pencil"></i> Editar
                            </a>
                            <form method="POST" action="{{ route('admin.convocatorias.concluir', $conv->id) }}"
                                  onsubmit="return confirm('¿Concluir esta convocatoria? Ya no se podrán registrar postulantes.')">
                                @csrf
                                <button class="btn-sm btn-concluir"><i class="ti ti-flag"></i> Concluir</button>
                            </form>
                            @endif
                            @if($conv->estado === 'CONCLUIDA')
                            <span style="font-size:12px;color:#94a3b8">— sin acciones —</span>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif
    </div>

</div>
</main>
</body>
</html>