<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Postulantes — CUP FICCT</title>
    <link href="https://fonts.googleapis.com/css2?family=Figtree:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Figtree', sans-serif; background: #f1f5f9; color: #1e293b; }

        .topbar { background: #1e3a6e; padding: 0 24px; height: 56px; display: flex; align-items: center; justify-content: space-between; position: fixed; top: 0; left: 0; right: 0; z-index: 200; }
        .topbar-brand { color: #fff; font-size: 16px; font-weight: 600; display: flex; align-items: center; gap: 10px; text-decoration: none; }
        .topbar-right { display: flex; align-items: center; gap: 16px; }
        .topbar-user { color: #a8c8f0; font-size: 13px; display: flex; align-items: center; gap: 6px; }
        .btn-logout { background: rgba(255,255,255,0.12); border: 1px solid rgba(255,255,255,0.22); color: #fff; padding: 6px 14px; border-radius: 8px; font-size: 12px; cursor: pointer; text-decoration: none; display: flex; align-items: center; gap: 6px; transition: background .2s; }
        .btn-logout:hover { background: rgba(255,255,255,0.22); }

        .sidebar { width: 224px; height: calc(100vh - 56px); background: #1e3a6e; position: fixed; top: 56px; left: 0; overflow-y: auto; padding: 20px 12px 24px; display: flex; flex-direction: column; gap: 2px; }
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

        .main { margin-left: 224px; padding-top: 56px; min-height: 100vh; }
        .page { padding: 28px; max-width: 1200px; }

        .page-header { margin-bottom: 22px; display: flex; align-items: flex-start; justify-content: space-between; }
        .page-title { font-size: 20px; font-weight: 600; color: #1e293b; }
        .page-sub { font-size: 13px; color: #94a3b8; margin-top: 3px; }

        /* Conteos */
        .conteos { display: grid; grid-template-columns: repeat(4,1fr); gap: 12px; margin-bottom: 20px; }
        .conteo { background: #fff; border-radius: 10px; padding: 16px 20px; border: 1px solid #e2e8f0; }
        .conteo-label { font-size: 12px; color: #64748b; margin-bottom: 6px; display: flex; align-items: center; gap: 5px; }
        .conteo-valor { font-size: 24px; font-weight: 600; color: #1e293b; }
        .conteo.total  .conteo-valor { color: #1e3a6e; }
        .conteo.reg    .conteo-valor { color: #92400e; }
        .conteo.pago   .conteo-valor { color: #065f46; }
        .conteo.ret    .conteo-valor { color: #991b1b; }

        /* Filtros */
        .filtros { background: #fff; border-radius: 10px; padding: 16px 20px; border: 1px solid #e2e8f0; margin-bottom: 16px; display: flex; gap: 16px; align-items: flex-end; flex-wrap: wrap; }
        .filtros label { font-size: 11px; font-weight: 600; color: #64748b; text-transform: uppercase; letter-spacing: .04em; display: block; margin-bottom: 5px; }
        .filtros select, .filtros input { padding: 7px 12px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 13px; color: #374151; background: #f8fafc; font-family: 'Figtree', sans-serif; }
        .filtros input { min-width: 220px; }
        .filtros select { min-width: 140px; }
        .btn-filtrar { padding: 8px 18px; background: #1e3a6e; color: #fff; border: none; border-radius: 8px; font-size: 13px; cursor: pointer; font-family: 'Figtree', sans-serif; display: flex; align-items: center; gap: 6px; }
        .btn-filtrar:hover { background: #0f2147; }
        .btn-limpiar { padding: 8px 14px; background: #f1f5f9; border: 1px solid #e2e8f0; color: #64748b; border-radius: 8px; font-size: 13px; cursor: pointer; font-family: 'Figtree', sans-serif; text-decoration: none; display: flex; align-items: center; gap: 6px; }
        .btn-limpiar:hover { background: #e2e8f0; }

        /* Tabla */
        .card { background: #fff; border-radius: 12px; border: 1px solid #e2e8f0; overflow: hidden; }
        .card-header { padding: 12px 16px; border-bottom: 1px solid #f1f5f9; display: flex; align-items: center; justify-content: space-between; }
        .card-info { font-size: 13px; color: #64748b; }
        .btn-export { font-size: 12px; color: #64748b; text-decoration: none; display: flex; align-items: center; gap: 5px; }
        .btn-export:hover { color: #1e3a6e; }
        table { width: 100%; border-collapse: collapse; font-size: 13px; }
        th { text-align: left; padding: 12px 16px; font-size: 11px; font-weight: 600; color: #94a3b8; background: #f8fafc; border-bottom: 1px solid #f1f5f9; text-transform: uppercase; letter-spacing: .04em; }
        td { padding: 13px 16px; border-bottom: 1px solid #f8fafc; color: #374151; vertical-align: middle; }
        tr:last-child td { border-bottom: none; }
        tr:hover td { background: #fafafa; }

        /* Badges */
        .badge { display: inline-flex; align-items: center; padding: 3px 10px; border-radius: 99px; font-size: 11px; font-weight: 500; }
        .badge-reg  { background: #fef3c7; color: #92400e; border: 1px solid #fde68a; }
        .badge-pago { background: #d1fae5; color: #065f46; border: 1px solid #6ee7b7; }
        .badge-ret  { background: #fee2e2; color: #991b1b; border: 1px solid #fca5a5; }
        .badge-man  { background: #fef9c3; color: #854d0e; }
        .badge-tar  { background: #dbeafe; color: #1e40af; }
        .badge-noch { background: #ede9fe; color: #5b21b6; }

        /* Avatar */
        .avatar { width: 32px; height: 32px; border-radius: 50%; background: #1e3a6e; color: #fff; display: flex; align-items: center; justify-content: center; font-size: 12px; font-weight: 600; flex-shrink: 0; }

        /* Código mono */
        .codigo { font-family: monospace; font-size: 12px; background: #f1f5f9; color: #475569; padding: 2px 8px; border-radius: 6px; }

        /* Botones acción */
        .btn { padding: 5px 10px; border-radius: 7px; font-size: 12px; cursor: pointer; border: none; font-family: 'Figtree', sans-serif; text-decoration: none; display: inline-flex; align-items: center; gap: 4px; font-weight: 500; }
        .btn-info { background: #dbeafe; color: #1e40af; border: 1px solid #93c5fd; }
        .btn-info:hover { background: #bfdbfe; }
        .btn-warn { background: #fef3c7; color: #92400e; border: 1px solid #fde68a; }
        .btn-warn:hover { background: #fde68a; }
        .btn-err  { background: #fee2e2; color: #991b1b; border: 1px solid #fca5a5; }
        .btn-err:hover { background: #fecaca; }

        /* Paginación */
        .pagination { padding: 12px 16px; border-top: 1px solid #f1f5f9; display: flex; align-items: center; justify-content: space-between; }
        .pag-info { font-size: 12px; color: #94a3b8; }
        .pag-links { display: flex; gap: 4px; }
        .pag-btn { padding: 5px 10px; border-radius: 7px; font-size: 12px; text-decoration: none; border: 1px solid #e2e8f0; color: #475569; background: #fff; }
        .pag-btn:hover { background: #1e3a6e; color: #fff; border-color: #1e3a6e; }
        .pag-btn.active { background: #1e3a6e; color: #fff; border-color: #1e3a6e; font-weight: 600; }
        .pag-btn.disabled { color: #cbd5e1; cursor: not-allowed; }

        /* Empty */
        .empty { text-align: center; padding: 56px; color: #94a3b8; }
        .empty i { font-size: 40px; display: block; margin-bottom: 10px; }

        /* Alertas */
        .alert { padding: 12px 16px; border-radius: 10px; margin-bottom: 16px; font-size: 13px; display: flex; align-items: center; gap: 8px; }
        .alert-ok  { background: #d1fae5; color: #065f46; border: 1px solid #6ee7b7; }
        .alert-err { background: #fee2e2; color: #991b1b; border: 1px solid #fca5a5; }
    </style>
</head>
<body>

{{-- TOPBAR --}}
<div class="topbar">
    <a href="{{ route('admin.dashboard') }}" class="topbar-brand">
        <i class="ti ti-school" style="font-size:20px"></i> CUP — FICCT
    </a>
    <div class="topbar-right">
        <span class="topbar-user">
            <i class="ti ti-user-circle" style="font-size:16px"></i>
            {{ Auth::user()->nombre ?? Auth::user()->name }} {{ Auth::user()->apellido ?? '' }}
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
    <a href="{{ route('admin.pre-registros.index') }}" class="nav-item c-blue">
        <i class="ti ti-clock"></i> Pre-registros
    </a>
    <a href="{{ route('admin.postulantes.index') }}" class="nav-item active">
        <i class="ti ti-id-badge"></i> Postulantes
    </a>
    <a href="{{ route('admin.docentes.index') }}" class="nav-item c-purple">
        <i class="ti ti-chalkboard"></i> Docentes
    </a>

    <div class="nav-label">Académico</div>
    <a href="#" class="nav-item c-teal">
        <i class="ti ti-layout-grid"></i> Grupos
    </a>
    <a href="#" class="nav-item c-rose">
        <i class="ti ti-file-text"></i> Exámenes
    </a>
    <a href="#" class="nav-item c-blue">
        <i class="ti ti-building"></i> Convocatoria
    </a>

    <div class="nav-label">Sistema</div>
    <a href="#" class="nav-item c-purple">
        <i class="ti ti-chart-bar"></i> Reportes
    </a>

    <div class="sidebar-footer">UAGRM &copy; {{ date('Y') }}</div>
</aside>

{{-- CONTENIDO --}}
<main class="main">
<div class="page">

    <div class="page-header">
        <div>
            <div class="page-title">Postulantes</div>
            <div class="page-sub">Gestiona los postulantes inscritos en la convocatoria activa</div>
        </div>
    </div>

    {{-- ALERTAS --}}
    @if(session('success'))
        <div class="alert alert-ok"><i class="ti ti-circle-check"></i> {{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-err"><i class="ti ti-alert-circle"></i> {{ session('error') }}</div>
    @endif

    {{-- CONTEOS --}}
    <div class="conteos">
        <div class="conteo total">
            <div class="conteo-label"><i class="ti ti-users"></i> Total</div>
            <div class="conteo-valor">{{ $stats['total'] }}</div>
        </div>
        <div class="conteo reg">
            <div class="conteo-label"><i class="ti ti-clock"></i> Registrados</div>
            <div class="conteo-valor">{{ $stats['registrados'] }}</div>
        </div>
        <div class="conteo pago">
            <div class="conteo-label"><i class="ti ti-circle-check"></i> Con Pago</div>
            <div class="conteo-valor">{{ $stats['con_pago'] }}</div>
        </div>
        <div class="conteo ret">
            <div class="conteo-label"><i class="ti ti-x"></i> Retirados</div>
            <div class="conteo-valor">{{ $stats['retirados'] }}</div>
        </div>
    </div>

    {{-- FILTROS --}}
    <form method="GET" action="{{ route('admin.postulantes.index') }}" class="filtros">
        <div>
            <label>Buscar</label>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Nombre, CI o código...">
        </div>
        <div>
            <label>Estado</label>
            <select name="estado">
                <option value="">Todos</option>
                <option value="REGISTRADO" {{ request('estado')=='REGISTRADO'?'selected':'' }}>Registrado</option>
                <option value="CON_PAGO"   {{ request('estado')=='CON_PAGO'?'selected':'' }}>Con Pago</option>
                <option value="RETIRADO"   {{ request('estado')=='RETIRADO'?'selected':'' }}>Retirado</option>
            </select>
        </div>
        <div>
            <label>Turno</label>
            <select name="turno">
                <option value="">Todos</option>
                <option value="MAÑANA" {{ request('turno')=='MAÑANA'?'selected':'' }}>Mañana</option>
                <option value="TARDE"  {{ request('turno')=='TARDE'?'selected':'' }}>Tarde</option>
                <option value="NOCHE"  {{ request('turno')=='NOCHE'?'selected':'' }}>Noche</option>
            </select>
        </div>
        <div>
            <label>Carrera</label>
            <select name="carrera_id">
                <option value="">Todas</option>
                @foreach($carreras as $carrera)
                    <option value="{{ $carrera->id }}" {{ request('carrera_id')==$carrera->id?'selected':'' }}>
                        {{ $carrera->nombre }}
                    </option>
                @endforeach
            </select>
        </div>
        <div style="display:flex;gap:8px">
            <button type="submit" class="btn-filtrar">
                <i class="ti ti-filter"></i> Filtrar
            </button>
            @if(request()->hasAny(['search','estado','turno','carrera_id']))
                <a href="{{ route('admin.postulantes.index') }}" class="btn-limpiar">
                    <i class="ti ti-x"></i> Limpiar
                </a>
            @endif
        </div>
    </form>

    {{-- TABLA --}}
    <div class="card">
        <div class="card-header">
            <span class="card-info">
                Mostrando {{ $postulantes->firstItem() ?? 0 }}–{{ $postulantes->lastItem() ?? 0 }}
                de {{ $postulantes->total() }} postulantes
            </span>
            <a href="{{ route('admin.postulantes.export') }}" class="btn-export">
                <i class="ti ti-download"></i> Exportar
            </a>
        </div>

        @if($postulantes->isEmpty())
            <div class="empty">
                <i class="ti ti-users"></i>
                No se encontraron postulantes
            </div>
        @else
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Postulante</th>
                    <th>Código</th>
                    <th>CI</th>
                    <th>Carrera preferida</th>
                    <th>Turno</th>
                    <th>Grupo</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($postulantes as $i => $p)
                <tr>
                    <td style="color:#94a3b8">{{ $postulantes->firstItem() + $i }}</td>
                    <td>
                        <div style="display:flex;align-items:center;gap:10px">
                            <div class="avatar">
                                {{ strtoupper(substr($p->nombre,0,1).substr($p->apellido,0,1)) }}
                            </div>
                            <div>
                                <div style="font-weight:500">{{ $p->nombre }} {{ $p->apellido }}</div>
                                <div style="color:#94a3b8;font-size:11px">{{ $p->email }}</div>
                            </div>
                        </div>
                    </td>
                    <td><span class="codigo">{{ $p->codigo_estudiante }}</span></td>
                    <td style="font-size:12px;color:#64748b">{{ $p->ci }}</td>
                    <td>
                        <div style="font-size:13px">{{ $p->carreraPref1->nombre ?? '—' }}</div>
                        @if($p->carreraPref2)
                            <div style="font-size:11px;color:#94a3b8">2ª: {{ $p->carreraPref2->nombre }}</div>
                        @endif
                    </td>
                    <td>
                        @php
                            $turnoClass = ['MAÑANA'=>'badge-man','TARDE'=>'badge-tar','NOCHE'=>'badge-noch'];
                        @endphp
                        <span class="badge {{ $turnoClass[$p->turno_asignado] ?? '' }}">
                            {{ ucfirst(strtolower($p->turno_asignado)) }}
                        </span>
                    </td>
                    <td style="font-size:12px;color:#64748b">
                        {{ $p->grupoPostulante?->grupo?->codigo_grupo ?? '—' }}
                    </td>
                    <td>
                        @php
                            $estadoClass = ['REGISTRADO'=>'badge-reg','CON_PAGO'=>'badge-pago','RETIRADO'=>'badge-ret'];
                        @endphp
                        <span class="badge {{ $estadoClass[$p->estado] ?? '' }}">
                            {{ ucfirst(strtolower(str_replace('_',' ',$p->estado))) }}
                        </span>
                    </td>
                    <td>
                        <div style="display:flex;gap:6px">
                            <a href="{{ route('admin.postulantes.show', $p->id) }}" class="btn btn-info">
                                <i class="ti ti-eye"></i> Ver
                            </a>
                            <a href="{{ route('admin.postulantes.edit', $p->id) }}" class="btn btn-warn">
                                <i class="ti ti-pencil"></i> Editar
                            </a>
                            <form method="POST" action="{{ route('admin.postulantes.destroy', $p->id) }}"
                                  onsubmit="return confirm('¿Eliminar a {{ $p->nombre }} {{ $p->apellido }}?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-err">
                                    <i class="ti ti-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        {{-- PAGINACIÓN --}}
        @if($postulantes->hasPages())
        <div class="pagination">
            <span class="pag-info">Página {{ $postulantes->currentPage() }} de {{ $postulantes->lastPage() }}</span>
            <div class="pag-links">
                @if($postulantes->onFirstPage())
                    <span class="pag-btn disabled">← Anterior</span>
                @else
                    <a href="{{ $postulantes->previousPageUrl() }}" class="pag-btn">← Anterior</a>
                @endif

                @foreach($postulantes->getUrlRange(max(1,$postulantes->currentPage()-2), min($postulantes->lastPage(),$postulantes->currentPage()+2)) as $page => $url)
                    <a href="{{ $url }}" class="pag-btn {{ $page == $postulantes->currentPage() ? 'active' : '' }}">{{ $page }}</a>
                @endforeach

                @if($postulantes->hasMorePages())
                    <a href="{{ $postulantes->nextPageUrl() }}" class="pag-btn">Siguiente →</a>
                @else
                    <span class="pag-btn disabled">Siguiente →</span>
                @endif
            </div>
        </div>
        @endif
        @endif
    </div>

</div>
</main>

</body>
</html>