<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Avanta - Iniciar sesión</title>
    
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

            /* --- Colores de UI de Soporte --- */
            --panel-bg: #111B54;      
            --border-color: #2F3A75;  
            --text-muted: #98A9C4;    
            --error-color: #ff6b6b;   

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

        /* --- Contenedor de la Marca (Solo Isotipo a Gran Escala) --- */
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
            height: 84px; /* Sincronizado a gran escala para máxima presencia visual */
            width: auto;
            display: block;
        }

        /* --- Tarjeta de Login Compacta --- */
        .login-card {
            background-color: var(--panel-bg);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            padding: 28px 32px;
            width: 100%;
            max-width: 340px;
        }

        h1 {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 20px;
            text-align: center;
            color: var(--avanta-blanco);
        }

        .form-group {
            margin-bottom: 16px;
        }

        label {
            display: block;
            font-size: 0.85rem;
            margin-bottom: 6px;
            color: var(--avanta-celeste);
            font-weight: 500;
        }

        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 10px 12px;
            background-color: rgba(4, 17, 74, 0.7);
            border: 1px solid var(--border-color);
            border-radius: 8px;
            color: var(--avanta-blanco);
            font-size: 0.95rem;
            font-family: inherit;
            font-weight: 300;
            transition: all 0.3s ease;
        }

        input[type="email"]:focus,
        input[type="password"]:focus {
            outline: none;
            border-color: var(--avanta-celeste);
        }

        .remember-group {
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            font-size: 0.85rem;
            color: var(--text-muted);
            margin-bottom: 20px;
            user-select: none;
            font-weight: 300;
        }

        .remember-group input {
            accent-color: var(--avanta-azul-electrico);
            width: 14px;
            height: 14px;
            cursor: pointer;
        }

        .btn-submit {
            width: 100%;
            background-color: var(--avanta-azul-electrico);
            color: var(--avanta-blanco);
            border: none;
            padding: 12px;
            border-radius: 8px;
            font-weight: 500;
            font-size: 0.95rem;
            font-family: inherit;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .btn-submit:hover {
            background-color: var(--avanta-verde);
            color: var(--avanta-azul-profundo);
            font-weight: 700;
        }

        .error-message {
            color: var(--error-color);
            font-size: 0.80rem;
            margin-top: 4px;
            font-weight: 500;
        }

        .register-link {
            text-align: center;
            margin-top: 20px;
            font-size: 0.85rem;
            color: var(--text-muted);
            font-weight: 300;
        }

        .register-link a {
            color: var(--avanta-celeste);
            text-decoration: none;
            font-weight: 500;
            transition: color 0.2s ease;
        }

        .register-link a:hover {
            color: var(--avanta-blanco);
            text-decoration: underline;
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
        <a href="/">
            <img src="{{ asset('imagenes/logoAVANTA.png') }}" alt="Avanta Isotipo" class="brand-logo">
        </a>
    </div>

    <!-- Bloque de Acceso Compacto -->
    <div class="login-card">
        <h1>Iniciar sesión</h1>

        <form method="POST" action="/login">
            @csrf

            <div class="form-group">
                <label for="email">Correo electrónico</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus>
                @error('email') 
                    <p class="error-message">{{ $message }}</p> 
                @enderror
            </div>

            <div class="form-group">
                <label for="password">Contraseña</label>
                <input type="password" id="password" name="password" required>
            </div>

            <label class="remember-group">
                <input type="checkbox" name="remember"> 
                <span>Recordarme en este equipo</span>
            </label>

            <button type="submit" class="btn-submit">Entrar</button>
        </form>

        <p class="register-link">¿No tienes cuenta? <a href="{{ route('register') }}">Regístrate</a></p>
    </div>
</body>
</html>
