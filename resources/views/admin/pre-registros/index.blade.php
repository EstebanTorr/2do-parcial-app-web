<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pre-registros — CUP FICCT</title>
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
        .page { padding: 28px; max-width: 1100px; }

        /* ── PAGE HEADER ── */
        .page-header { margin-bottom: 22px; }
        .page-title { font-size: 20px; font-weight: 600; color: #1e293b; }
        .page-sub { font-size: 13px; color: #94a3b8; margin-top: 3px; }

        /* ── CONTEOS ── */
        .conteos { display: grid; grid-template-columns: repeat(3,1fr); gap: 12px; margin-bottom: 20px; }
        .conteo { background: #fff; border-radius: 10px; padding: 16px 20px; border: 1px solid #e2e8f0; }
        .conteo-label { font-size: 12px; color: #64748b; margin-bottom: 6px; display: flex; align-items: center; gap: 5px; }
        .conteo-valor { font-size: 24px; font-weight: 600; }
        .conteo.pend .conteo-valor { color: #92400e; }
        .conteo.aprov .conteo-valor { color: #065f46; }
        .conteo.rech .conteo-valor { color: #991b1b; }

        /* ── FILTROS ── */
        .filtros { background: #fff; border-radius: 10px; padding: 16px 20px; border: 1px solid #e2e8f0; margin-bottom: 16px; display: flex; gap: 16px; align-items: flex-end; flex-wrap: wrap; }
        .filtros label { font-size: 11px; font-weight: 600; color: #64748b; text-transform: uppercase; letter-spacing: .04em; display: block; margin-bottom: 5px; }
        .filtros select { padding: 7px 12px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 13px; color: #374151; background: #f8fafc; font-family: 'Figtree', sans-serif; min-width: 160px; }
        .btn-filtrar { padding: 8px 18px; background: #1e3a6e; color: #fff; border: none; border-radius: 8px; font-size: 13px; cursor: pointer; font-family: 'Figtree', sans-serif; display: flex; align-items: center; gap: 6px; }
        .btn-filtrar:hover { background: #0f2147; }

        /* ── TABLA ── */
        .card { background: #fff; border-radius: 12px; border: 1px solid #e2e8f0; overflow: hidden; }
        table { width: 100%; border-collapse: collapse; font-size: 13px; }
        th { text-align: left; padding: 12px 16px; font-size: 11px; font-weight: 600; color: #94a3b8; background: #f8fafc; border-bottom: 1px solid #f1f5f9; text-transform: uppercase; letter-spacing: .04em; }
        td { padding: 13px 16px; border-bottom: 1px solid #f8fafc; color: #374151; vertical-align: middle; }
        tr:last-child td { border-bottom: none; }
        tr:hover td { background: #fafafa; }

        /* ── BADGES ── */
        .badge { display: inline-flex; align-items: center; padding: 3px 10px; border-radius: 99px; font-size: 11px; font-weight: 500; }
        .badge-pend  { background: #fef3c7; color: #92400e; }
        .badge-aprov { background: #d1fae5; color: #065f46; }
        .badge-rech  { background: #fee2e2; color: #991b1b; }
        .badge-est   { background: #dbeafe; color: #1e40af; }
        .badge-doc   { background: #ede9fe; color: #5b21b6; }

        /* ── AVATAR ── */
        .avatar { width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 12px; font-weight: 600; flex-shrink: 0; }
        .av-blue   { background: #dbeafe; color: #1e40af; }
        .av-purple { background: #ede9fe; color: #5b21b6; }

        /* ── BOTONES ACCIÓN ── */
        .btn { padding: 6px 12px; border-radius: 8px; font-size: 12px; cursor: pointer; border: none; font-family: 'Figtree', sans-serif; text-decoration: none; display: inline-flex; align-items: center; gap: 4px; font-weight: 500; }
        .btn-ok   { background: #d1fae5; color: #065f46; border: 1px solid #6ee7b7; }
        .btn-ok:hover { background: #a7f3d0; }
        .btn-err  { background: #fee2e2; color: #991b1b; border: 1px solid #fca5a5; }
        .btn-err:hover { background: #fecaca; }
        .btn-info { background: #dbeafe; color: #1e40af; border: 1px solid #93c5fd; }
        .btn-info:hover { background: #bfdbfe; }

        /* ── DOCS BAR ── */
        .docs-bar { display: flex; align-items: center; gap: 6px; font-size: 12px; }
        .docs-fill { height: 6px; border-radius: 99px; background: #e2e8f0; width: 56px; overflow: hidden; }
        .docs-fill-inner { height: 100%; border-radius: 99px; }

        /* ── EMPTY ── */
        .empty { text-align: center; padding: 56px; color: #94a3b8; }
        .empty i { font-size: 40px; display: block; margin-bottom: 10px; }

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
    <a href="{{ route('admin.pre-registros.index') }}" class="nav-item active">
        <i class="ti ti-clock"></i> Pre-registros
    </a>
    <a href="{{ route('admin.postulantes.index') }}" class="nav-item c-blue">
        <i class="ti ti-id-badge"></i> Postulantes
    </a>
    <a href="{{ route('admin.docentes.index') }}" class="nav-item c-purple">
        <i class="ti ti-chalkboard"></i> Docentes
    </a>

    <div class="nav-label">Académico</div>
    <a href="{{ route('admin.grupos.index') }}" class="nav-item c-teal">
        <i class="ti ti-layout-grid"></i> Grupos
    </a>
    <a href="{{ route('admin.examenes.index') }}" class="nav-item c-rose">
        <i class="ti ti-file-text"></i> Exámenes
    </a>
    <a href="{{ route('admin.convocatorias.index') }}" class="nav-item c-blue">
        <i class="ti ti-building"></i> Convocatoria
    </a>

    <div class="nav-label">Sistema</div>
    <a href="{{ route('admin.reportes.index') }}" class="nav-item c-purple">
        <i class="ti ti-chart-bar"></i> Reportes
    </a>

    <div class="sidebar-footer">UAGRM &copy; {{ date('Y') }}</div>
</aside>

{{-- CONTENIDO --}}
<main class="main">
<div class="page">

    <div class="page-header">
        <div class="page-title">Pre-registros</div>
        <div class="page-sub">Revisa, aprueba o rechaza solicitudes de estudiantes y docentes</div>
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
        <div class="conteo pend">
            <div class="conteo-label"><i class="ti ti-clock"></i> Pendientes</div>
            <div class="conteo-valor">{{ $conteos['pendientes'] }}</div>
        </div>
        <div class="conteo aprov">
            <div class="conteo-label"><i class="ti ti-circle-check"></i> Aprobados</div>
            <div class="conteo-valor">{{ $conteos['aprobados'] }}</div>
        </div>
        <div class="conteo rech">
            <div class="conteo-label"><i class="ti ti-x"></i> Rechazados</div>
            <div class="conteo-valor">{{ $conteos['rechazados'] }}</div>
        </div>
    </div>

    {{-- FILTROS --}}
    <form method="GET" action="{{ route('admin.pre-registros.index') }}" class="filtros">
        <div>
            <label>Estado</label>
            <select name="estado">
                <option value="PENDIENTE" {{ $estado=='PENDIENTE'?'selected':'' }}>Pendientes</option>
                <option value="APROBADO"  {{ $estado=='APROBADO'?'selected':'' }}>Aprobados</option>
                <option value="RECHAZADO" {{ $estado=='RECHAZADO'?'selected':'' }}>Rechazados</option>
                <option value="todos"     {{ $estado=='todos'?'selected':'' }}>Todos</option>
            </select>
        </div>
        <div>
            <label>Tipo</label>
            <select name="tipo">
                <option value="todos"      {{ $tipo=='todos'?'selected':'' }}>Estudiantes y Docentes</option>
                <option value="estudiante" {{ $tipo=='estudiante'?'selected':'' }}>Solo Estudiantes</option>
                <option value="docente"    {{ $tipo=='docente'?'selected':'' }}>Solo Docentes</option>
            </select>
        </div>
        <div>
            <button type="submit" class="btn-filtrar">
                <i class="ti ti-filter"></i> Filtrar
            </button>
        </div>
    </form>

    {{-- TABLA --}}
    <div class="card">
        @if($registros->isEmpty())
            <div class="empty">
                <i class="ti ti-inbox"></i>
                No hay registros con ese filtro
            </div>
        @else
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nombre</th>
                    <th>Tipo</th>
                    <th>CI</th>
                    <th>Email</th>
                    <th>Turno</th>
                    <th>Docs</th>
                    <th>Estado</th>
                    <th>Fecha</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($registros as $reg)
                <tr>
                    <td style="color:#94a3b8">{{ $reg->id }}</td>
                    <td>
                        <div style="display:flex;align-items:center;gap:10px">
                            <div class="avatar {{ $reg->tipo=='ESTUDIANTE'?'av-blue':'av-purple' }}">
                                {{ strtoupper(substr($reg->nombre,0,1)) }}{{ strtoupper(substr($reg->apellido,0,1)) }}
                            </div>
                            <div>
                                <div style="font-weight:500">{{ $reg->nombre }} {{ $reg->apellido }}</div>
                                <div style="color:#94a3b8;font-size:11px">{{ $reg->telefono }}</div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <span class="badge {{ $reg->tipo=='ESTUDIANTE'?'badge-est':'badge-doc' }}">
                            {{ $reg->tipo }}
                        </span>
                    </td>
                    <td>{{ $reg->ci }}-{{ $reg->ci_extension }}</td>
                    <td style="font-size:12px;color:#64748b">{{ $reg->email }}</td>
                    <td>
                        @if(isset($reg->turno_preferido))
                            <span class="badge" style="background:#f1f5f9;color:#475569">{{ $reg->turno_preferido }}</span>
                        @else
                            <span style="color:#94a3b8;font-size:12px">—</span>
                        @endif
                    </td>
                    <td>
                        <div class="docs-bar">
                            <div class="docs-fill">
                                <div class="docs-fill-inner" style="width:{{ $reg->docs_req > 0 ? ($reg->docs/$reg->docs_req)*100 : 0 }}%;background:{{ $reg->docs==$reg->docs_req?'#10b981':'#f59e0b' }}"></div>
                            </div>
                            <span style="font-size:11px;color:#64748b">{{ $reg->docs }}/{{ $reg->docs_req }}</span>
                        </div>
                    </td>
                    <td>
                        <span class="badge {{ $reg->estado=='PENDIENTE'?'badge-pend':($reg->estado=='APROBADO'?'badge-aprov':'badge-rech') }}">
                            {{ $reg->estado }}
                        </span>
                    </td>
                    <td style="font-size:12px;color:#64748b">
                        {{ $reg->created_at ? \Carbon\Carbon::parse($reg->created_at)->format('d/m/Y') : '—' }}
                    </td>
                    <td>
                        <div style="display:flex;gap:6px;flex-wrap:wrap">
                            {{-- Ver detalle siempre --}}
                            @if($reg->tipo === 'ESTUDIANTE')
                            <a href="{{ route('admin.pre-registros.estudiante.show', $reg->id) }}" class="btn btn-info">
                                <i class="ti ti-eye"></i> Ver
                            </a>
                            @else
                            <a href="{{ route('admin.pre-registros.docente.show', $reg->id) }}" class="btn btn-info">
                                <i class="ti ti-eye"></i> Ver
                            </a>
                            @endif

                            @if($reg->estado === 'PENDIENTE')
                                {{-- Aprobar solo estudiantes (docentes tienen su propio flujo) --}}
                                @if($reg->tipo === 'ESTUDIANTE')
                                <form method="POST" action="{{ route('admin.pre-registros.estudiante.aprobar', $reg->id) }}"
                                      onsubmit="return confirm('¿Aprobar a {{ $reg->nombre }} {{ $reg->apellido }}?')">
                                    @csrf
                                    <button type="submit" class="btn btn-ok">
                                        <i class="ti ti-check"></i> Aprobar
                                    </button>
                                </form>
                                <button class="btn btn-err" onclick="abrirModal('est','{{ $reg->id }}')">
                                    <i class="ti ti-x"></i> Rechazar
                                </button>
                                @else
                                <button class="btn btn-err" onclick="abrirModal('doc','{{ $reg->id }}')">
                                    <i class="ti ti-x"></i> Rechazar
                                </button>
                                @endif
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

{{-- MODAL RECHAZO ESTUDIANTE --}}
<div class="modal-overlay" id="modal-est">
    <div class="modal">
        <div class="modal-title"><i class="ti ti-alert-circle" style="color:#991b1b"></i> Motivo del rechazo</div>
        <form method="POST" id="form-rechazar-est">
            @csrf
            <textarea name="observacion" placeholder="Indica el motivo del rechazo..." required></textarea>
            <div class="modal-footer">
                <button type="button" class="btn-cancel" onclick="cerrarModal('est')">Cancelar</button>
                <button type="submit" class="btn-rechazar">Confirmar rechazo</button>
            </div>
        </form>
    </div>
</div>

{{-- MODAL RECHAZO DOCENTE --}}
<div class="modal-overlay" id="modal-doc">
    <div class="modal">
        <div class="modal-title"><i class="ti ti-alert-circle" style="color:#991b1b"></i> Motivo del rechazo</div>
        <form method="POST" id="form-rechazar-doc">
            @csrf
            <textarea name="observacion" placeholder="Indica el motivo del rechazo..." required></textarea>
            <div class="modal-footer">
                <button type="button" class="btn-cancel" onclick="cerrarModal('doc')">Cancelar</button>
                <button type="submit" class="btn-rechazar">Confirmar rechazo</button>
            </div>
        </form>
    </div>
</div>

<script>
function abrirModal(tipo, id) {
    const modal = document.getElementById('modal-' + tipo);
    const form  = document.getElementById('form-rechazar-' + tipo);
    const base  = tipo === 'est'
        ? '{{ url("admin/pre-registros/estudiante") }}'
        : '{{ url("admin/pre-registros/docente") }}';
    form.action = base + '/' + id + '/rechazar';
    modal.classList.add('open');
}
function cerrarModal(tipo) {
    document.getElementById('modal-' + tipo).classList.remove('open');
}
</script>

</body>
</html>