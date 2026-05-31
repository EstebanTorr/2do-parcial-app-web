<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'CUP — FICCT')</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet"/>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-100">

<div class="flex h-screen overflow-hidden">

    {{-- ══════════════════════════════════════
         SIDEBAR
    ══════════════════════════════════════ --}}
    <aside id="sidebar"
           class="w-56 bg-[#1a3353] flex flex-col flex-shrink-0 transition-all duration-300">

        {{-- Logo --}}
        <div class="h-14 flex items-center px-5 border-b border-white/10">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-2 text-white font-bold text-base tracking-wide">
                <svg class="w-5 h-5 text-blue-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 14l9-5-9-5-9 5 9 5z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 14l6.16-3.422A12 12 0 0112 21a12 12 0 01-6.16-3.422L12 14z"/>
                </svg>
                CUP — FICCT
            </a>
        </div>

        {{-- Navegación --}}
        <nav class="flex-1 overflow-y-auto py-4 px-3 space-y-5">

            {{-- MENÚ --}}
            <div>
                <p class="px-2 mb-1 text-[10px] font-semibold uppercase tracking-widest text-blue-300/60">Menú</p>
                <ul class="space-y-0.5">
                    <li>
                        <a href="{{ route('dashboard') }}"
                           class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm font-medium transition
                                  {{ request()->routeIs('dashboard') ? 'bg-white/15 text-white' : 'text-blue-100 hover:bg-white/10 hover:text-white' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                            </svg>
                            Inicio
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.pre-registros.index') }}"
                           class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm font-medium transition
                                  {{ request()->routeIs('admin.pre-registros.*') ? 'bg-white/15 text-white' : 'text-blue-100 hover:bg-white/10 hover:text-white' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            Pre-registros
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.postulantes.index') }}"
                           class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm font-medium transition
                                  {{ request()->routeIs('admin.postulantes.*') ? 'bg-white/15 text-white' : 'text-blue-100 hover:bg-white/10 hover:text-white' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.768-.231-1.48-.634-2.067M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.768.231-1.48.634-2.067M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            Postulantes
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.docentes.index') }}"
                           class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm font-medium transition
                                  {{ request()->routeIs('admin.docentes.*') ? 'bg-white/15 text-white' : 'text-blue-100 hover:bg-white/10 hover:text-white' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            Docentes
                        </a>
                    </li>
                </ul>
            </div>

            {{-- ACADÉMICO --}}
            <div>
                <p class="px-2 mb-1 text-[10px] font-semibold uppercase tracking-widest text-blue-300/60">Académico</p>
                <ul class="space-y-0.5">
                    <li>
                        <a href="{{ route('admin.grupos.index') }}"
                           class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm font-medium transition
                                  {{ request()->routeIs('admin.grupos.*') ? 'bg-white/15 text-white' : 'text-blue-100 hover:bg-white/10 hover:text-white' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                            </svg>
                            Grupos
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.examenes.index') }}"
                           class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm font-medium transition
                                  {{ request()->routeIs('admin.examenes.*') ? 'bg-white/15 text-white' : 'text-blue-100 hover:bg-white/10 hover:text-white' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                            Exámenes
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.convocatorias.index') }}"
                           class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm font-medium transition
                                  {{ request()->routeIs('admin.convocatorias.*') ? 'bg-white/15 text-white' : 'text-blue-100 hover:bg-white/10 hover:text-white' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            Convocatoria
                        </a>
                    </li>
                </ul>
            </div>

            {{-- SISTEMA --}}
            <div>
                <p class="px-2 mb-1 text-[10px] font-semibold uppercase tracking-widest text-blue-300/60">Sistema</p>
                <ul class="space-y-0.5">
                    <li>
                        <a href="{{ route('admin.reportes.index') }}"
                           class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm font-medium transition
                                  {{ request()->routeIs('admin.reportes.*') ? 'bg-white/15 text-white' : 'text-blue-100 hover:bg-white/10 hover:text-white' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                            Reportes
                        </a>
                    </li>
                </ul>
            </div>
        </nav>

        {{-- Footer sidebar --}}
        <div class="px-4 py-3 border-t border-white/10">
            <p class="text-[10px] text-blue-300/40 text-center">UAGRM © {{ date('Y') }}</p>
        </div>
    </aside>

    {{-- ══════════════════════════════════════
         ÁREA PRINCIPAL
    ══════════════════════════════════════ --}}
    <div class="flex-1 flex flex-col overflow-hidden">

        {{-- TOPBAR --}}
        <header class="h-14 bg-[#1a3353] flex items-center justify-between px-6 flex-shrink-0">

            {{-- Botón hamburguesa (mobile) --}}
            <button onclick="document.getElementById('sidebar').classList.toggle('-translate-x-full')"
                    class="text-white/70 hover:text-white lg:hidden">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>

            <div class="hidden lg:block"></div>

            {{-- Usuario --}}
            <div class="flex items-center gap-3">
                <div class="flex items-center gap-2 text-white text-sm">
                    <svg class="w-4 h-4 text-blue-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    <span class="font-medium">{{ Auth::user()->nombre ?? Auth::user()->name }} {{ Auth::user()->apellido ?? '' }}</span>
                </div>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                            class="flex items-center gap-1.5 text-xs text-white/70 hover:text-white border border-white/20 hover:border-white/40 px-3 py-1.5 rounded-lg transition">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                        Salir
                    </button>
                </form>
            </div>
        </header>

        {{-- CONTENIDO --}}
        <main class="flex-1 overflow-y-auto bg-gray-50">

            {{-- Flash messages --}}
            @if(session('success'))
                <div class="mx-6 mt-4 flex items-center gap-2 bg-green-50 border border-green-200 text-green-700 text-sm px-4 py-3 rounded-lg">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mx-6 mt-4 flex items-center gap-2 bg-red-50 border border-red-200 text-red-700 text-sm px-4 py-3 rounded-lg">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    {{ session('error') }}
                </div>
            @endif

            @yield('content')
        </main>
    </div>
</div>

</body>
</html>