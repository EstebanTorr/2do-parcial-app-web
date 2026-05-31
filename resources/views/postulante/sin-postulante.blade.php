<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CUP — Perfil no encontrado</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Figtree:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Figtree', sans-serif; background: #f8fafc; color: #1e293b; min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 20px; }
        
        .card {
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            max-width: 500px;
            width: 100%;
            padding: 40px;
            text-align: center;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
        }
        
        .icon-box {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: #fffbeb;
            color: #d97706;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 40px;
            margin: 0 auto 24px;
            border: 2px dashed #fcd34d;
        }
        
        h1 { font-size: 22px; font-weight: 700; color: #0f172a; margin-bottom: 12px; }
        p { font-size: 14px; color: #64748b; line-height: 1.6; margin-bottom: 30px; }
        
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: #1e3a6e;
            color: #fff;
            padding: 12px 24px;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
            transition: background 0.2s;
        }
        .btn:hover { background: #162d58; }
    </style>
</head>
<body>

    <div class="card">
        <div class="icon-box">
            <i class="ti ti-user-exclamation"></i>
        </div>
        <h1>Perfil de postulante no encontrado</h1>
        <p>Tu cuenta de usuario está registrada, pero aún no tiene un perfil de postulante asociado en el sistema. Por favor, ponte en contacto con el administrador de la facultad para solucionar este problema.</p>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn" style="border: none; cursor: pointer; font-family: inherit;">
                <i class="ti ti-logout"></i>
                Cerrar Sesión
            </button>
        </form>
    </div>

</body>
</html>
