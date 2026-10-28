<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bitácora de Auditoría - Avanta Admin</title>
    <style>
        /* Estilos alineados al Brandbook de Avanta */
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            background-color: #F8FAFC;
            color: #04114A;
            /* Azul Profundo */
            margin: 0;
            padding: 0;
        }

        .navbar {
            background-color: #04114A;
            /* Azul Profundo */
            padding: 10px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: #FFFFFF;
        }

        .navbar-brand {
            display: flex;
            align-items: center;
            gap: 1px;
        }

        .navbar-brand img {
            height: 60px;
            width: auto;
            display: block;
        }

        .navbar-brand span {
            font-size: 25px;
            font-weight: 300;
            color: #2FE7FF;
            /* Celeste */
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
            /* Celeste activo */
            font-weight: 700;
        }

        .nav-links-menu a:hover {
            color: #FFFFFF;
        }

        .btn-logout {
            background: transparent;
            border: 1px solid #2FE7FF;
            /* Celeste */
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
    </style>
</head>

<body>

    <nav class="navbar">
        <div class="navbar-brand">
            <img src="{{ asset('imagenes/logoAVANTA.png') }}" alt="Logotipo Avanta">
            <span>| Panel</span>
        </div>

        <div class="nav-right-container">
            <div class="nav-links-menu">
                <a href="{{ route('asistencias') }}">Solicitar Asistencia</a>
                <a href="{{ route('admin.usuarios') }}" class="{{ Request::is('admin/usuarios') ? 'active' : '' }}">Usuarios</a>
                <a href="{{ route('admin.bitacora') }}" class="{{ Request::is('admin/bitacora') ? 'active' : '' }}">Bitácora SQL</a>
            </div>
            <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
                @csrf
                <button type="submit" class="btn-logout">Cerrar Sesión</button>
            </form>
        </div>
    </nav>

    <div class="container">
        <div class="header-section">
            <h2>Bitácora de Auditoría de Sistemas</h2>
            <p>Monitoreo interactivo en tiempo real de transacciones, navegación e inyección de queries SQL de los colaboradores.</p>
        </div>

        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th style="width: 15%;">Usuario / IP</th>
                        <th style="width: 20%;">Acción Ejecutada</th>
                        <th style="width: 8%;">Método</th>
                        <th style="width: 42%;">Consultas SQL e Inyecciones de Base de Datos</th>
                        <th style="width: 15%;">Fecha y Hora</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $log)
                    <tr>
                        <td>
                            <strong style="color: #04114A;">{{ $log->usuario_nombre ?? 'Sistema' }}</strong>
                            <br><span style="color: #64748B; font-size: 12px;">IP: {{ $log->ip }}</span>
                        </td>
                        <td>
                            <span style="font-weight: 500;">{{ $log->accion }}</span>
                            <br><span style="color: #94A3B8; font-size: 11px; word-break: break-all;">{{ $log->url }}</span>
                        </td>
                        <td>
                            <span class="badge-method {{ $log->metodo === 'POST' ? 'bg-post' : 'bg-get' }}">
                                {{ $log->metodo }}
                            </span>
                        </td>
                        <td>
                            @if($log->detalles_query)
                            <pre class="query-box">{{ $log->detalles_query }}</pre>
                            @else
                            <span style="color: #94A3B8; font-style: italic; font-size: 13px;">Sin queries SQL ejecutados en este hilo.</span>
                            @endif
                        </td>
                        <td style="font-weight: 500;">
                            {{ $log->created_at->timezone('America/Guatemala')->format('d/m/Y H:i:s') }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" style="text-align: center; color: #94A3B8; padding: 30px;">
                            La bitácora se encuentra vacía en este momento.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Contenedor con estilos de reinicio para encoger las flechas -->
        <div class="pagination-wrapper">
            {{ $logs->links() }}
        </div>
    </div>

</body>

</html>