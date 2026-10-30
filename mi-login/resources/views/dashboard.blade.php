<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Avanta - Dashboard</title>
    <link rel="icon" type="image/png" href="{{ asset('imagenes/logo_AVANTA.png') }}">
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            background-color: #F8FAFC;
            color: #04114A;
            margin: 0;
            padding: 0;
        }

        .navbar {
            background-color: #04114A;
            padding: 10px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: #FFFFFF;
            box-sizing: border-box;
        }

        .navbar-brand {
            display: flex;
            align-items: center;
            gap: 1px;
        }

        .navbar-brand img {
            height: 65px;
            width: auto;
            display: block;
        }

        .navbar-brand span {
            font-size: 25px;
            font-weight: 300;
            color: #2FE7FF;
            letter-spacing: 0.5px;
        }

        .nav-right-container {
            display: flex;
            align-items: center;
            gap: 25px;
        }

        .nav-links-menu {
            display: flex;
            gap: 20px;
        }

        .nav-links-menu a {
            color: #98A9C4;
            text-decoration: none;
            font-size: 15px;
            font-weight: 500;
            transition: color 0.3s;
        }

        .nav-links-menu a.active {
            color: #2FE7FF;
            font-weight: 700;
        }

        .nav-links-menu a:hover {
            color: #FFFFFF;
        }

        .btn-logout {
            background: transparent;
            border: 1px solid #2FE7FF;
            color: #2FE7FF;
            padding: 8px 16px;
            font-size: 14px;
            font-weight: 500;
            border-radius: 4px;
            cursor: pointer;
            transition: all 0.3s;
        }

        .btn-logout:hover {
            background-color: #2FE7FF;
            color: #04114A;
        }

        .container {
            max-width: 1200px;
            margin: 40px auto;
            padding: 0 20px;
        }

        .header-section {
            margin-bottom: 30px;
        }

        .header-section h2 {
            font-size: 32px;
            font-weight: 700;
            margin: 0 0 10px 0;
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
        }

        table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
        }

        th {
            background-color: #F1F5F9;
            color: #04114A;
            font-weight: 700;
            font-size: 13px;
            padding: 16px;
            text-transform: uppercase;
            border-bottom: 2px solid #E2E8F0;
        }

        td {
            padding: 14px 16px;
            font-size: 14px;
            color: #334155;
            border-bottom: 1px solid #E2E8F0;
            vertical-align: top;
        }

        tr:hover {
            background-color: #F8FAFC;
        }

        .query-box {
            background-color: #04114A;
            color: #35FF57;
            /* Verde Neón de código */
            padding: 10px;
            border-radius: 6px;
            font-family: 'Courier New', Courier, monospace;
            font-size: 12px;
            max-height: 120px;
            overflow-y: auto;
            white-space: pre-wrap;
            margin: 0;
            box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.2);
        }

        .badge-method {
            padding: 4px 8px;
            font-size: 11px;
            font-weight: 700;
            border-radius: 4px;
            color: white;
            display: inline-block;
        }

        .bg-post {
            background-color: #0F63FF;
        }

        .bg-get {
            background-color: #16A34A;
        }

        /* 🛡️ ARREGLO DE PAGINACIÓN COMPACTA Y PEQUEÑA */
        .pagination-wrapper {
            margin-top: 30px;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .pagination-wrapper ul.pagination {
            display: flex;
            padding-left: 0;
            list-style: none;
            border-radius: 6px;
            gap: 5px;
            margin: 0;
        }

        .pagination-wrapper ul.pagination li.page-item span,
        .pagination-wrapper ul.pagination li.page-item a {
            display: block;
            padding: 8px 14px;
            font-size: 14px;
            color: #04114A;
            background-color: #FFFFFF;
            border: 1px solid #E2E8F0;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 500;
            transition: all 0.2s ease;
        }

        .pagination-wrapper ul.pagination li.page-item a:hover {
            background-color: #F1F5F9;
            border-color: #CBD5E1;
            color: #0F63FF;
        }

        .pagination-wrapper ul.pagination li.page-item.active span {
            background-color: #0F63FF !important;
            color: #FFFFFF !important;
            border-color: #0F63FF !important;
            font-weight: 700;
        }

        .pagination-wrapper ul.pagination li.page-item.disabled span {
            color: #94A3B8;
            background-color: #F8FAFC;
            border-color: #E2E8F0;
            cursor: not-allowed;
        }

        .main-content {
            max-width: 1200px;
            margin: 40px auto;
            padding: 0 20px;
            box-sizing: border-box;
        }

        .dashboard-card {
            background-color: #FFFFFF;
            border-radius: 8px;
            padding: 30px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
            border: 1px solid #E2E8F0;
        }

        .dashboard-card h2 {
            font-size: 28px;
            font-weight: 700;
            color: #04114A;
            margin-top: 0;
            margin-bottom: 25px;
        }

        .actions-container {
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
        }

        .btn-avanta {
            display: inline-block;
            padding: 12px 24px;
            font-size: 15px;
            font-weight: 600;
            text-decoration: none;
            border-radius: 6px;
            transition: all 0.3s ease;
            text-align: center;
        }

        .btn-primary {
            background-color: #0F63FF;
            color: #FFFFFF;
            border: 1px solid #0F63FF;
        }

        .btn-primary:hover {
            background-color: #04114A;
            border-color: #04114A;
        }

        .btn-secondary {
            background-color: transparent;
            color: #04114A;
            border: 1px solid #CBD5E1;
        }

        .btn-secondary:hover {
            background-color: #F1F5F9;
            border-color: #94A3B8;
        }

        .mode-badge {
            background-color: #FFFBEB;
            color: #B45309;
            border: 1px solid #FDE68A;
            padding: 16px 20px;
            border-radius: 6px;
            font-size: 15px;
            font-weight: 500;
            line-height: 1.5;
            width: 100%;
        }
    </style>
</head>

<body>

    <!-- barra de navegacion -->
    <nav class="navbar">
        <div class="navbar-brand">
            <img src="{{ asset('imagenes/logoAVANTA.png') }}" alt="Logotipo Avanta">
            @role('admin') <span>| Panel</span> @endrole
        </div>

        <div class="nav-right-container">
            <div class="nav-links-menu">
                <a href="{{ route('dashboard') }}" class="{{ Request::is('dashboard') ? 'active' : '' }}">Inicio</a>
                <a href="{{ route('asistencias') }}" class="{{ Request::is('asistencias*') ? 'active' : '' }}">Emitir Asistencia</a>
                @role('admin')
                <a href="{{ route('admin.usuarios') }}" class="{{ Request::is('admin/usuarios*') ? 'active' : '' }}">Usuarios</a>
                <a href="{{ route('admin.bitacora') }}" class="{{ Request::is('admin/bitacora*') ? 'active' : '' }}">Bitácora SQL</a>
                @endrole
            </div>
            <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
                @csrf
                <button type="submit" class="btn-logout">Cerrar Sesión</button>
            </form>
        </div>
    </nav>

    <!-- Principal -->
    <div class="main-content">
        <div class="dashboard-card">
            <h2>Hola, {{ Auth::user()->name }} 👋</h2>

            <div class="actions-container">
                @role('admin')
                <a href="{{ route('asistencias') }}" class="btn-avanta btn-primary">
                    Crear / Solicitar Asistencia
                </a>
                <a href="{{ route('admin.usuarios') }}" class="btn-avanta btn-secondary">
                    Ir al Panel de Control
                </a>
                @else
                <div class="mode-badge">
                    Tu cuenta se encuentra en modo de lectura. Solicita autorización con el administrador para activar la creación de asistencias.
                </div>
                @endrole
            </div>
        </div>
    </div>

</body>

</html>