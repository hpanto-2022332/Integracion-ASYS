<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Avanta - Panel Principal</title>
    
    <!-- Configuración del Favicon corporativo para reemplazar el globito del navegador -->
    <link rel="icon" type="image/png" href="{{ asset('imagenes/logo_AVANTA.png') }}">
    
    <style>
        :root {
            /* --- Colores Corporativos Oficiales (Pág. 15-16) --- */
            --avanta-azul-profundo: #04114A;
            --avanta-azul-electrico: #0F63FF;
            --avanta-celeste: #2FE7FF;
            --avanta-verde: #35FF57;
            --avanta-blanco: #FFFFFF;

            /* --- Colores de UI de Soporte (Mantenidos para legibilidad) --- */
            --panel-bg: #111B54;      
            --border-color: #2F3A75;  
            --text-muted: #98A9C4;    

            /* --- Tipografía (Pág. 18) --- */
            --font-avanta: 'Helvetica Neue', Helvetica, Arial, sans-serif;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: var(--font-avanta);
            background-color: var(--avanta-azul-profundo);
            color: var(--avanta-blanco);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
            position: relative;
        }

        /* --- Contenedor de la Marca (Solo Isotipo a Gran Escala - Pág. 12) --- */
        .brand-container {
            position: absolute;
            top: 40px;
            left: 40px;
            display: flex;
            align-items: center;
        }

        /* Envoltura del enlace para aplicar la animación interactiva del logo */
        .brand-container a {
            display: inline-block;
            transition: transform 0.2s ease-in-out;
            will-change: transform;
            line-height: 0;
        }

        /* Efecto hover: se agranda un toque sutil (aprox 2px) */
        .brand-container a:hover {
            transform: scale(1.025);
        }

        .brand-logo {
            height: 84px; /* Sincronizado exactamente con el login y registro */
            width: auto;
            display: block;
        }

        /* --- Tarjeta Principal Compacta --- */
        .dashboard-card {
            background-color: var(--panel-bg);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            padding: 28px 32px; /* Reducido e idéntico a las vistas anteriores */
            width: 100%;
            max-width: 340px;   /* Sincronizado con el tamaño de las otras tarjetas */
            text-align: center;
        }

        h1 {
            /* Jerarquía para Títulos (Pág. 18): Helvetica Neue Bold */
            font-size: 1.5rem; /* Ajustado proporcionalmente al nuevo tamaño de tarjeta */
            font-weight: 700;
            margin-bottom: 8px;
            color: var(--avanta-blanco);
        }

        .welcome-text {
            /* Jerarquía para Textos Continuos (Pág. 18): Helvetica Neue Light */
            font-weight: 300;
            color: var(--avanta-celeste);
            font-size: 0.95rem;
            margin-bottom: 24px;
        }

        .btn-action {
            /* Jerarquía para Subtítulos/Acciones (Pág. 18): Helvetica Neue Medium */
            display: inline-block;
            width: 100%;
            background-color: var(--avanta-azul-electrico);
            color: var(--avanta-blanco);
            text-decoration: none;
            padding: 12px;
            border-radius: 8px;
            font-weight: 500;
            margin-bottom: 12px;
            font-size: 0.95rem;
            transition: background-color 0.3s ease;
        }

        .btn-action:hover {
            /* Transición fluida hacia el tono Verde de crecimiento (Pág. 9, 15) */
            background-color: var(--avanta-verde);
            color: var(--avanta-azul-profundo);
            font-weight: 700;
        }

        .logout-form {
            margin-top: 4px;
        }

        .btn-logout {
            font-family: var(--font-avanta);
            background: none;
            border: 1px solid var(--border-color);
            color: var(--text-muted);
            width: 100%;
            padding: 11px;
            border-radius: 8px;
            font-weight: 300;
            font-size: 0.95rem;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-logout:hover {
            border-color: var(--avanta-celeste);
            color: var(--avanta-blanco);
            background-color: rgba(47, 231, 255, 0.05);
        }

        /* Ajuste responsivo optimizado para el isotipo aislado */
        @media (max-width: 768px) {
            body {
                justify-content: flex-start;
                padding-top: 180px;
            }
            .brand-container {
                position: absolute;
                top: 35px;
                left: 50%;
                transform: translateX(-50%);
            }
        }
    </style>
</head>
<body>

    <!-- Identidad de Marca: El Isotipo limpio actúa como hipervínculo de retorno -->
    <div class="brand-container">
        <a href="{{ route('dashboard') }}">
            <img src="{{ asset('imagenes/logoAVANTA.png') }}" alt="Avanta Isotipo" class="brand-logo">
        </a>
    </div>

    <!-- Contenido del Dashboard Compacto -->
    <div class="dashboard-card">
        <h1>Hola, {{ auth()->user()->name }} 👋</h1>
        <p class="welcome-text">Ya estás dentro de Avanta.</p>

        <p><a href="{{ route('asistencias') }}" class="btn-action">Crear caso de asistencia</a></p>

        <form method="POST" action="/logout" class="logout-form">
            @csrf
            <button type="submit" class="btn-logout">Cerrar sesión</button>
        </form>
    </div>

</body>
</html>
