<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CUP — Mi Grupo de Admisión</title>
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
        .menu-label {
            font-size: 11px; font-weight: 600; text-transform: uppercase;
            color: rgba(255, 255, 255, 0.4); padding: 10px 12px 6px; letter-spacing: 0.05em;
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

        /* ── HERO BANNER ── */
        .group-hero {
            background: linear-gradient(135deg, #1e3a6e 0%, #162b4e 100%);
            border-radius: 16px;
            padding: 32px;
            color: #fff;
            margin-bottom: 30px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .hero-title h2 { font-size: 24px; font-weight: 700; }
        .hero-title p { font-size: 14px; color: #a8c8f0; margin-top: 6px; }
        
        .turno-badge {
            background: rgba(255,255,255,0.15);
            border: 1px solid rgba(255,255,255,0.25);
            color: #fff;
            padding: 6px 16px;
            border-radius: 99px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
        }

        /* ── GRID ── */
        .grid-layout {
            display: grid;
            grid-template-columns: 1fr 1.2fr;
            gap: 24px;
        }

        .card {
            background: #fff; border: 1px solid #e2e8f0; border-radius: 16px;
            padding: 24px; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.01);
        }
        .card-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px; border-bottom: 1px solid #f1f5f9; padding-bottom: 14px; }
        .card-title {
            font-size: 16px; font-weight: 700; color: #0f172a;
            display: flex; align-items: center; gap: 8px;
        }
        .card-title i { color: #2563eb; font-size: 18px; }

        /* ── LIST ITEMS ── */
        .list-items { display: flex; flex-direction: column; gap: 14px; }
        .list-item {
            display: flex;
            align-items: center;
            gap: 16px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 16px;
        }
        .item-icon {
            width: 44px; height: 44px; border-radius: 10px;
            background: #eff6ff; color: #2563eb;
            display: flex; align-items: center; justify-content: center;
            font-size: 20px;
        }
        .list-item.docente .item-icon { background: #ecfdf5; color: #10b981; }
        .item-details h4 { font-size: 14px; font-weight: 600; color: #1e293b; }
        .item-details p { font-size: 12px; color: #64748b; margin-top: 2px; }

        @media (max-width: 768px) {
            .grid-layout { grid-template-columns: 1fr; }
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
            <a href="{{ route('postulante.dashboard') }}" class="menu-item">
                <i class="ti ti-smart-home"></i>
                <span>Inicio</span>
            </a>
            <a href="{{ route('postulante.notas') }}" class="menu-item">
                <i class="ti ti-file-analytics"></i>
                <span>Mis Notas</span>
            </a>
            <a href="{{ route('postulante.grupo') }}" class="menu-item active">
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
                <h1>Mi Grupo Asignado</h1>
                <p>Información detallada de tu grupo, docentes y horarios.</p>
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

            {{-- HERO BANNER --}}
            <div class="group-hero">
                <div class="hero-title">
                    <h2>Grupo {{ $grupo->numero_grupo }}</h2>
                    <p>Facultad de Ciencias de la Computación y Telecomunicaciones</p>
                </div>
                <div class="turno-badge">
                    Turno: {{ $grupo->turno }}
                </div>
            </div>

            {{-- GRID --}}
            <div class="grid-layout">
                {{-- MATERIAS --}}
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">
                            <i class="ti ti-notebook"></i>
                            <span>Materias del Curso</span>
                        </div>
                    </div>
                    <div class="list-items">
                        @forelse($grupo->materias as $materia)
                            <div class="list-item">
                                <div class="item-icon">
                                    <i class="ti ti-book"></i>
                                </div>
                                <div class="item-details">
                                    <h4>{{ $materia->nombre }}</h4>
                                    <p>Código: {{ $materia->codigo }}</p>
                                </div>
                            </div>
                        @empty
                            <p style="color: #64748b; font-style: italic;">No hay materias registradas en este grupo.</p>
                        @endforelse
                    </div>
                </div>

                {{-- DOCENTES --}}
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">
                            <i class="ti ti-chalkboard"></i>
                            <span>Docentes Designados</span>
                        </div>
                    </div>
                    <div class="list-items">
                        @forelse($grupo->docentes as $docente)
                            <div class="list-item docente">
                                <div class="item-icon">
                                    <i class="ti ti-user-check"></i>
                                </div>
                                <div class="item-details">
                                    <h4>{{ $docente->nombre }} {{ $docente->apellido }}</h4>
                                    <p>Email: {{ $docente->email }} | Especialidad: {{ $docente->especialidad ?? 'Computación' }}</p>
                                </div>
                            </div>
                        @empty
                            <p style="color: #64748b; font-style: italic;">No hay docentes designados para este grupo aún.</p>
                        @endforelse
                    </div>
                </div>
            </div>

        </main>
    </div>

</body>
</html>
