<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\AuditoriaLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class AuditoriaMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Empezar a escuchar y capturar las consultas SQL (Queries) que haga Laravel durante este request
        $queries = [];
        DB::listen(function ($query) use (&$queries) {
            // Guardamos el texto del SQL y sus valores
            $queries[] = '[SQL] ' . $query->sql . ' | Valores: [' . implode(', ', $query->bindings) . ']';
        });

        // 2. Dejar que la petición continúe su flujo normal
        $response = $next($request);

        // 3. Si el usuario está autenticado, registramos su actividad en la bitácora
        if (Auth::check()) {
            $user = Auth::user();
            
            // Definimos un nombre descriptivo para la acción según la ruta visitada
            $accion = 'Navegación general';
            if ($request->is('asistencias') && $request->isMethod('post')) $accion = 'Creación de Caso de Asistencia';
            elseif ($request->is('asistencias/categorias/*')) $accion = 'Consulta de Categorías por Plan';
            elseif ($request->is('admin/usuarios')) $accion = 'Acceso al Panel de Gestión de Usuarios';
            elseif ($request->is('login') && $request->isMethod('post')) $accion = 'Inicio de Sesión';

            // Guardamos en la base de datos
            AuditoriaLog::create([
                'user_id'        => $user->id,
                'usuario_nombre' => $user->name,
                'accion'         => $accion,
                'url'            => $request->fullUrl(),
                'metodo'         => $request->method(),
                'ip'             => $request->ip(),
                'detalles_query' => implode("\n", $queries), // Unifica todas las consultas SQL ejecutadas
            ]);
        }

        return $response;
    }
}
