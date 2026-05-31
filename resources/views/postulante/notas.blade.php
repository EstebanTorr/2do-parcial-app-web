<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CUP — Mis Calificaciones</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Figtree:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Figtree', sans-serif; background: #f8fafc; color: #1e293b; min-height: 100vh; display: flex; }

        /* ── SIDEBAR ── */
        .sidebar {
            width: 260px;
            background: linear-gradient(180deg, #1e3a6e 0%, #0f2147 100%);
            color: #fff;
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0; bottom: 0; left: 0;
            z-index: 100;
        }
        .sidebar-brand {
            padding: 24px; font-size: 20px; font-weight: 700;
            display: flex; align-items: center; gap: 10px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        .sidebar-menu {
            flex: 1; padding: 24px 16px;
            display: flex; flex-direction: column; gap: 6px;
        }
        .menu-label {
            font-size: 11px; font-weight: 600; text-transform: uppercase;
            color: rgba(255, 255, 255, 0.4); padding: 10px 12px 6px; letter-spacing: 0.05em;
        }
        .menu-item {
            display: flex; align-items: center; gap: 12px; padding: 12px;
            color: rgba(255, 255, 255, 0.75); text-decoration: none;
            font-size: 14px; font-weight: 500; border-radius: 10px; transition: all 0.2s;
        }
        .menu-item:hover { background: rgba(255, 255, 255, 0.08); color: #fff; }
        .menu-item.active {
            background: #2563eb; color: #fff;
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.2);
        }
        .sidebar-footer {
            padding: 20px; border-top: 1px solid rgba(255, 255, 255, 0.1);
            font-size: 12px; color: rgba(255, 255, 255, 0.4); text-align: center;
        }

        /* ── MAIN CONTENT ── */
        .main-content {
            margin-left: 260px; flex: 1;
            display: flex; flex-direction: column; min-height: 100vh;
        }

        /* ── TOPBAR ── */
        .topbar {
            height: 70px; background: #fff; border-bottom: 1px solid #e2e8f0;
            display: flex; align-items: center; justify-content: space-between; padding: 0 40px;
        }
        .welcome-msg h1 { font-size: 20px; font-weight: 700; color: #0f172a; }
        .welcome-msg p { font-size: 13px; color: #64748b; margin-top: 2px; }
        .user-profile { display: flex; align-items: center; gap: 12px; }
        .avatar {
            width: 40px; height: 40px; border-radius: 50%;
            background: #dbeafe; color: #2563eb;
            display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 14px;
        }
        .user-info .name { font-size: 14px; font-weight: 600; color: #0f172a; }
        .user-info .code { font-size: 11px; color: #64748b; }

        .btn-logout {
            background: none; border: 1px solid #e2e8f0;
            padding: 8px 16px; border-radius: 8px;
            font-size: 13px; font-weight: 500; color: #64748b;
            cursor: pointer; display: flex; align-items: center; gap: 6px;
            transition: all 0.2s; font-family: 'Figtree', sans-serif;
        }
        .btn-logout:hover { background: #fee2e2; border-color: #fca5a5; color: #ef4444; }

        /* ── WRAPPER ── */
        .content-wrapper {
            padding: 40px; max-width: 1200px; width: 100%; margin: 0 auto; flex: 1;
        }

        .card {
            background: #fff; border: 1px solid #e2e8f0; border-radius: 16px;
            padding: 28px; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.01);
            margin-bottom: 24px;
        }
        .card-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px; }
        .card-title {
            font-size: 16px; font-weight: 700; color: #0f172a;
            display: flex; align-items: center; gap: 8px;
        }
        .card-title i { color: #2563eb; font-size: 18px; }

        /* ── NOTAS TABLE ── */
        .materia-section {
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.01);
        }
        .materia-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid #f1f5f9;
            padding-bottom: 14px;
            margin-bottom: 14px;
        }
        .materia-name { font-size: 15px; font-weight: 700; color: #1e3a6e; }
        .materia-docente { font-size: 12px; color: #64748b; margin-top: 2px; }

        table { width: 100%; border-collapse: collapse; text-align: left; }
        th { font-size: 11px; text-transform: uppercase; color: #94a3b8; font-weight: 600; padding: 10px 8px; border-bottom: 1px solid #f1f5f9; }
        td { font-size: 13px; color: #475569; padding: 12px 8px; border-bottom: 1px solid #f8fafc; }
        tr:last-child td { border-bottom: none; }

        .score-badge {
            display: inline-flex; align-items: center; justify-content: center;
            padding: 4px 10px; border-radius: 6px; font-weight: 700; font-size: 12px;
        }
        .score-badge.approved { background: #d1fae5; color: #065f46; }
        .score-badge.failed { background: #fee2e2; color: #991b1b; }

        /* ── EMPTY STATE ── */
        .empty-state {
            text-align: center; padding: 48px 24px; color: #94a3b8;
        }
        .empty-state i { font-size: 40px; color: #cbd5e1; margin-bottom: 12px; }
        .empty-state h3 { font-size: 16px; font-weight: 600; color: #64748b; }
        .empty-state p { font-size: 13px; color: #94a3b8; margin-top: 6px; }
    </style>
</head>
<body>

    {{-- SIDEBAR --}}
    <aside class="sidebar">
        <div class="sidebar-brand">
            <i class="ti ti-school"></i>
            <span>CUP — FICCT</span>
        </div>
        <nav class="sidebar-menu">
            <div class="menu-label">Mi Cuenta</div>
            <a href="{{ route('postulante.dashboard') }}" class="menu-item">
                <i class="ti ti-smart-home"></i>
                <span>Inicio</span>
            </a>
            <a href="{{ route('postulante.notas') }}" class="menu-item active">
                <i class="ti ti-file-analytics"></i>
                <span>Mis Notas</span>
            </a>
            <a href="{{ route('postulante.grupo') }}" class="menu-item">
                <i class="ti ti-users-group"></i>
                <span>Mi Grupo</span>
            </a>
        </nav>
        <div class="sidebar-footer">
            UAGRM &copy; {{ date('Y') }}
        </div>
    </aside>

    {{-- MAIN CONTENT --}}
    <div class="main-content">
        {{-- TOPBAR --}}
        <header class="topbar">
            <div class="welcome-msg">
                <h1>Mis Calificaciones</h1>
                <p>Historial y desglose detallado de tus notas por materia.</p>
            </div>
            <div class="user-profile">
                <div class="avatar">
                    {{ $postulante->iniciales }}
                </div>
                <div class="user-info">
                    <div class="name">{{ $postulante->nombre_completo }}</div>
                    <div class="code">Reg: {{ $postulante->codigo_estudiante }}</div>
                </div>
                <form method="POST" action="{{ route('logout') }}" style="margin-left: 10px;">
                    @csrf
                    <button type="submit" class="btn-logout">
                        <i class="ti ti-logout"></i>
                        <span>Cerrar Sesión</span>
                    </button>
                </form>
            </div>
        </header>

        {{-- CONTENT WRAPPER --}}
        <main class="content-wrapper">

            @if($notas->isEmpty())
                <div class="card">
                    <div class="empty-state">
                        <i class="ti ti-checklist"></i>
                        <h3>Aún no se registraron calificaciones</h3>
                        <p>Cuando los docentes califiquen tus evaluaciones, aparecerán en esta sección de forma inmediata.</p>
                    </div>
                </div>
            @else
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">
                            <i class="ti ti-bookmark"></i>
                            <span>Calificaciones por materia</span>
                        </div>
                    </div>

                    @foreach($notas as $materiaId => $grupoNotas)
                        @php
                            $materia = $grupoNotas->first()->materia;
                            $docente = $grupoNotas->first()->docente;
                        @endphp
                        <div class="materia-section">
                            <div class="materia-header">
                                <div>
                                    <h3 class="materia-name">{{ $materia->nombre }}</h3>
                                    <div class="materia-docente">
                                        <i class="ti ti-school-bell"></i> Docente: 
                                        {{ $docente ? $docente->nombre . ' ' . $docente->apellido : 'Por designar' }}
                                    </div>
                                </div>
                            </div>
                            
                            <table>
                                <thead>
                                    <tr>
                                        <th>Fecha Registro</th>
                                        <th>Calificación</th>
                                        <th>Estado</th>
                                        <th>Observaciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($grupoNotas as $nota)
                                    <tr>
                                        <td>{{ $nota->created_at ? $nota->created_at->format('d/m/Y H:i') : '—' }}</td>
                                        <td style="font-weight: 700; font-size: 15px;">{{ number_format($nota->calificacion, 2) }} pts</td>
                                        <td>
                                            <span class="score-badge {{ $nota->calificacion >= 51 ? 'approved' : 'failed' }}">
                                                {{ $nota->calificacion >= 51 ? 'Aprobado' : 'Reprobado' }}
                                            </span>
                                        </td>
                                        <td style="color: #64748b; font-style: italic;">{{ $nota->observaciones ?? 'Ninguna' }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endforeach
                </div>
            @endif

        </main>
    </div>

</body>
</html>
