<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CUP — Pre-registro enviado</title>
    <link href="https://fonts.googleapis.com/css2?family=Figtree:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Figtree', sans-serif; background: #f1f5f9; min-height: 100vh; display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 24px; }
        .card { background: #fff; border-radius: 16px; border: 1px solid #e2e8f0; padding: 48px 40px; max-width: 480px; width: 100%; text-align: center; }
        .icon-wrap { width: 72px; height: 72px; border-radius: 50%; background: #d1fae5; display: flex; align-items: center; justify-content: center; margin: 0 auto 24px; }
        .icon-wrap i { font-size: 36px; color: #059669; }
        h1 { font-size: 22px; font-weight: 700; color: #1e293b; margin-bottom: 10px; }
        p  { font-size: 14px; color: #64748b; line-height: 1.6; }
        .ci-badge { background: #f1f5f9; border-radius: 10px; padding: 12px 20px; margin: 20px 0; font-size: 13px; color: #374151; }
        .ci-badge strong { font-size: 18px; color: #1e3a6e; display: block; margin-top: 4px; }
        .btn { display: inline-flex; align-items: center; gap: 6px; padding: 10px 24px; border-radius: 10px; font-size: 13px; font-weight: 600; background: #1e3a6e; color: #fff; text-decoration: none; margin-top: 24px; }
        .btn:hover { background: #162d58; }
    </style>
</head>
<body>
    <div class="card">
        <div class="icon-wrap"><i class="ti ti-circle-check"></i></div>
        <h1>¡Pre-registro enviado!</h1>
        <p>Tu solicitud fue recibida correctamente, <strong>{{ $nombre }}</strong>. El administrador revisará tu documentación y te notificará por correo electrónico.</p>
        <div class="ci-badge">
            Tu CI registrado<strong>{{ $ci }}</strong>
        </div>
        <p style="font-size:12px;color:#9ca3af">El estado de tu pre-registro es <strong style="color:#92400e">PENDIENTE</strong> hasta que sea aprobado.</p>
        <a href="{{ route('login') }}" class="btn"><i class="ti ti-home"></i> Volver al inicio</a>
    </div>
</body>
</html>