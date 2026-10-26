<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        :root {
            --bg-main: #111522;
            --panel-bg: #1E263B;
            --border-color: #303D59;
            --text-muted: #536684;
            --accent-blue: #98A9C4;
            --text-light: #DCE3EF;
            --error-color: #ff6b6b;
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

        .login-card {
            background-color: var(--panel-bg);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            padding: 40px;
            width: 100%;
            max-width: 400px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.5);
        }

        h1 {
            font-size: 1.8rem;
            margin-bottom: 25px;
            text-align: center;
            color: var(--text-light);
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            font-size: 0.9rem;
            margin-bottom: 8px;
            color: var(--accent-blue);
            font-weight: 500;
        }

        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 12px 14px;
            background-color: rgba(17, 21, 34, 0.6);
            border: 1px solid var(--border-color);
            border-radius: 8px;
            color: var(--text-light);
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        input[type="email"]:focus,
        input[type="password"]:focus {
            outline: none;
            border-color: var(--accent-blue);
            box-shadow: 0 0 0 3px rgba(152, 169, 196, 0.15);
        }

        .remember-group {
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            font-size: 0.9rem;
            color: var(--text-muted);
            margin-bottom: 25px;
            user-select: none;
        }

        .remember-group input {
            accent-color: var(--accent-blue);
            width: 16px;
            height: 16px;
            cursor: pointer;
        }

        .btn-submit {
            width: 100%;
            background-color: var(--accent-blue);
            color: var(--bg-main);
            border: none;
            padding: 14px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 1rem;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        .btn-submit:hover {
            background-color: var(--text-light);
            transform: translateY(-1px);
        }

        .error-message {
            color: var(--error-color);
            font-size: 0.85rem;
            margin-top: 6px;
            font-weight: 500;
        }

        .register-link {
            text-align: center;
            margin-top: 25px;
            font-size: 0.9rem;
            color: var(--text-muted);
        }

        .register-link a {
            color: var(--accent-blue);
            text-decoration: none;
            font-weight: 600;
            transition: color 0.2s ease;
        }

        .register-link a:hover {
            color: var(--text-light);
            text-decoration: underline;
        }
    </style>
</head>
<body>
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
