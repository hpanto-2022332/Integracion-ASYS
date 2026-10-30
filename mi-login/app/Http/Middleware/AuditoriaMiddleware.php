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
            if ($request->is('asistencias/emitir') && $request->isMethod('post')) {
                $accion = 'Emisión de Asistencia (Sales Transaction)';
            } elseif ($request->is('asistencias/catalogo/countries/*/*')) {
                $accion = 'Consulta de Catálogo de Municipios / Distritos';
            } elseif ($request->is('asistencias/catalogo/countries/*')) {
                $accion = 'Consulta de Catálogo de Departamentos';
            } elseif ($request->is('asistencias/catalogo/countries')) {
                $accion = 'Consulta de Catálogo de Países';
            } elseif ($request->is('asistencias') && $request->isMethod('post')) {
                $accion = 'Creación de Caso de Asistencia';
            } elseif ($request->is('asistencias') && $request->isMethod('get')) {
                $accion = 'Acceso al Formulario de Emisión';
            } elseif ($request->is('asistencias/categorias/*')) {
                $accion = 'Consulta de Categorías por Plan';
            } elseif ($request->is('admin/usuarios') && $request->isMethod('get')) {
                $accion = 'Acceso al Panel de Gestión de Usuarios';
            } elseif ($request->is('admin/usuarios/*/cambiar-rol')) {
                $accion = 'Cambio de Rol de Usuario';
            } elseif ($request->is('admin/bitacora')) {
                $accion = 'Consulta de Bitácora de Auditoría';
            } elseif ($request->is('login') && $request->isMethod('post')) {
                $accion = 'Inicio de Sesión';
            }

            // Si no hubo queries de BD (por ser consumo de API externa o vista estática), dejamos un log técnico explicativo
            if (empty($queries)) {
                if ($request->is('asistencias/emitir')) {
                    $queries[] = "[API REST] POST /assistance/sales/transaction\n[DESTINO] https://dev.apolo11.online/api/assistance/sales/transaction\n[ESTADO BD] Transacción enviada vía HTTP REST Client hacia aseguradora (Operación externa sin inyección SQL local)";
                } elseif ($request->is('asistencias/catalogo/*') || $request->is('asistencias/categorias/*')) {
                    $queries[] = "[API CATÁLOGO] GET " . $request->path() . "\n[SERVICIO] Apolo11 REST API / Catálogo en memoria\n[ESTADO BD] Consulta procesada vía API externa (Sin acceso ni bloqueo a la base de datos local)";
                } elseif ($request->is('asistencias*')) {
                    $queries[] = "[RENDER BLADE] " . $request->method() . " " . $request->path() . "\n[INTERFAZ] Carga de componentes de la vista y verificación de sesión en memoria\n[ESTADO BD] Solicitud resuelta sin queries SQL requeridos";
                } else {
                    $queries[] = "[OPERACIÓN EN MEMORIA] " . $request->method() . " " . $request->path() . "\n[ESTADO BD] Proceso completado sin ejecutar queries SQL en este hilo";
                }
            }

            // Guardamos en la base de datos
            AuditoriaLog::create([
                'user_id'        => $user->id,
                'usuario_nombre' => $user->name,
                'accion'         => $accion,
                'url'            => $request->fullUrl(),
                'metodo'         => $request->method(),
                'ip'             => $request->ip(),
                'detalles_query' => implode("\n", $queries), // Unifica todas las consultas SQL o detalles de API ejecutadas
            ]);
        }

        return $response;
    }
}
