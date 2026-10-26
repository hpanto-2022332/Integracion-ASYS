<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use RuntimeException;

class AsistenciaController extends Controller
{
    private const TOKEN_KEY = 'seguro_api_token';

    public function form()
    {
        $misPlanes = DB::table('usuario_planes')
            ->where('user_id', Auth::id())
            ->get(); // Trae solo lo suyo

        return view('asistencias.form', compact('misPlanes'));
    }

    /** Olvida el token guardado (se usa al cambiar de escenario en la simulación). */
    public function reiniciarToken(): JsonResponse
    {
        Cache::forget(self::TOKEN_KEY);

        return response()->json(['ok' => true]);
    }

    public function categorias(int $plan): JsonResponse
    {
        // 1. OBTENER EL USUARIO QUE TIENE LA SESIÓN INICIADA
        $user = Auth::user();

        // 2. VALIDAR SEGURIDAD LOCAL
        // Comprobamos si el plan que introdujo existe en los servicios asociados a su cuenta corporativa o perfil
        $tieneAcceso = DB::table('usuario_planes')
            ->where('user_id', $user->id)
            ->where('assistance_plan_id', $plan)
            ->exists();

        // Si intenta poner un ID que no le pertenece, bloqueamos la petición de inmediato
        if (!$tieneAcceso) {
            return response()->json([
                'message' => 'No tienes autorización para consultar este plan de asistencia.'
            ], 403); // Código 403: Prohibido / Acceso Denegado
        }

        // 3. SI TODO ESTÁ BIEN, SE CONSULTA LA API
        return $this->proxy(fn() => $this->api('get', "/assistancecategories/{$plan}/assistanceplan"));
    }

    /** POST /assistancecase */
    public function crear(Request $request): JsonResponse
    {
        $d = $request->validate([
            'assistance_plan_id'  => 'required|integer',
            'assistance_id'       => 'required|integer',
            'member_id'           => 'required|integer',
            'first_name'          => 'required|string|max:100',
            'middle_name'         => 'nullable|string|max:100',
            'last_name'           => 'required|string|max:100',
            'last_name_2'         => 'nullable|string|max:100',
            'national_id'         => 'required|string|max:30',
            'phone1'              => 'required|string|max:30',
            'member_country'      => 'nullable|integer',
            'contact_name'        => 'required|string|max:150',
            'country_phone_code'  => 'nullable|integer',
            'contact_channel'     => 'nullable|integer',
            'phone'               => 'required|string|max:30',
            'email'               => 'required|email',
            'country_id'          => 'nullable|integer',
            'state_id'            => 'nullable|integer',
            'state_district_id'   => 'nullable|integer',
            'zone'                => 'nullable|integer',
            'suburb'              => 'nullable|string|max:150',
            'street'              => 'nullable|string|max:150',
            'area'                => 'nullable|string|max:150',
            'postal_code'         => 'nullable|string|max:20',
            'reference'           => 'nullable|string|max:255',
        ]);

        $nombre = trim(implode(' ', array_filter([
            $d['first_name'],
            $d['middle_name'] ?? null,
            $d['last_name'],
            $d['last_name_2'] ?? null,
        ])));

        // Misma estructura que el ejemplo de Swagger
        $body = [
            'member' => [
                'first_name'  => $d['first_name'],
                'middle_name' => $d['middle_name'] ?? '',
                'last_name'   => $d['last_name'],
                'last_name_2' => $d['last_name_2'] ?? '',
                'country'     => $d['member_country'] ?? 1,
                'national_id' => $d['national_id'],
                'name'        => $nombre,
                'phone1'      => $d['phone1'],
            ],
            'postal_code'        => $d['postal_code'] ?? '00000',
            'assistance_id'      => $d['assistance_id'],
            'assistance_plan_id' => $d['assistance_plan_id'],
            'assistance_sale_id' => null,
            'country_id'         => $d['country_id'] ?? 1,
            'member_id'          => $d['member_id'],
            'contact_name'       => $d['contact_name'],
            'country_phone_code' => $d['country_phone_code'] ?? 502,
            'contact_channel'    => $d['contact_channel'] ?? 2,
            'phone'              => $d['phone'],
            'email'              => $d['email'],
            'state_id'           => $d['state_id'] ?? null,
            'state_district_id'  => $d['state_district_id'] ?? null,
            'street'             => $d['street'] ?? '0',
            'zone'               => $d['zone'] ?? null,
            'suburb'             => $d['suburb'] ?? '',
            'area'               => $d['area'] ?? '',
            'reference'          => $d['reference'] ?? '',
        ];

        return $this->proxy(fn() => $this->api('post', '/assistancecase', $body));
    }

    /* ------------------------------------------------------------------ */

    /** Token de la API: se pide una vez y se reutiliza desde el caché. */
    private function token(bool $refresh = false): string
    {
        if ($refresh) {
            Cache::forget(self::TOKEN_KEY);
        }

        return Cache::remember(self::TOKEN_KEY, now()->addMinutes(50), function () {
            $res = Http::acceptJson()->timeout(20)->post($this->url('/ext/insurance/token'), [
                // Si la API espera otros nombres de campo, cámbialos aquí.
                'email'    => config('services.seguro.email'),
                'password' => config('services.seguro.password'),
            ]);

            if ($res->failed()) {
                $motivo = $res->json('message') ?? $res->json('error') ?? '';
                throw new RuntimeException('No se pudo iniciar sesión en la API (HTTP ' . $res->status() . ')' . ($motivo ? ': ' . $motivo : '.'));
            }

            $json  = $res->json();
            $token = is_array($json)
                ? ($json['token'] ?? $json['access_token'] ?? data_get($json, 'data.token') ?? data_get($json, 'data.access_token'))
                : trim($res->body(), "\" \n\r");

            if (! $token) {
                throw new RuntimeException('La respuesta de la API no trae token.');
            }

            return $token;
        });
    }

    /** Llama a la API con token; si responde 401 renueva el token y reintenta una vez. */
    private function api(string $method, string $path, ?array $data = null): Response
    {
        $send = fn(string $token) => Http::withToken($token)->acceptJson()->timeout(20)
            ->{$method}($this->url($path), $data ?? []);

        $res = $send($this->token());

        if ($res->status() === 401) {
            $res = $send($this->token(refresh: true));
        }

        return $res;
    }

    private function url(string $path): string
    {
        return rtrim((string) config('services.seguro.url'), '/') . $path;
    }

    /** Devuelve al navegador lo que respondió la API (o un error claro). */
    private function proxy(callable $call): JsonResponse
    {
        try {
            /** @var Response $res */
            $res = $call();

            $payload = $res->json() ?? ['message' => $res->body()];
            if ($res->failed() && empty($payload['message'])) {
                $payload['message'] = 'La API respondió ' . $res->status() . ': ' . mb_substr($res->body(), 0, 300);
            }

            return response()->json($payload, $res->status());
        } catch (ConnectionException $e) {
            report($e);

            return response()->json(['message' => 'No se pudo conectar con la API: ' . $e->getMessage()], 502);
        } catch (RuntimeException $e) {
            report($e);

            return response()->json(['message' => $e->getMessage()], 502);
        }
    }
}
