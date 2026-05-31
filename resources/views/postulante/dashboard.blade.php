<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CUP — Mi Panel de Postulante</title>
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
            padding: 24px;
            font-size: 20px;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 10px;
            border-b: 1px solid rgba(255, 255, 255, 0.1);
        }
        .sidebar-menu {
            flex: 1;
            padding: 24px 16px;
            display: flex;
            flex-direction: column;
            gap: 6px;
        }
        .menu-label {
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            color: rgba(255, 255, 255, 0.4);
            padding: 10px 12px 6px;
            letter-spacing: 0.05em;
        }
        .menu-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px;
            color: rgba(255, 255, 255, 0.75);
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            border-radius: 10px;
            transition: all 0.2s;
        }
        .menu-item:hover {
            background: rgba(255, 255, 255, 0.08);
            color: #fff;
        }
        .menu-item.active {
            background: #2563eb;
            color: #fff;
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.2);
        }
        .sidebar-footer {
            padding: 20px;
            border-t: 1px solid rgba(255, 255, 255, 0.1);
            font-size: 12px;
            color: rgba(255, 255, 255, 0.4);
            text-align: center;
        }

        /* ── MAIN CONTENT ── */
        .main-content {
            margin-left: 260px;
            flex: 1;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        /* ── TOPBAR ── */
        .topbar {
            height: 70px;
            background: #fff;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 40px;
        }
        .welcome-msg h1 { font-size: 20px; font-weight: 700; color: #0f172a; }
        .welcome-msg p { font-size: 13px; color: #64748b; margin-top: 2px; }
        .user-profile {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #dbeafe;
            color: #2563eb;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 14px;
        }
        .user-info .name { font-size: 14px; font-weight: 600; color: #0f172a; }
        .user-info .code { font-size: 11px; color: #64748b; }

        .btn-logout {
            background: none; border: 1px solid #e2e8f0;
            padding: 8px 16px; border-radius: 8px;
            font-size: 13px; font-weight: 500; color: #64748b;
            cursor: pointer; display: flex; align-items: center; gap: 6px;
            transition: all 0.2s;
            font-family: 'Figtree', sans-serif;
        }
        .btn-logout:hover {
            background: #fee2e2; border-color: #fca5a5; color: #ef4444;
        }

        /* ── WRAPPER ── */
        .content-wrapper {
            padding: 40px;
            max-width: 1200px;
            width: 100%;
            margin: 0 auto;
            flex: 1;
        }

        /* ── ALERTAS DE ESTADO DE ADMISIÓN ── */
        .status-hero {
            border-radius: 16px;
            padding: 28px;
            margin-bottom: 30px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 4px 20px rgba(0,0,0,0.02);
            position: relative;
            overflow: hidden;
        }
        .status-hero::after {
            content: ''; position: absolute;
            width: 300px; height: 300px; border-radius: 50%;
            right: -80px; top: -80px;
            background: rgba(255,255,255,0.06);
            pointer-events: none;
        }

        .status-hero.aprobado {
            background: linear-gradient(135deg, #059669 0%, #10b981 100%);
            color: #fff;
        }
        .status-hero.rechazado {
            background: linear-gradient(135deg, #e11d48 0%, #f43f5e 100%);
            color: #fff;
        }
        .status-hero.evaluando {
            background: linear-gradient(135deg, #4f46e5 0%, #6366f1 100%);
            color: #fff;
        }

        .status-content {
            display: flex;
            align-items: center;
            gap: 20px;
            position: relative;
            z-index: 10;
        }
        .status-icon {
            width: 64px; height: 64px;
            border-radius: 16px;
            background: rgba(255,255,255,0.2);
            display: flex; align-items: center; justify-content: center;
            font-size: 32px;
        }
        .status-text h2 { font-size: 24px; font-weight: 700; }
        .status-text p { font-size: 14px; opacity: 0.9; margin-top: 4px; line-height: 1.5; }

        .status-badge {
            background: rgba(255, 255, 255, 0.25);
            border: 1px solid rgba(255, 255, 255, 0.35);
            padding: 8px 20px;
            border-radius: 99px;
            font-weight: 700;
            font-size: 14px;
            letter-spacing: 0.05em;
            text-transform: uppercase;
            box-shadow: 0 4px 10px rgba(0,0,0,0.05);
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }

        /* ── CARDS GRID ── */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        .stat-card {
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            padding: 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.01);
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(0,0,0,0.04);
        }
        .stat-info h3 { font-size: 13px; font-weight: 600; color: #64748b; text-transform: uppercase; letter-spacing: 0.03em; }
        .stat-info .value { font-size: 28px; font-weight: 700; color: #0f172a; margin-top: 8px; }
        .stat-info .desc { font-size: 12px; color: #94a3b8; margin-top: 4px; }
        .stat-icon {
            width: 48px; height: 48px; border-radius: 12px;
            background: #f1f5f9; color: #64748b;
            display: flex; align-items: center; justify-content: center;
            font-size: 22px;
        }
        .stat-card.primary .stat-icon { background: #eff6ff; color: #2563eb; }
        .stat-card.success .stat-icon { background: #ecfdf5; color: #10b981; }
        .stat-card.warning .stat-icon { background: #fffbeb; color: #f59e0b; }

        /* ── TWO COLUMNS ── */
        .two-columns {
            display: grid;
            grid-template-columns: 1.5fr 1fr;
            gap: 24px;
        }

        .card {
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            padding: 28px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.01);
        }
        .card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 24px;
        }
        .card-title {
            font-size: 16px;
            font-weight: 700;
            color: #0f172a;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .card-title i { color: #2563eb; font-size: 18px; }

        /* ── DATOS LIST ── */
        .info-list {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }
        .info-item {
            border-bottom: 1px solid #f1f5f9;
            padding-bottom: 12px;
        }
        .info-item:last-child, .info-item:nth-last-child(2) {
            border-bottom: none;
        }
        .info-label { font-size: 12px; color: #94a3b8; font-weight: 500; }
        .info-value { font-size: 14px; color: #334155; font-weight: 600; margin-top: 6px; }

        /* ── PREFERENCIAS ACADEMICAS ── */
        .pref-box {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }
        .pref-item {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 16px;
            display: flex;
            align-items: center;
            gap: 16px;
        }
        .pref-badge {
            width: 32px; height: 32px; border-radius: 50%;
            background: #2563eb; color: #fff;
            display: flex; align-items: center; justify-content: center;
            font-weight: 700; font-size: 12px;
        }
        .pref-item.second .pref-badge { background: #64748b; }
        .pref-details h4 { font-size: 14px; font-weight: 600; color: #334155; }
        .pref-details p { font-size: 11px; color: #94a3b8; margin-top: 2px; }

        @media (max-width: 992px) {
            .two-columns { grid-template-columns: 1fr; }
        }
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
            <a href="{{ route('postulante.dashboard') }}" class="menu-item active">
                <i class="ti ti-smart-home"></i>
                <span>Inicio</span>
            </a>
            <a href="{{ route('postulante.notas') }}" class="menu-item">
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
                <h1>Hola, {{ $postulante->nombre }}</h1>
                <p>Bienvenido al sistema de admisiones CUP de la FICCT.</p>
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

            {{-- DYNAMIC HERO STATUS --}}
            @if($estadisticas['estado'] === 'APROBADO' || $estadisticas['estado'] === 'ADMITIDO')
                <div class="status-hero aprobado">
                    <div class="status-content">
                        <div class="status-icon">🎉</div>
                        <div class="status-text">
                            <h2>¡Felicidades! Has sido Admitido</h2>
                            <p>Superaste exitosamente el proceso de admisión. Has sido asignado a la carrera de <strong>{{ $estadisticas['carrera_asignada']?->nombre ?? 'Carrera elegida' }}</strong>.</p>
                        </div>
                    </div>
                    <div class="status-badge">Admitido</div>
                </div>
            @elseif($estadisticas['estado'] === 'RECHAZADO' || $estadisticas['estado'] === 'REPROBADO_CUP')
                <div class="status-hero rechazado">
                    <div class="status-content">
                        <div class="status-icon">💪</div>
                        <div class="status-text">
                            <h2>Resultado del Proceso</h2>
                            <p>Tu promedio final es menor al puntaje mínimo de aprobación (51 pts). No te rindas, ¡sigue preparándote!</p>
                        </div>
                    </div>
                    <div class="status-badge">No Admitido</div>
                </div>
            @else
                <div class="status-hero evaluando">
                    <div class="status-content">
                        <div class="status-icon">📝</div>
                        <div class="status-text">
                            <h2>Proceso en Evaluación</h2>
                            <p>Tus calificaciones están siendo procesadas. Mantente atento a este panel para ver tu resultado final.</p>
                        </div>
                    </div>
                    <div class="status-badge">En Proceso</div>
                </div>
            @endif

            {{-- METRICS GRID --}}
            <div class="stats-grid">
                <div class="stat-card primary">
                    <div class="stat-info">
                        <h3>Promedio Actual</h3>
                        <div class="value">{{ number_format($estadisticas['promedio'] ?? 0, 2) }} pts</div>
                        <p class="desc">Sobre 100 puntos</p>
                    </div>
                    <div class="stat-icon">
                        <i class="ti ti-trophy"></i>
                    </div>
                </div>
                <div class="stat-card success">
                    <div class="stat-info">
                        <h3>Notas Registradas</h3>
                        <div class="value">{{ $estadisticas['notas_registradas'] }}</div>
                        <p class="desc">Evaluaciones calificadas</p>
                    </div>
                    <div class="stat-icon">
                        <i class="ti ti-list-check"></i>
                    </div>
                </div>
                <div class="stat-card warning">
                    <div class="stat-info">
                        <h3>Grupo Asignado</h3>
                        <div class="value">{{ $estadisticas['grupo']?->numero_grupo ?? 'Sin Asignar' }}</div>
                        <p class="desc">Turno: {{ $postulante->turno_asignado ?? 'Pendiente' }}</p>
                    </div>
                    <div class="stat-icon">
                        <i class="ti ti-id"></i>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-info">
                        <h3>Ranking</h3>
                        <div class="value">#{{ $estadisticas['posicion_ranking'] ?? '—' }}</div>
                        <p class="desc">Posición general</p>
                    </div>
                    <div class="stat-icon">
                        <i class="ti ti-trending-up"></i>
                    </div>
                </div>
            </div>

            {{-- TWO COLUMNS DETAILS --}}
            <div class="two-columns">
                {{-- PERSONAL INFO --}}
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">
                            <i class="ti ti-user"></i>
                            <span>Mis Datos Personales</span>
                        </div>
                    </div>
                    <div class="info-list">
                        <div class="info-item">
                            <div class="info-label">Nombre Completo</div>
                            <div class="info-value">{{ $postulante->nombre_completo }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Documento de Identidad</div>
                            <div class="info-value">{{ $postulante->ci }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Correo Electrónico</div>
                            <div class="info-value">{{ $postulante->email }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Teléfono de Contacto</div>
                            <div class="info-value">{{ $postulante->telefono }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Colegio de Procedencia</div>
                            <div class="info-value">{{ $postulante->colegio_nombre ?? 'No registrado' }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Convocatoria Activa</div>
                            <div class="info-value">{{ $postulante->convocatoria?->gestion ?? 'Gestión actual' }}</div>
                        </div>
                    </div>
                </div>

                {{-- CAREERS SELECTION --}}
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">
                            <i class="ti ti-books"></i>
                            <span>Carreras Solicitadas</span>
                        </div>
                    </div>
                    <div class="pref-box">
                        <div class="pref-item">
                            <div class="pref-badge">1</div>
                            <div class="pref-details">
                                <h4>{{ $postulante->carreraPref1?->nombre ?? 'Primera opción' }}</h4>
                                <p>Primera Preferencia</p>
                            </div>
                        </div>
                        <div class="pref-item second">
                            <div class="pref-badge">2</div>
                            <div class="pref-details">
                                <h4>{{ $postulante->carreraPref2?->nombre ?? 'Segunda opción' }}</h4>
                                <p>Segunda Preferencia</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </main>
    </div>

</body>
</html>
