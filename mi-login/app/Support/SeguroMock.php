<?php

namespace App\Support;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

/**
 * Usa Http::fake(): cuando AsistenciaController llama a Http::post()/get(),
 * Laravel NO sale a internet, responde con lo que definimos aquí.
 * Así el controlador es EXACTAMENTE el mismo que usarías con la API real.
 */
class SeguroMock
{
    public const ESCENARIOS = [
        'ok'                => 'Todo funciona (200 / 201)',
        'sin_permiso'       => '403 Forbidden (usuario sin permiso, el error que viste)',
        'token_vencido'     => '401 una sola vez (el controlador renueva el token y reintenta)',
        'login_invalido'    => '401 en el login (credenciales incorrectas)',
        'usuario_bloqueado' => '423 en el login (usuario bloqueado)',
        'sin_categorias'    => '200 pero sin categorías (data vacío)',
        'datos_invalidos'   => '400 al crear el caso (cuerpo inválido)',
        'error_servidor'    => '500 (la API falló)',
        'sin_conexion'      => 'Sin conexión (timeout)',
    ];

    public static function register(): void
    {
        Http::fake([
            '*/ext/insurance/token'    => fn (Request $r) => self::login(),
            '*/assistancecategories/*' => fn (Request $r) => self::categorias(),
            '*/assistancecase'         => fn (Request $r) => self::crearCaso($r),
        ]);
    }

    private static function escenario(): string
    {
        return (string) request()->header('X-Mock-Scenario', 'ok');
    }

    /** POST /ext/insurance/token */
    private static function login()
    {
        return match (self::escenario()) {
            'login_invalido'    => Http::response(['code' => 401, 'message' => 'invalid_client'], 401),
            'usuario_bloqueado' => Http::response(['code' => 423, 'message' => 'locked'], 423),
            'sin_conexion'      => throw new ConnectionException('cURL error 28: Operation timed out (simulado)'),
            default             => Http::response(['token' => 'token-simulado-'.bin2hex(random_bytes(6))]),
        };
    }

    /** Fallos que pueden ocurrir en cualquier endpoint que use el token. */
    private static function fallaComun()
    {
        switch (self::escenario()) {
            case 'sin_permiso':
                return Http::response(['error' => 'Forbidden'], 403);

            case 'token_vencido':
                // Cache::add solo guarda si no existía: el primer intento da 401, el reintento pasa.
                if (Cache::add('mock_401_una_vez', true, 30)) {
                    return Http::response(['code' => 401, 'message' => 'token expired'], 401);
                }

                return null;

            case 'error_servidor':
                return Http::response(['error' => 'Internal server error'], 500);

            case 'sin_conexion':
                throw new ConnectionException('cURL error 28: Operation timed out (simulado)');
        }

        return null;
    }

    /** GET /assistancecategories/{plan}/assistanceplan */
    private static function categorias()
    {
        if ($falla = self::fallaComun()) {
            return $falla;
        }

        if (self::escenario() === 'sin_categorias') {
            return Http::response(['data' => [], 'total' => 0]);
        }

        $data = [
            ['id' => 119, 'name' => 'Asistencia vial',     'priority' => 1, 'color' => '#e63946', 'sap_code' => 'VIAL', 'status' => 1],
            ['id' => 120, 'name' => 'Asistencia médica',   'priority' => 2, 'color' => '#2a9d8f', 'sap_code' => 'MED',  'status' => 1],
            ['id' => 121, 'name' => 'Asistencia del hogar', 'priority' => 3, 'color' => '#e9c46a', 'sap_code' => 'HOG',  'status' => 1],
            ['id' => 122, 'name' => 'Asistencia legal',    'priority' => 4, 'color' => '#457b9d', 'sap_code' => 'LEG',  'status' => 1],
        ];

        return Http::response(['data' => $data, 'total' => count($data)]);
    }

    /** POST /assistancecase */
    private static function crearCaso(Request $r)
    {
        if ($falla = self::fallaComun()) {
            return $falla;
        }

        $body = $r->data();

        // Validación "del servidor": si falta lo básico, responde 400 como la API real.
        if (self::escenario() === 'datos_invalidos'
            || empty(data_get($body, 'member.first_name'))
            || empty(data_get($body, 'member_id'))) {
            return Http::response(['code' => 400, 'message' => 'Invalid request body'], 400);
        }

        return Http::response([
            'id'      => random_int(100000, 999999),
            'status'  => 'CREATED',
            'message' => 'Caso creado (simulado)',
        ], 201);
    }
}
