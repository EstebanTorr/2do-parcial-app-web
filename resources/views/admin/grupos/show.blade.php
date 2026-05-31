@extends('layouts.admin')

@section('title', "CUP — Grupo {$grupo->numero_grupo}")

@section('content')
<div class="px-8 py-8 max-w-7xl mx-auto w-100">

    {{-- ENCABEZADO DE LA PÁGINA --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <div>
            <div class="flex items-center gap-2 text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1.5">
                <a href="{{ route('admin.grupos.index') }}" class="hover:text-[#1e3a6e] transition">Grupos</a>
                <i class="ti ti-chevron-right text-[10px]"></i>
                <span class="text-slate-600">Detalles del Grupo {{ $grupo->numero_grupo }}</span>
            </div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight flex items-center gap-2">
                <i class="ti ti-layout-grid text-[#1e3a6e]"></i>
                Comisión: Grupo {{ $grupo->numero_grupo }}
            </h1>
            <p class="text-sm text-slate-500 mt-1">Configuración general y distribución de alumnos y docentes para esta aula.</p>
        </div>

        {{-- BOTÓN VOLVER --}}
        <div>
            <a href="{{ route('admin.grupos.index', ['convocatoria_id' => $grupo->convocatoria_id]) }}" 
               class="inline-flex items-center gap-2 bg-white hover:bg-slate-50 text-slate-700 font-semibold py-2.5 px-4 rounded-xl text-sm transition border border-slate-200 shadow-sm cursor-pointer text-decoration-none">
                <i class="ti ti-arrow-left"></i>
                Volver a la Lista
            </a>
        </div>
    </div>

    {{-- ALERTAS DE ÉXITO O ERROR --}}
    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-100 text-emerald-800 rounded-2xl px-6 py-4 mb-8 flex items-center gap-3">
            <span class="text-xl">✨</span>
            <p class="text-sm font-semibold mb-0">{{ session('success') }}</p>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-rose-50 border border-rose-100 text-rose-800 rounded-2xl px-6 py-4 mb-8 flex items-center gap-3">
            <span class="text-xl">⚠️</span>
            <p class="text-sm font-semibold mb-0">{{ session('error') }}</p>
        </div>
    @endif

    {{-- CONTENIDO PRINCIPAL --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        {{-- COLUMNA 1: INFO Y MÉTRICAS DEL GRUPO (SIDEBAR) --}}
        <div class="lg:col-span-1 space-y-6">
            <div class="bg-white border border-slate-200/80 rounded-2xl p-6 shadow-sm">
                <h3 class="text-base font-bold text-slate-800 mb-4 flex items-center gap-2">
                    <i class="ti ti-info-circle text-[#1e3a6e]"></i>
                    Información del Grupo
                </h3>

                {{-- TURNO DESTACADO --}}
                <div class="bg-slate-50 border border-slate-100 rounded-2xl p-4 mb-6 flex items-center justify-between">
                    <div>
                        <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-wide">Turno de Clases</span>
                        <span class="text-base font-bold text-slate-800 flex items-center gap-1.5 mt-1">
                            @if($grupo->turno == 'MAÑANA')
                                🌅 Mañana (07:30 - 12:30)
                            @elseif($grupo->turno == 'TARDE')
                                ☀️ Tarde (13:00 - 18:00)
                            @else
                                🌙 Noche (18:30 - 22:00)
                            @endif
                        </span>
                    </div>
                </div>

                {{-- MEDIDOR DE CAPACIDAD --}}
                @php
                    $usado = $grupo->postulantes->count();
                    $capacidadEfectiva = min($grupo->capacidad_maxima, 70);
                    $porcentaje = $capacidadEfectiva > 0 ? ($usado / $capacidadEfectiva) * 100 : 0;
                    
                    $progresoColor = 'bg-emerald-500';
                    $textoCupoColor = 'text-emerald-600';
                    if ($porcentaje >= 100) {
                        $progresoColor = 'bg-rose-500';
                        $textoCupoColor = 'text-rose-600';
                    } elseif ($porcentaje >= 70) {
                        $progresoColor = 'bg-amber-500';
                        $textoCupoColor = 'text-amber-600';
                    }
                @endphp
                <div class="border-b border-slate-100 pb-6 mb-6">
                    <div class="flex justify-between text-xs font-semibold mb-2">
                        <span class="text-slate-400">Ocupación de Capacidad</span>
                        <span class="text-slate-700">{{ $usado }} / {{ $capacidadEfectiva }} Alumnos</span>
                    </div>
                    <div class="w-full bg-slate-100 rounded-full h-3 overflow-hidden mb-3">
                        <div class="h-full rounded-full {{ $progresoColor }} transition-all duration-500" style="width: {{ min($porcentaje, 100) }}%"></div>
                    </div>
                    <p class="text-xs text-slate-500">
                        Quedan <strong class="{{ $textoCupoColor }}">{{ max(0, $capacidadEfectiva - $usado) }} vacantes</strong> disponibles en este grupo.
                    </p>
                </div>

                {{-- DETALLES GENERALES --}}
                <div class="space-y-4">
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-slate-500">Convocatoria:</span>
                        <span class="font-semibold text-slate-800">{{ $grupo->convocatoria->nombre }}</span>
                    </div>
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-slate-500">Estado de Grupo:</span>
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold uppercase tracking-wider bg-emerald-50 text-emerald-700 border border-emerald-100">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                            {{ $grupo->estado }}
                        </span>
                    </div>
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-slate-500">Capacidad Máxima:</span>
                        <span class="font-bold text-slate-800">{{ $capacidadEfectiva }} Alumnos</span>
                    </div>
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-slate-500">Límite CUP:</span>
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-lg text-[10px] font-bold bg-[#1e3a6e]/10 text-[#1e3a6e]">
                            <i class="ti ti-lock"></i> Máx. 70 por grupo
                        </span>
                    </div>
                </div>
            </div>

            {{-- PANEL DE ESTADÍSTICAS GENERALES DE CONVOCATORIA --}}
            <div class="bg-white border border-slate-200/80 rounded-2xl p-6 shadow-sm">
                <h3 class="text-base font-bold text-slate-800 mb-4 flex items-center gap-2">
                    <i class="ti ti-chart-bar text-[#1e3a6e]"></i>
                    Convocatoria Global
                </h3>
                <div class="space-y-3">
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-slate-500">Postulantes APROBADOS:</span>
                        <span class="font-bold text-emerald-700">{{ $totalAprobados }}</span>
                    </div>
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-slate-500">Ya con grupo:</span>
                        <span class="font-bold text-[#1e3a6e]">{{ $totalAsignados }}</span>
                    </div>
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-slate-500">Sin grupo aún:</span>
                        <span class="font-bold text-amber-600">{{ max(0, $totalAprobados - $totalAsignados) }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- COLUMNA 2: LISTADOS DE PERSONAS (POSTULANTES Y DOCENTES) --}}
        <div class="lg:col-span-2 space-y-8">
            
            {{-- SECCIÓN POSTULANTES --}}
            <div class="bg-white border border-slate-200/80 rounded-2xl shadow-sm overflow-hidden">
                <div class="border-b border-slate-100 px-6 py-4 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div>
                        <h3 class="text-base font-bold text-slate-800 flex items-center gap-2">
                            <i class="ti ti-users text-[#1e3a6e]"></i>
                            Postulantes Asignados
                            <span class="bg-[#1e3a6e]/10 text-[#1e3a6e] text-xs px-2.5 py-0.5 rounded-full font-bold">
                                {{ $usado }}
                            </span>
                        </h3>
                    </div>
                    
                    {{-- BUSCADOR REACTIVO DE POSTULANTES --}}
                    @if($grupo->postulantes->isNotEmpty())
                        <div class="relative w-full sm:w-64">
                            <input type="text" id="searchPostulantes" onkeyup="filterPostulantes()" 
                                   placeholder="Buscar por nombre o CI..."
                                   class="w-full bg-slate-50 border border-slate-200 rounded-xl pl-9 pr-4 py-2 text-xs font-medium text-slate-700 focus:outline-none focus:border-[#1e3a6e] focus:ring-1 focus:ring-[#1e3a6e] transition">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                                <i class="ti ti-search text-sm"></i>
                            </div>
                        </div>
                    @endif
                </div>

                <div class="p-6">
                    @if($grupo->postulantes->isEmpty())
                        {{-- ESTADO VACÍO --}}
                        <div class="border-2 border-dashed border-slate-100 rounded-2xl p-8 flex flex-col items-center justify-center text-center">
                            <div class="w-12 h-12 rounded-xl bg-slate-50 flex items-center justify-center text-slate-300 mb-4">
                                <i class="ti ti-users text-xl"></i>
                            </div>
                            <h4 class="text-sm font-bold text-slate-700">Sin alumnos asignados</h4>
                            <p class="text-xs text-slate-400 max-w-xs mt-1">Usa la sección inferior para añadir postulantes con estado <strong class="text-emerald-600">APROBADO</strong> individualmente a este grupo.</p>
                        </div>
                    @else
                        {{-- LISTA DE POSTULANTES --}}
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse" id="postulantesTable">
                                <thead>
                                    <tr class="border-b border-slate-100 text-xs font-bold text-slate-400 uppercase tracking-wider">
                                        <th class="pb-3 pl-2">Postulante</th>
                                        <th class="pb-3">Cédula de Identidad</th>
                                        <th class="pb-3">Estado</th>
                                        <th class="pb-3 text-right pr-2">Acción</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-50">
                                    @foreach($grupo->postulantes as $postulante)
                                        @php
                                            $nombres = explode(' ', $postulante->nombre_completo);
                                            $iniciales = (count($nombres) > 1) 
                                                ? strtoupper(substr($nombres[0], 0, 1) . substr($nombres[count($nombres)-1], 0, 1))
                                                : strtoupper(substr($postulante->nombre_completo, 0, 2));
                                            
                                            $charVal = ord(substr($iniciales, 0, 1));
                                            $hue = ($charVal * 15) % 360;
                                            $bgCol = "hsl({$hue}, 85%, 95%)";
                                            $txtCol = "hsl({$hue}, 85%, 35%)";
                                        @endphp
                                        <tr class="hover:bg-slate-50/50 transition duration-150" 
                                            data-name="{{ $postulante->nombre_completo }}" 
                                            data-ci="{{ $postulante->ci }}">
                                            <td class="py-3.5 pl-2 flex items-center gap-3">
                                                <div class="w-9 h-9 rounded-full flex items-center justify-center font-bold text-xs shadow-sm uppercase shrink-0" 
                                                     style="background-color: {{ $bgCol }}; color: {{ $txtCol }}; border: 1px solid hsl({{ $hue }}, 85%, 90%);">
                                                    {{ $iniciales }}
                                                </div>
                                                <div>
                                                    <span class="block text-sm font-semibold text-slate-800">{{ $postulante->nombre_completo }}</span>
                                                    <span class="block text-[10px] text-slate-400">Postulante oficial</span>
                                                </div>
                                            </td>
                                            <td class="py-3.5 text-sm font-medium text-slate-600">
                                                {{ $postulante->ci }}
                                            </td>
                                            <td class="py-3.5">
                                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-lg text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-100">
                                                    <i class="ti ti-circle-check"></i>
                                                    {{ $postulante->estado }}
                                                </span>
                                            </td>
                                            <td class="py-3.5 text-right pr-2">
                                                <form method="POST" action="{{ route('admin.grupos.desasignar-postulante', $grupo) }}" class="inline">
                                                    @csrf
                                                    <input type="hidden" name="postulante_id" value="{{ $postulante->id }}">
                                                    <button type="submit" 
                                                            class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-rose-50 hover:bg-rose-100 text-rose-600 transition border-0 cursor-pointer shadow-sm"
                                                            onclick="return confirm('¿Desasignar a este postulante del grupo {{ $grupo->numero_grupo }}?')">
                                                        <i class="ti ti-trash text-sm"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        {{-- ALERTA SI NO HAY RESULTADOS DEL FILTRO --}}
                        <div id="noPostulantesMatch" class="text-center py-6 text-slate-400 text-xs font-semibold" style="display: none;">
                            No se encontraron postulantes que coincidan con la búsqueda.
                        </div>
                    @endif

                    {{-- AGREGAR POSTULANTE --}}
                    @php $capacidadEfectiva = min($grupo->capacidad_maxima, 70); @endphp
                    @if($postulantesSinGrupo->isNotEmpty() && $grupo->postulantes->count() < $capacidadEfectiva)
                        <div class="mt-6 border-t border-slate-100 pt-6">
                            <div class="flex items-center justify-between mb-3">
                                <h4 class="text-xs font-bold text-slate-500 uppercase tracking-wider">Agregar Postulante a esta Comisión</h4>
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-lg text-[10px] font-semibold bg-emerald-50 text-emerald-700">
                                    <i class="ti ti-circle-check"></i> Solo postulantes APROBADOS
                                </span>
                            </div>
                            <form method="POST" action="{{ route('admin.grupos.asignar-postulante', $grupo) }}" class="flex flex-col sm:flex-row gap-3">
                                @csrf
                                <div class="relative flex-1">
                                    <select name="postulante_id" required 
                                            class="w-full appearance-none bg-white border border-slate-200 text-slate-700 py-2.5 pl-4 pr-10 rounded-xl text-sm font-medium focus:outline-none focus:border-[#1e3a6e] focus:ring-1 focus:ring-[#1e3a6e] transition cursor-pointer">
                                        <option value="">-- Seleccionar Postulante APROBADO --</option>
                                        @foreach($postulantesSinGrupo as $p)
                                            <option value="{{ $p->id }}">{{ $p->nombre_completo }} (CI: {{ $p->ci }})</option>
                                        @endforeach
                                    </select>
                                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-slate-500">
                                        <i class="ti ti-chevron-down text-xs"></i>
                                    </div>
                                </div>
                                <button type="submit" 
                                        class="bg-[#1e3a6e] hover:bg-[#0f2147] text-white font-semibold py-2.5 px-5 rounded-xl text-sm transition shadow-sm flex items-center justify-center gap-2 border-0 cursor-pointer">
                                    <i class="ti ti-plus"></i>
                                    Asignar Alumno
                                </button>
                            </form>
                        </div>
                    @elseif($grupo->postulantes->count() >= $capacidadEfectiva)
                        <div class="mt-6 bg-rose-50/80 border border-rose-100 rounded-xl p-3.5 flex items-start gap-2.5 text-xs font-semibold text-rose-800">
                            <span class="mt-0.5">🚫</span>
                            <div>
                                <span class="block font-bold">Grupo lleno — Límite máximo alcanzado</span>
                                <span class="font-normal text-rose-700">Este grupo ha alcanzado la capacidad máxima de <strong>{{ $capacidadEfectiva }} estudiantes</strong> permitida por el reglamento CUP. Desasigna un postulante para poder agregar otro.</span>
                            </div>
                        </div>
                    @elseif($postulantesSinGrupo->isEmpty())
                        <div class="mt-6 bg-slate-50 border border-slate-100 rounded-xl p-3.5 flex items-center gap-2.5 text-xs text-slate-500">
                            <i class="ti ti-info-circle text-slate-400"></i>
                            No hay postulantes con estado <strong class="text-emerald-700">APROBADO</strong> pendientes de asignar a esta convocatoria.
                        </div>
                    @endif
                </div>
            </div>

            {{-- SECCIÓN DOCENTES --}}
            <div class="bg-white border border-slate-200/80 rounded-2xl shadow-sm overflow-hidden">
                <div class="border-b border-slate-100 px-6 py-4 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div>
                        <h3 class="text-base font-bold text-slate-800 flex items-center gap-2">
                            <i class="ti ti-chalkboard text-emerald-600"></i>
                            Docentes Asignados
                            <span class="bg-emerald-50 text-emerald-600 text-xs px-2.5 py-0.5 rounded-full font-bold">
                                {{ $grupo->docentes->count() }}
                            </span>
                        </h3>
                    </div>
                    
                    {{-- BUSCADOR REACTIVO DE DOCENTES --}}
                    @if($grupo->docentes->isNotEmpty())
                        <div class="relative w-full sm:w-64">
                            <input type="text" id="searchDocentes" onkeyup="filterDocentes()" 
                                   placeholder="Buscar docente asignado..."
                                   class="w-full bg-slate-50 border border-slate-200 rounded-xl pl-9 pr-4 py-2 text-xs font-medium text-slate-700 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                                <i class="ti ti-search text-sm"></i>
                            </div>
                        </div>
                    @endif
                </div>

                <div class="p-6">
                    @if($grupo->docentes->isEmpty())
                        {{-- ESTADO VACÍO --}}
                        <div class="border-2 border-dashed border-slate-100 rounded-2xl p-8 flex flex-col items-center justify-center text-center">
                            <div class="w-12 h-12 rounded-xl bg-slate-50 flex items-center justify-center text-slate-300 mb-4">
                                <i class="ti ti-chalkboard text-xl"></i>
                            </div>
                            <h4 class="text-sm font-bold text-slate-700">Sin docentes asignados</h4>
                            <p class="text-xs text-slate-400 max-w-xs mt-1">Este grupo requiere de al menos un docente para calificar exámenes y registrar el rendimiento.</p>
                        </div>
                    @else
                        {{-- LISTA DE DOCENTES --}}
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse" id="docentesTable">
                                <thead>
                                    <tr class="border-b border-slate-100 text-xs font-bold text-slate-400 uppercase tracking-wider">
                                        <th class="pb-3 pl-2">Docente</th>
                                        <th class="pb-3">Correo Institucional</th>
                                        <th class="pb-3">Especialidad</th>
                                        <th class="pb-3 text-right pr-2">Acción</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-50">
                                    @foreach($grupo->docentes as $docente)
                                        @php
                                            $nombresD = explode(' ', $docente->nombre_completo);
                                            $inicialesD = (count($nombresD) > 1) 
                                                ? strtoupper(substr($nombresD[0], 0, 1) . substr($nombresD[count($nombresD)-1], 0, 1))
                                                : strtoupper(substr($docente->nombre_completo, 0, 2));
                                            
                                            $charValD = ord(substr($inicialesD, 0, 1));
                                            $hueD = ($charValD * 33) % 360;
                                            $bgColD = "hsl({$hueD}, 85%, 95%)";
                                            $txtColD = "hsl({$hueD}, 85%, 35%)";
                                        @endphp
                                        <tr class="hover:bg-slate-50/50 transition duration-150" 
                                            data-name="{{ $docente->nombre_completo }}"
                                            data-specialty="{{ $docente->especialidad }}">
                                            <td class="py-3.5 pl-2 flex items-center gap-3">
                                                <div class="w-9 h-9 rounded-full flex items-center justify-center font-bold text-xs shadow-sm uppercase shrink-0" 
                                                     style="background-color: {{ $bgColD }}; color: {{ $txtColD }}; border: 1px solid hsl({{ $hueD }}, 85%, 90%);">
                                                    {{ $inicialesD }}
                                                </div>
                                                <div>
                                                    <span class="block text-sm font-semibold text-slate-800">{{ $docente->nombre_completo }}</span>
                                                    <span class="block text-[10px] text-emerald-600 font-semibold uppercase tracking-wider">CUP Docente</span>
                                                </div>
                                            </td>
                                            <td class="py-3.5 text-sm text-slate-500">
                                                {{ $docente->email }}
                                            </td>
                                            <td class="py-3.5">
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-lg text-xs font-semibold bg-slate-100 text-slate-600">
                                                    {{ $docente->especialidad }}
                                                </span>
                                            </td>
                                            <td class="py-3.5 text-right pr-2">
                                                <form method="POST" action="{{ route('admin.grupos.desasignar-docente', $grupo) }}" class="inline">
                                                    @csrf
                                                    <input type="hidden" name="docente_id" value="{{ $docente->id }}">
                                                    <button type="submit" 
                                                            class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-rose-50 hover:bg-rose-100 text-rose-600 transition border-0 cursor-pointer shadow-sm"
                                                            onclick="return confirm('¿Desasignar al docente del grupo {{ $grupo->numero_grupo }}?')">
                                                        <i class="ti ti-trash text-sm"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        {{-- ALERTA SI NO HAY RESULTADOS --}}
                        <div id="noDocentesMatch" class="text-center py-6 text-slate-400 text-xs font-semibold" style="display: none;">
                            No se encontraron docentes asignados que coincidan.
                        </div>
                    @endif

                    {{-- AGREGAR DOCENTE --}}
                    @if($docentesDisponibles->isNotEmpty())
                        <div class="mt-6 border-t border-slate-100 pt-6">
                            <div class="flex items-center justify-between mb-3">
                                <h4 class="text-xs font-bold text-slate-500 uppercase tracking-wider">Asignar Docente a este Grupo</h4>
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-lg text-[10px] font-semibold bg-emerald-50 text-emerald-700">
                                    <i class="ti ti-circle-check"></i> Solo docentes ACTIVOS
                                </span>
                            </div>
                            <form method="POST" action="{{ route('admin.grupos.asignar-docente', $grupo) }}" class="flex flex-col sm:flex-row gap-3">
                                @csrf
                                <div class="relative flex-1">
                                    <select name="docente_id" required 
                                            class="w-full appearance-none bg-white border border-slate-200 text-slate-700 py-2.5 pl-4 pr-10 rounded-xl text-sm font-medium focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition cursor-pointer">
                                        <option value="">-- Seleccionar Docente ACTIVO disponible --</option>
                                        @foreach($docentesDisponibles as $d)
                                            <option value="{{ $d->id }}">{{ $d->nombre_completo }} (Especialidad: {{ $d->especialidad }})</option>
                                        @endforeach
                                    </select>
                                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-slate-500">
                                        <i class="ti ti-chevron-down text-xs"></i>
                                    </div>
                                </div>
                                <button type="submit" 
                                        class="bg-emerald-600 hover:bg-emerald-700 text-white font-semibold py-2.5 px-5 rounded-xl text-sm transition shadow-sm flex items-center justify-center gap-2 border-0 cursor-pointer">
                                    <i class="ti ti-plus"></i>
                                    Asignar Docente
                                </button>
                            </form>
                        </div>
                    @else
                        <div class="mt-6 bg-slate-50 border border-slate-100 rounded-xl p-3.5 flex items-center gap-2 text-xs text-slate-500">
                            <i class="ti ti-info-circle text-slate-400"></i>
                            No hay más docentes con estado <strong class="text-emerald-700">ACTIVO</strong> disponibles para asignar en este momento.
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</div>

<script>
    /**
     * filterPostulantes()
     *
     * Función de búsqueda reactiva (en tiempo real) para la tabla de postulantes asignados.
     * Se ejecuta cada vez que el usuario escribe en el campo de búsqueda (#searchPostulantes).
     *
     * Cómo funciona:
     *   1. Lee el texto ingresado en el input y lo convierte a minúsculas.
     *   2. Recorre cada fila <tr> de la tabla #postulantesTable.
     *   3. Compara el texto con los atributos data-name (nombre completo) y data-ci (cédula).
     *   4. Muestra la fila si coincide, la oculta si no.
     *   5. Si ninguna fila es visible, muestra el mensaje #noPostulantesMatch.
     *
     * No realiza peticiones al servidor; opera solo sobre el DOM ya cargado.
     */
    function filterPostulantes() {
        // Obtener el texto de búsqueda del input y normalizarlo a minúsculas
        const input = document.getElementById("searchPostulantes");
        const filter = input.value.toLowerCase();

        // Seleccionar todas las filas del tbody de la tabla de postulantes
        const rows = document.querySelectorAll("#postulantesTable tbody tr");
        let countVisible = 0; // Contador de filas visibles tras el filtro

        // Iterar sobre cada fila y decidir si mostrarla u ocultarla
        rows.forEach(row => {
            // Leer los datos de búsqueda desde los atributos data-* de cada fila
            const name = row.getAttribute("data-name").toLowerCase(); // Nombre del postulante
            const ci   = row.getAttribute("data-ci").toLowerCase();   // Cédula de identidad

            // Si el texto coincide con nombre o CI, mostrar la fila; si no, ocultarla
            if (name.includes(filter) || ci.includes(filter)) {
                row.style.display = "";  // Mostrar fila
                countVisible++;
            } else {
                row.style.display = "none"; // Ocultar fila
            }
        });

        // Mostrar mensaje de "sin resultados" si ninguna fila es visible
        const alertEl = document.getElementById("noPostulantesMatch");
        if (alertEl) {
            alertEl.style.display = (countVisible === 0 && rows.length > 0) ? "block" : "none";
        }
    }

    /**
     * filterDocentes()
     *
     * Función de búsqueda reactiva (en tiempo real) para la tabla de docentes asignados.
     * Se ejecuta cada vez que el usuario escribe en el campo de búsqueda (#searchDocentes).
     *
     * Cómo funciona:
     *   1. Lee el texto ingresado en el input y lo convierte a minúsculas.
     *   2. Recorre cada fila <tr> de la tabla #docentesTable.
     *   3. Compara el texto con los atributos data-name (nombre completo)
     *      y data-specialty (especialidad del docente).
     *   4. Muestra la fila si coincide, la oculta si no.
     *   5. Si ninguna fila es visible, muestra el mensaje #noDocentesMatch.
     *
     * No realiza peticiones al servidor; opera solo sobre el DOM ya cargado.
     */
    function filterDocentes() {
        // Obtener el texto de búsqueda del input y normalizarlo a minúsculas
        const input = document.getElementById("searchDocentes");
        const filter = input.value.toLowerCase();

        // Seleccionar todas las filas del tbody de la tabla de docentes
        const rows = document.querySelectorAll("#docentesTable tbody tr");
        let countVisible = 0; // Contador de filas visibles tras el filtro

        // Iterar sobre cada fila y decidir si mostrarla u ocultarla
        rows.forEach(row => {
            // Leer los datos de búsqueda desde los atributos data-* de cada fila
            const name      = row.getAttribute("data-name").toLowerCase();      // Nombre del docente
            const specialty = row.getAttribute("data-specialty").toLowerCase(); // Especialidad del docente

            // Si el texto coincide con nombre o especialidad, mostrar la fila; si no, ocultarla
            if (name.includes(filter) || specialty.includes(filter)) {
                row.style.display = "";  // Mostrar fila
                countVisible++;
            } else {
                row.style.display = "none"; // Ocultar fila
            }
        });

        // Mostrar mensaje de "sin resultados" si ninguna fila es visible
        const alertEl = document.getElementById("noDocentesMatch");
        if (alertEl) {
            alertEl.style.display = (countVisible === 0 && rows.length > 0) ? "block" : "none";
        }
    }
</script>
@endsection

