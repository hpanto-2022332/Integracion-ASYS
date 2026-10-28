<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Usuarios - Avanta Admin</title>
    <style>
        /* ==========================================================================
           🏢 AVANTA CORE SYSTEM - HOJA DE ESTILOS UNIFICADA E INTEGRADA
           ========================================================================== */
        :root {
            --avanta-azul-profundo: #04114A;
            --avanta-azul-electrico: #0F63FF;
            --avanta-celeste: #2FE7FF;
            --avanta-blanco: #FFFFFF;
            --panel-bg: #111B54;
            --border-color: #2F3A75;
            --text-muted: #98A9C4;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            background-color: #F8FAFC; /* Fondo claro oficial para el panel de administración */
            color: var(--avanta-azul-profundo);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* 🛡️ NAVBAR CORPORATIVA GLOBAL UNIFICADA (FLEXBOX CORREGIDO) */
        .navbar {
            background-color: var(--avanta-azul-profundo);
            border-bottom: 1px solid var(--border-color);
            padding: 12px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 100%;
        }

        .navbar-brand {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .navbar-brand img {
            height: 54px; /* Escala aumentada oficial a 54px */
            width: auto;
            display: block;
        }

        .navbar-brand span {
            font-size: 22px;
            font-weight: 300;
            color: var(--avanta-celeste);
            letter-spacing: 0.5px;
        }

        .nav-right-container {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .nav-links-menu {
            display: flex;
            gap: 20px;
        }

        .nav-links-menu a {
            color: var(--text-muted);
            text-decoration: none;
            font-size: 15px;
            font-weight: 500;
            transition: color 0.3s;
        }

        .nav-links-menu a.active {
            color: var(--avanta-celeste);
            font-weight: 700;
        }

        .nav-links-menu a:hover {
            color: var(--avanta-blanco);
        }

        .btn-logout {
            background: transparent;
            border: 1px solid var(--avanta-celeste);
            color: var(--avanta-celeste);
            padding: 8px 16px;
            font-size: 14px;
            font-weight: 500;
            border-radius: 4px;
            cursor: pointer;
            transition: all 0.3s;
        }

        .btn-logout:hover {
            background-color: var(--avanta-celeste);
            color: var(--avanta-azul-profundo);
        }

        /* 📝 CONTENEDOR Y MAQUETACIÓN DE LA TABLA */
        .container {
            max-width: 1100px;
            margin: 40px auto;
            padding: 0 20px;
            width: 100%;
        }

        .header-section {
            margin-bottom: 30px;
            text-align: left;
        }

        .header-section h2 {
            font-size: 32px;
            font-weight: 700;
            margin: 0 0 10px 0;
            color: var(--avanta-azul-profundo);
        }

        .header-section p {
            font-size: 16px;
            color: #64748B;
            margin: 0;
        }

        .table-responsive {
            background-color: #FFFFFF;
            border-radius: 8px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
            overflow: hidden;
            border: 1px solid #E2E8F0;
            width: 100%;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
        }

        th {
            background-color: #F1F5F9;
            color: var(--avanta-azul-profundo);
            font-weight: 700;
            font-size: 14px;
            padding: 16px;
            text-transform: uppercase;
            border-bottom: 2px solid #E2E8F0;
        }

        td {
            padding: 16px;
            font-size: 14px;
            color: #334155;
            border-bottom: 1px solid #E2E8F0;
        }

        tr:last-child td {
            border-bottom: none;
        }

        tr:hover {
            background-color: #F8FAFC;
        }

        /* BADGES Y COMPONENTES VISUALES */
        .badge {
            display: inline-block;
            padding: 4px 12px;
            font-size: 12px;
            font-weight: 700;
            border-radius: 9999px;
            text-transform: capitalize;
        }

        .badge-admin { background-color: #E0F2FE; color: var(--avanta-azul-electrico); }
        .badge-cliente { background-color: #DCFCE7; color: #15803D; }
        
        .flag-badge {
            background-color: #F1F5F9;
            color: var(--avanta-azul-profundo);
            padding: 4px 10px;
            font-size: 13px;
            border-radius: 4px;
            font-weight: 500;
            border: 1px solid #CBD5E1;
            display: inline-block;
        }
    </style>
</head>
<body>

    <!-- Navbar Corporativa Completa -->
    <nav class="navbar">
        <div class="navbar-brand">
            <img src="{{ asset('imagenes/logoAVANTA.png') }}" alt="Logotipo Avanta">
            <span>| Panel</span>
        </div>
        
        <div class="nav-right-container">
            <div class="nav-links-menu">
                <a href="{{ route('dashboard') }}">Inicio</a>
                <a href="{{ route('asistencias') }}">Solicitar Asistencia</a>
                <a href="{{ route('admin.usuarios') }}" class="active">Usuarios</a>
                <a href="{{ route('admin.bitacora') }}">Bitácora SQL</a>
            </div>
            <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
                @csrf
                <button type="submit" class="btn-logout">Cerrar Sesión</button>
            </form>
        </div>
    </nav>

    <!-- Contenido Principal -->
    <div class="container">
        <div class="header-section">
            <h2>Control y Gestión de Usuarios</h2>
            <p>Monitoreo perimetral de cuentas registradas en la plataforma corporativa.</p>
        </div>

        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Nombre Completo</th>
                        <th>Correo Electrónico</th>
                        <th>Región / País</th>
                        <th>Rol Asignado</th>
                        <th>Fecha de Registro</th>
                        <th style="text-align: center;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($usuarios as $usuario)
                        <tr>
                            <td style="font-weight: 500;">{{ $usuario->name }}</td>
                            <td>{{ $usuario->email }}</td>
                            <td>
                                <span class="flag-badge">
                                    {{ $usuario->pais ?? 'Guatemala' }}
                                </span>
                            </td>
                            <td>
                                @if($usuario->hasRole('admin'))
                                    <span class="badge badge-admin">Administrador</span>
                                @else
                                    <span class="badge badge-cliente">Cliente</span>
                                @endif
                            </td>
                            <td>{{ $usuario->created_at->timezone('America/Guatemala')->format('d/m/Y H:i') }}</td>
                            
                            <!-- Control operativo de roles -->
                            <td style="text-align: center;">
                                @if($usuario->id !== Auth::id())
                                    <form action="{{ route('admin.usuarios.cambiarRol', $usuario->id) }}" method="POST" style="margin: 0; display: inline;">
                                        @csrf
                                        <button type="submit" class="btn-logout" style="font-size: 12px; padding: 6px 12px; border-color: #0F63FF; color: #0F63FF; font-weight: bold; background: transparent; cursor: pointer; border-radius: 4px; transition: all 0.3s;" onmouseover="this.style.backgroundColor='#0F63FF'; this.style.color='#FFFFFF'" onmouseout="this.style.backgroundColor='transparent'; this.style.color='#0F63FF'">
                                            Hacer {{ $usuario->hasRole('admin') ? 'Cliente' : 'Admin' }}
                                        </button>
                                    </form>
                                @else
                                    <span style="color: #98A9C4; font-size: 13px; font-style: italic;">Sesión Activa</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</body>
</html>
