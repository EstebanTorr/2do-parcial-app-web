<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CUP — Pre-registro Estudiante</title>
    <link href="https://fonts.googleapis.com/css2?family=Figtree:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Figtree', sans-serif; background: #f1f5f9; color: #1e293b; min-height: 100vh; }

        /* ── HEADER ── */
        .header {
            background: #1e3a6e;
            padding: 0 32px;
            height: 56px;
            display: flex; align-items: center; justify-content: space-between;
            position: sticky; top: 0; z-index: 100;
        }
        .header-brand { color: #fff; font-size: 15px; font-weight: 600; display: flex; align-items: center; gap: 10px; text-decoration: none; }
        .header-back  { color: #a8c8f0; font-size: 13px; text-decoration: none; display: flex; align-items: center; gap: 6px; }
        .header-back:hover { color: #fff; }

        /* ── HERO ── */
        .hero {
            background: linear-gradient(135deg, #1e3a6e 0%, #0f2147 100%);
            padding: 40px 32px 32px;
            text-align: center;
            color: #fff;
        }
        .hero-badge {
            display: inline-flex; align-items: center; gap: 6px;
            background: rgba(255,255,255,0.12);
            border: 1px solid rgba(255,255,255,0.2);
            border-radius: 99px; padding: 4px 14px;
            font-size: 12px; color: #a8c8f0;
            margin-bottom: 16px;
        }
        .hero h1 { font-size: 26px; font-weight: 700; margin-bottom: 8px; }
        .hero p  { font-size: 14px; color: #a8c8f0; }

        @keyframes fadeIn { from { opacity: 0; transform: translateY(8px); } to { opacity: 1; transform: none; } }

        /* ── STEPPER ── */
        .stepper {
            display: flex; justify-content: center; gap: 0;
            background: #fff; border-bottom: 1px solid #e2e8f0;
            padding: 0 32px; overflow-x: auto;
        }
        .step {
            display: flex; align-items: center; gap: 8px;
            padding: 14px 20px; font-size: 12px; font-weight: 500;
            color: #94a3b8; cursor: pointer; border-bottom: 2px solid transparent;
            white-space: nowrap; transition: color .2s, border-color .2s;
        }
        .step.active { color: #1e3a6e; border-bottom-color: #1e3a6e; }
        .step.done   { color: #065f46; }
        .step-num {
            width: 22px; height: 22px; border-radius: 50%;
            background: #e2e8f0; color: #64748b;
            display: flex; align-items: center; justify-content: center;
            font-size: 11px; font-weight: 600; flex-shrink: 0;
        }
        .step.active .step-num { background: #1e3a6e; color: #fff; }
        .step.done   .step-num { background: #d1fae5; color: #065f46; }

        /* ── LAYOUT ── */
        .container { max-width: 860px; margin: 0 auto; padding: 32px 20px 60px; }

        /* ── SECCIÓN ── */
        .section { display: none; animation: fadeIn .25s ease; }
        .section.active { display: block; }

        .card {
            background: #fff; border-radius: 14px;
            border: 1px solid #e2e8f0;
            padding: 28px; margin-bottom: 16px;
        }
        .card-header {
            display: flex; align-items: center; gap: 10px;
            margin-bottom: 22px;
            padding-bottom: 14px;
            border-bottom: 1px solid #f1f5f9;
        }
        .card-icon {
            width: 36px; height: 36px; border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 18px; flex-shrink: 0;
        }
        .ci-blue   { background: #dbeafe; color: #1e40af; }
        .ci-teal   { background: #d1fae5; color: #065f46; }
        .ci-purple { background: #ede9fe; color: #5b21b6; }
        .ci-amber  { background: #fef3c7; color: #92400e; }
        .card-header h2 { font-size: 15px; font-weight: 600; color: #1e293b; }
        .card-header p  { font-size: 12px; color: #64748b; margin-top: 2px; }

        /* ── GRID ── */
        .grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
        .grid-3 { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 16px; }
        .col-span-2 { grid-column: span 2; }

        /* ── CAMPO ── */
        .field { display: flex; flex-direction: column; gap: 5px; }
        .field label { font-size: 12px; font-weight: 500; color: #374151; }
        .field label .req { color: #ef4444; margin-left: 2px; }

        input[type="text"], input[type="email"], input[type="date"],
        input[type="number"], input[type="tel"], select, textarea {
            width: 100%; padding: 9px 12px;
            border: 1px solid #d1d5db; border-radius: 9px;
            font-size: 13px; color: #111827;
            background: #fff; outline: none;
            font-family: 'Figtree', sans-serif;
            transition: border-color .2s, box-shadow .2s;
        }
        input:focus, select:focus, textarea:focus {
            border-color: #1e3a6e;
            box-shadow: 0 0 0 3px rgba(30,58,110,0.08);
        }
        input.is-error, select.is-error { border-color: #ef4444; }
        .field-hint { font-size: 11px; color: #9ca3af; }
        .field-error { font-size: 11px; color: #ef4444; display: flex; align-items: center; gap: 4px; }

        /* ── RADIO TURNO ── */
        .turno-grid { display: grid; grid-template-columns: repeat(3,1fr); gap: 10px; }
        .turno-opt { position: relative; }
        .turno-opt input[type="radio"] { position: absolute; opacity: 0; width: 0; height: 0; }
        .turno-label {
            display: flex; flex-direction: column; align-items: center;
            gap: 6px; padding: 14px 10px;
            border: 2px solid #e2e8f0; border-radius: 10px;
            cursor: pointer; font-size: 13px; color: #64748b;
            transition: border-color .2s, background .2s, color .2s;
            text-align: center;
        }
        .turno-label i { font-size: 22px; }
        .turno-opt input:checked + .turno-label {
            border-color: #1e3a6e; background: #eff4ff; color: #1e3a6e; font-weight: 500;
        }

        /* ── UPLOAD ── */
        .upload-box {
            border: 2px dashed #d1d5db; border-radius: 10px;
            padding: 20px; text-align: center; cursor: pointer;
            transition: border-color .2s, background .2s;
            position: relative;
        }
        .upload-box:hover { border-color: #1e3a6e; background: #f8faff; }
        .upload-box input[type="file"] {
            position: absolute; inset: 0; opacity: 0; cursor: pointer; width: 100%; height: 100%;
        }
        .upload-box i { font-size: 28px; color: #94a3b8; display: block; margin-bottom: 8px; }
        .upload-box .up-title { font-size: 13px; font-weight: 500; color: #374151; }
        .upload-box .up-hint  { font-size: 11px; color: #9ca3af; margin-top: 4px; }
        .upload-box.has-file  { border-color: #6ee7b7; background: #f0fdf4; }
        .upload-box.has-file i { color: #059669; }
        .upload-req { font-size: 11px; color: #ef4444; margin-top: 6px; }

        /* ── ERRORES GLOBALES ── */
        .error-banner {
            background: #fef2f2; border: 1px solid #fecaca;
            border-radius: 10px; padding: 14px 18px;
            margin-bottom: 20px; font-size: 13px; color: #b91c1c;
            display: flex; gap: 10px; align-items: flex-start;
        }
        .error-banner i { font-size: 18px; flex-shrink: 0; margin-top: 1px; }
        .error-banner ul { list-style: none; }
        .error-banner ul li::before { content: '· '; }

        /* ── NAVEGACIÓN ── */
        .nav-btns {
            display: flex; justify-content: space-between; align-items: center;
            margin-top: 24px;
        }
        .btn {
            padding: 10px 24px; border-radius: 10px;
            font-size: 13px; font-weight: 600;
            cursor: pointer; border: none;
            font-family: 'Figtree', sans-serif;
            display: inline-flex; align-items: center; gap: 6px;
            transition: background .2s, transform .1s;
        }
        .btn:active { transform: scale(.98); }
        .btn-primary { background: #1e3a6e; color: #fff; }
        .btn-primary:hover { background: #162d58; }
        .btn-secondary { background: #f1f5f9; color: #374151; border: 1px solid #e2e8f0; }
        .btn-secondary:hover { background: #e2e8f0; }
        .btn-success { background: #059669; color: #fff; }
        .btn-success:hover { background: #047857; }

        /* ── CONVOCATORIA BADGE ── */
        .conv-banner {
            background: #eff4ff; border: 1px solid #bfdbfe;
            border-radius: 10px; padding: 12px 16px;
            display: flex; align-items: center; gap: 10px;
            margin-bottom: 24px; font-size: 13px; color: #1e40af;
        }
        .conv-banner i { font-size: 18px; flex-shrink: 0; }
        .no-conv {
            background: #fef9c3; border: 1px solid #fde68a;
            border-radius: 10px; padding: 16px 20px;
            text-align: center; font-size: 13px; color: #92400e;
            margin-bottom: 24px;
        }
    </style>
</head>
<body>

{{-- HEADER --}}
<header class="header">
    <a href="{{ route('login') }}" class="header-brand">
        <i class="ti ti-school" style="font-size:20px"></i> CUP — FICCT
    </a>
    <a href="{{ route('login') }}" class="header-back">
        <i class="ti ti-arrow-left"></i> Volver al inicio
    </a>
</header>

{{-- HERO --}}
<div class="hero">
    <div class="hero-badge"><i class="ti ti-clipboard-list"></i> Pre-registro de postulante</div>
    <h1>Formulario de Pre-registro</h1>
    <p>Completa los datos y sube tus documentos para iniciar el proceso de admisión</p>
</div>

{{-- STEPPER --}}
<div class="stepper">
    <div class="step active" id="tab-1"><span class="step-num">1</span> Datos personales</div>
    <div class="step"        id="tab-2"><span class="step-num">2</span> Datos académicos</div>
    <div class="step"        id="tab-3"><span class="step-num">3</span> Turno y carrera</div>
    <div class="step"        id="tab-4"><span class="step-num">4</span> Documentos</div>
</div>

<div class="container">

    {{-- CONVOCATORIA --}}
    @if($convocatoria)
        <div class="conv-banner">
            <i class="ti ti-calendar-event"></i>
            <div>
                <strong>Convocatoria activa:</strong> {{ $convocatoria->nombre }} —
                Fecha límite: <strong>{{ \Carbon\Carbon::parse($convocatoria->fecha_fin)->format('d/m/Y') }}</strong>
                &nbsp;·&nbsp; Monto de inscripción: <strong>Bs. {{ number_format($convocatoria->monto_pago, 2) }}</strong>
            </div>
        </div>
    @else
        <div class="no-conv">
            <i class="ti ti-alert-circle" style="font-size:20px;display:block;margin-bottom:6px"></i>
            <strong>No hay convocatoria activa en este momento.</strong><br>
            El pre-registro estará habilitado cuando la administración abra una nueva convocatoria.
        </div>
    @endif

    {{-- ERRORES --}}
    @if ($errors->any())
        <div class="error-banner">
            <i class="ti ti-alert-circle"></i>
            <div>
                <strong>Por favor corrige los siguientes errores:</strong>
                <ul>
                    @foreach ($errors->all() as $e)
                        <li>{{ $e }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    {{-- FORMULARIO --}}
    <form method="POST" action="{{ route('pre-registro.estudiante.store') }}" enctype="multipart/form-data" id="form-prereg">
        @csrf

        {{-- ══ PASO 1: DATOS PERSONALES ══ --}}
        <div class="section active" id="sec-1">
            <div class="card">
                <div class="card-header">
                    <div class="card-icon ci-blue"><i class="ti ti-user"></i></div>
                    <div><h2>Datos personales</h2><p>Información básica del postulante</p></div>
                </div>
                <div class="grid-2">
                    <div class="field">
                        <label>Nombres <span class="req">*</span></label>
                        <input type="text" name="nombre" value="{{ old('nombre') }}"
                               placeholder="Ej: Juan Carlos"
                               class="{{ $errors->has('nombre') ? 'is-error' : '' }}">
                        @error('nombre')<div class="field-error"><i class="ti ti-alert-circle"></i>{{ $message }}</div>@enderror
                    </div>
                    <div class="field">
                        <label>Apellidos <span class="req">*</span></label>
                        <input type="text" name="apellido" value="{{ old('apellido') }}"
                               placeholder="Ej: García Pérez"
                               class="{{ $errors->has('apellido') ? 'is-error' : '' }}">
                        @error('apellido')<div class="field-error"><i class="ti ti-alert-circle"></i>{{ $message }}</div>@enderror
                    </div>
                    <div class="field">
                        <label>Carnet de identidad (CI) <span class="req">*</span></label>
                        <input type="text" name="ci" value="{{ old('ci') }}"
                               placeholder="Ej: 12345678"
                               class="{{ $errors->has('ci') ? 'is-error' : '' }}">
                        @error('ci')<div class="field-error"><i class="ti ti-alert-circle"></i>{{ $message }}</div>@enderror
                    </div>
                    <div class="field">
                        <label>Extensión CI <span class="req">*</span></label>
                        <select name="ci_extension" class="{{ $errors->has('ci_extension') ? 'is-error' : '' }}">
                            <option value="">Seleccionar...</option>
                            @foreach(['SC'=>'Santa Cruz','LP'=>'La Paz','CB'=>'Cochabamba','OR'=>'Oruro','PT'=>'Potosí','TJ'=>'Tarija','CH'=>'Chuquisaca','BN'=>'Beni','PD'=>'Pando'] as $k=>$v)
                                <option value="{{ $k }}" {{ old('ci_extension')==$k ? 'selected' : '' }}>{{ $k }} — {{ $v }}</option>
                            @endforeach
                        </select>
                        @error('ci_extension')<div class="field-error"><i class="ti ti-alert-circle"></i>{{ $message }}</div>@enderror
                    </div>
                    <div class="field">
                        <label>Fecha de nacimiento <span class="req">*</span></label>
                        <input type="date" name="fecha_nacimiento" value="{{ old('fecha_nacimiento') }}"
                               class="{{ $errors->has('fecha_nacimiento') ? 'is-error' : '' }}">
                        @error('fecha_nacimiento')<div class="field-error"><i class="ti ti-alert-circle"></i>{{ $message }}</div>@enderror
                    </div>
                    <div class="field">
                        <label>Sexo <span class="req">*</span></label>
                        <select name="sexo" class="{{ $errors->has('sexo') ? 'is-error' : '' }}">
                            <option value="">Seleccionar...</option>
                            <option value="M"    {{ old('sexo')=='M'    ? 'selected':'' }}>Masculino</option>
                            <option value="F"    {{ old('sexo')=='F'    ? 'selected':'' }}>Femenino</option>
                            <option value="OTRO" {{ old('sexo')=='OTRO' ? 'selected':'' }}>Otro</option>
                        </select>
                        @error('sexo')<div class="field-error"><i class="ti ti-alert-circle"></i>{{ $message }}</div>@enderror
                    </div>
                    <div class="field">
                        <label>Teléfono / Celular</label>
                        <input type="tel" name="telefono" value="{{ old('telefono') }}" placeholder="Ej: 70000000">
                    </div>
                    <div class="field">
                        <label>Correo electrónico <span class="req">*</span></label>
                        <input type="email" name="email" value="{{ old('email') }}"
                               placeholder="Ej: juan@gmail.com"
                               class="{{ $errors->has('email') ? 'is-error' : '' }}">
                        @error('email')<div class="field-error"><i class="ti ti-alert-circle"></i>{{ $message }}</div>@enderror
                    </div>
                    <div class="field">
                        <label>Ciudad <span class="req">*</span></label>
                        <input type="text" name="ciudad" value="{{ old('ciudad') }}"
                               placeholder="Ej: Santa Cruz de la Sierra"
                               class="{{ $errors->has('ciudad') ? 'is-error' : '' }}">
                        @error('ciudad')<div class="field-error"><i class="ti ti-alert-circle"></i>{{ $message }}</div>@enderror
                    </div>
                    <div class="field col-span-2">
                        <label>Dirección</label>
                        <input type="text" name="direccion" value="{{ old('direccion') }}"
                               placeholder="Ej: Av. Cañoto #123, Barrio Centro">
                    </div>
                </div>
            </div>
            <div class="nav-btns">
                <span></span>
                <button type="button" class="btn btn-primary" onclick="goTo(2)">
                    Siguiente <i class="ti ti-arrow-right"></i>
                </button>
            </div>
        </div>

        {{-- ══ PASO 2: DATOS ACADÉMICOS ══ --}}
        <div class="section" id="sec-2">
            <div class="card">
                <div class="card-header">
                    <div class="card-icon ci-teal"><i class="ti ti-school"></i></div>
                    <div><h2>Datos académicos</h2><p>Información del colegio de procedencia</p></div>
                </div>
                <div class="grid-2">
                    <div class="field col-span-2">
                        <label>Nombre del colegio <span class="req">*</span></label>
                        <input type="text" name="colegio_nombre" value="{{ old('colegio_nombre') }}"
                               placeholder="Ej: Unidad Educativa Juan Pablo II"
                               class="{{ $errors->has('colegio_nombre') ? 'is-error' : '' }}">
                        @error('colegio_nombre')<div class="field-error"><i class="ti ti-alert-circle"></i>{{ $message }}</div>@enderror
                    </div>
                    <div class="field">
                        <label>Tipo de colegio <span class="req">*</span></label>
                        <select name="colegio_tipo" class="{{ $errors->has('colegio_tipo') ? 'is-error' : '' }}">
                            <option value="">Seleccionar...</option>
                            <option value="FISCAL"      {{ old('colegio_tipo')=='FISCAL'      ? 'selected':'' }}>Fiscal</option>
                            <option value="PARTICULAR"  {{ old('colegio_tipo')=='PARTICULAR'  ? 'selected':'' }}>Particular</option>
                            <option value="CONVENIO"    {{ old('colegio_tipo')=='CONVENIO'    ? 'selected':'' }}>Convenio</option>
                        </select>
                        @error('colegio_tipo')<div class="field-error"><i class="ti ti-alert-circle"></i>{{ $message }}</div>@enderror
                    </div>
                    <div class="field">
                        <label>Año de egreso <span class="req">*</span></label>
                        <input type="number" name="anio_egreso" value="{{ old('anio_egreso') }}"
                               min="2000" max="{{ date('Y') }}" placeholder="{{ date('Y') }}"
                               class="{{ $errors->has('anio_egreso') ? 'is-error' : '' }}">
                        @error('anio_egreso')<div class="field-error"><i class="ti ti-alert-circle"></i>{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>
            <div class="nav-btns">
                <button type="button" class="btn btn-secondary" onclick="goTo(1)">
                    <i class="ti ti-arrow-left"></i> Anterior
                </button>
                <button type="button" class="btn btn-primary" onclick="goTo(3)">
                    Siguiente <i class="ti ti-arrow-right"></i>
                </button>
            </div>
        </div>

        {{-- ══ PASO 3: TURNO Y CARRERA ══ --}}
        <div class="section" id="sec-3">
            <div class="card">
                <div class="card-header">
                    <div class="card-icon ci-purple"><i class="ti ti-layout-grid"></i></div>
                    <div><h2>Preferencias académicas</h2><p>Elige tu turno y las carreras a las que postulas</p></div>
                </div>

                <div class="field" style="margin-bottom:22px">
                    <label>Turno preferido <span class="req">*</span></label>
                    <div class="turno-grid" style="margin-top:8px">
                        <label class="turno-opt">
                            <input type="radio" name="turno_preferido" value="MAÑANA"
                                   {{ old('turno_preferido')=='MAÑANA' ? 'checked' : '' }}>
                            <span class="turno-label"><i class="ti ti-sun"></i>Mañana<small style="color:#9ca3af;font-size:10px">06:00 – 12:00</small></span>
                        </label>
                        <label class="turno-opt">
                            <input type="radio" name="turno_preferido" value="TARDE"
                                   {{ old('turno_preferido')=='TARDE' ? 'checked' : '' }}>
                            <span class="turno-label"><i class="ti ti-cloud"></i>Tarde<small style="color:#9ca3af;font-size:10px">12:00 – 18:00</small></span>
                        </label>
                        <label class="turno-opt">
                            <input type="radio" name="turno_preferido" value="NOCHE"
                                   {{ old('turno_preferido')=='NOCHE' ? 'checked' : '' }}>
                            <span class="turno-label"><i class="ti ti-moon"></i>Noche<small style="color:#9ca3af;font-size:10px">18:00 – 22:00</small></span>
                        </label>
                    </div>
                    @error('turno_preferido')<div class="field-error" style="margin-top:6px"><i class="ti ti-alert-circle"></i>{{ $message }}</div>@enderror
                </div>

                <div class="grid-2">
                    <div class="field">
                        <label>1ª preferencia de carrera <span class="req">*</span></label>
                        <select name="carrera_pref_1_id" id="carrera1"
                                class="{{ $errors->has('carrera_pref_1_id') ? 'is-error' : '' }}"
                                onchange="filtrarCarrera2()">
                            <option value="">Seleccionar carrera...</option>
                            @foreach($carreras as $c)
                                <option value="{{ $c->id }}" {{ old('carrera_pref_1_id')==$c->id ? 'selected':'' }}>
                                    {{ $c->nombre }} ({{ $c->codigo }})
                                </option>
                            @endforeach
                        </select>
                        @error('carrera_pref_1_id')<div class="field-error"><i class="ti ti-alert-circle"></i>{{ $message }}</div>@enderror
                    </div>
                    <div class="field">
                        <label>2ª preferencia de carrera <span class="req">*</span></label>
                        <select name="carrera_pref_2_id" id="carrera2"
                                class="{{ $errors->has('carrera_pref_2_id') ? 'is-error' : '' }}">
                            <option value="">Seleccionar carrera...</option>
                            @foreach($carreras as $c)
                                <option value="{{ $c->id }}" {{ old('carrera_pref_2_id')==$c->id ? 'selected':'' }}>
                                    {{ $c->nombre }} ({{ $c->codigo }})
                                </option>
                            @endforeach
                        </select>
                        @error('carrera_pref_2_id')<div class="field-error"><i class="ti ti-alert-circle"></i>{{ $message }}</div>@enderror
                        <p class="field-hint">Debe ser diferente a la primera opción</p>
                    </div>
                </div>
            </div>
            <div class="nav-btns">
                <button type="button" class="btn btn-secondary" onclick="goTo(2)">
                    <i class="ti ti-arrow-left"></i> Anterior
                </button>
                <button type="button" class="btn btn-primary" onclick="goTo(4)">
                    Siguiente <i class="ti ti-arrow-right"></i>
                </button>
            </div>
        </div>

        {{-- ══ PASO 4: DOCUMENTOS ══ --}}
        <div class="section" id="sec-4">
            <div class="card">
                <div class="card-header">
                    <div class="card-icon ci-amber"><i class="ti ti-files"></i></div>
                    <div><h2>Documentos requeridos</h2><p>Sube los archivos en formato PDF, JPG o PNG (máx. 3 MB c/u)</p></div>
                </div>
                <div class="grid-2">
                    {{-- CI --}}
                    <div class="field">
                        <label>Carnet de identidad (ambos lados) <span class="req">*</span></label>
                        <div class="upload-box {{ $errors->has('doc_ci') ? 'is-error' : '' }}" id="box-ci">
                            <input type="file" name="doc_ci" accept=".pdf,.jpg,.jpeg,.png"
                                   onchange="previewUpload(this, 'box-ci', 'lbl-ci')">
                            <i class="ti ti-id-badge-2" id="ico-ci"></i>
                            <div class="up-title" id="lbl-ci">Seleccionar archivo</div>
                            <div class="up-hint">PDF, JPG o PNG · máx. 3 MB</div>
                        </div>
                        @error('doc_ci')<div class="field-error" style="margin-top:6px"><i class="ti ti-alert-circle"></i>{{ $message }}</div>@enderror
                    </div>

                    {{-- TÍTULO --}}
                    <div class="field">
                        <label>Título de bachiller <span class="req">*</span></label>
                        <div class="upload-box {{ $errors->has('doc_titulo') ? 'is-error' : '' }}" id="box-titulo">
                            <input type="file" name="doc_titulo" accept=".pdf,.jpg,.jpeg,.png"
                                   onchange="previewUpload(this, 'box-titulo', 'lbl-titulo')">
                            <i class="ti ti-certificate" id="ico-titulo"></i>
                            <div class="up-title" id="lbl-titulo">Seleccionar archivo</div>
                            <div class="up-hint">PDF, JPG o PNG · máx. 3 MB</div>
                        </div>
                        @error('doc_titulo')<div class="field-error" style="margin-top:6px"><i class="ti ti-alert-circle"></i>{{ $message }}</div>@enderror
                    </div>

                    {{-- LIBRETA / CERTIFICADO --}}
                    <div class="field">
                        <label>Libreta o Certificado de último año de secundaria <span style="color:#9ca3af;font-size:11px">(opcional)</span></label>
                        <div class="upload-box" id="box-boleta">
                            <input type="file" name="doc_boleta" accept=".pdf,.jpg,.jpeg,.png"
                                   onchange="previewUpload(this, 'box-boleta', 'lbl-boleta')">
                            <i class="ti ti-notebook" id="ico-boleta"></i>
                            <div class="up-title" id="lbl-boleta">Seleccionar archivo</div>
                            <div class="up-hint">PDF, JPG o PNG · máx. 3 MB</div>
                        </div>
                    </div>

                    {{-- COMPROBANTE DE PAGO --}}
                    <div class="field">
                        <label>Comprobante de pago <span class="req">*</span></label>
                        <div class="upload-box {{ $errors->has('doc_pago') ? 'is-error' : '' }}" id="box-pago">
                            <input type="file" name="doc_pago" accept=".pdf,.jpg,.jpeg,.png"
                                   onchange="previewUpload(this, 'box-pago', 'lbl-pago')">
                            <i class="ti ti-receipt" id="ico-pago"></i>
                            <div class="up-title" id="lbl-pago">Seleccionar archivo</div>
                            <div class="up-hint">PDF, JPG o PNG · máx. 3 MB</div>
                        </div>
                        @error('doc_pago')<div class="field-error" style="margin-top:6px"><i class="ti ti-alert-circle"></i>{{ $message }}</div>@enderror
                    </div>
                </div>

                {{-- RESUMEN --}}
                <div style="background:#f8fafc;border-radius:10px;padding:14px 16px;margin-top:20px;font-size:12px;color:#64748b;border:1px solid #e2e8f0">
                    <i class="ti ti-info-circle" style="vertical-align:-2px"></i>
                    Al enviar este formulario, tu solicitud quedará en estado <strong>PENDIENTE</strong> hasta que el administrador la revise y apruebe. Recibirás tus credenciales de acceso por correo electrónico.
                </div>
            </div>
            <div class="nav-btns">
                <button type="button" class="btn btn-secondary" onclick="goTo(3)">
                    <i class="ti ti-arrow-left"></i> Anterior
                </button>
                <button type="submit" class="btn btn-success" {{ !$convocatoria ? 'disabled' : '' }}>
                    <i class="ti ti-send"></i> Enviar pre-registro
                </button>
            </div>
        </div>

    </form>
</div>

<script>
    let currentStep = {{ $errors->any() ? 1 : 1 }};

    // Si hay errores, ir al paso con error
    @if($errors->has('nombre') || $errors->has('apellido') || $errors->has('ci') || $errors->has('email') || $errors->has('sexo') || $errors->has('ciudad'))
        currentStep = 1;
    @elseif($errors->has('colegio_nombre') || $errors->has('colegio_tipo') || $errors->has('anio_egreso'))
        currentStep = 2;
    @elseif($errors->has('turno_preferido') || $errors->has('carrera_pref_1_id') || $errors->has('carrera_pref_2_id'))
        currentStep = 3;
    @elseif($errors->has('doc_ci') || $errors->has('doc_titulo'))
        currentStep = 4;
    @endif

    function goTo(n) {
        document.querySelectorAll('.section').forEach((s,i) => {
            s.classList.toggle('active', i === n-1);
        });
        document.querySelectorAll('.step').forEach((t,i) => {
            t.classList.remove('active','done');
            if (i === n-1) t.classList.add('active');
            if (i < n-1)  t.classList.add('done');
        });
        currentStep = n;
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    // Si hay errores, mostrar el paso correcto
    if (currentStep > 1) goTo(currentStep);

    function previewUpload(input, boxId, lblId) {
        const box = document.getElementById(boxId);
        const lbl = document.getElementById(lblId);
        if (input.files && input.files[0]) {
            const name = input.files[0].name;
            const size = (input.files[0].size / 1024 / 1024).toFixed(2);
            lbl.textContent = name + ' (' + size + ' MB)';
            box.classList.add('has-file');
        }
    }

    function filtrarCarrera2() {
        const v1 = document.getElementById('carrera1').value;
        const sel2 = document.getElementById('carrera2');
        Array.from(sel2.options).forEach(opt => {
            opt.disabled = (opt.value && opt.value === v1);
        });
        if (sel2.value === v1) sel2.value = '';
    }
</script>
</body>
</html>