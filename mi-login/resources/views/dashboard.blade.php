<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <style>
        :root {
            --bg-main: #111522;       /* Fondo azul muy oscuro */
            --panel-bg: #1E263B;      /* Tarjeta principal */
            --border-color: #303D59;  /* Bordes sutiles */
            --text-muted: #536684;    /* Gris para texto secundario */
            --accent-blue: #98A9C4;   /* Color destacado / Hover */
            --text-light: #DCE3EF;    /* Color de texto principal */
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--bg-main);
            color: var(--text-light);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }

        .dashboard-card {
            background-color: var(--panel-bg);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            padding: 40px;
            width: 100%;
            max-width: 450px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.5);
            text-align: center;
        }

        h1 {
            font-size: 1.8rem;
            margin-bottom: 8px;
            color: var(--text-light);
        }

        .welcome-text {
            color: var(--accent-blue);
            font-size: 1rem;
            margin-bottom: 30px;
        }

        .btn-action {
            display: inline-block;
            width: 100%;
            background-color: var(--accent-blue);
            color: var(--bg-main);
            text-decoration: none;
            padding: 14px;
            border-radius: 8px;
            font-weight: 600;
            margin-bottom: 15px;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        .btn-action:hover {
            background-color: var(--text-light);
            transform: translateY(-2px);
        }

        .logout-form {
            margin-top: 10px;
        }

        .btn-logout {
            background: none;
            border: 1px solid var(--border-color);
            color: var(--text-muted);
            width: 100%;
            padding: 12px;
            border-radius: 8px;
            font-weight: 500;
            cursor: pointer;
            width: 100%;
            transition: all 0.3s ease;
        }

        .btn-logout:hover {
            border-color: var(--accent-blue);
            color: var(--text-light);
            background-color: rgba(152, 169, 196, 0.05);
        }
    </style>
</head>
<body>
    <div class="dashboard-card">
        <h1>Hola, {{ auth()->user()->name }} 👋</h1>
        <p class="welcome-text">Ya estás dentro del sistema.</p>

        <p><a href="{{ route('asistencias') }}" class="btn-action">Crear caso de asistencia</a></p>

        <form method="POST" action="/logout" class="logout-form">
            @csrf
            <button type="submit" class="btn-logout">Cerrar sesión</button>
        </form>
    </div>
</body>
</html>
