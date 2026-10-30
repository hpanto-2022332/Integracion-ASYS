<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use RuntimeException;

class AsistenciaController extends Controller
{
    private const TOKEN_KEY = 'seguro_api_token';

    /**
     * Muestra el formulario de emisión de asistencia.
     */
    public function form()
    {
        return view('asistencias.form');
    }

    /**
     * Catálogo de países: consume GET /countries de la API externa.
     * La API real devuelve { "data": [...] } con campos:
     *   id, name, s6_equivalence_code (código ISO p. ej. "GT"), sap_code, etc.
     * Filtramos exclusivamente Guatemala (GT) y El Salvador (SV).
     * Fallback automático si el servidor externo no está disponible.
     */
    public function catalogoPaises(): JsonResponse
    {
        if ($this->shouldUseMock()) {
            return response()->json($this->mockPaises());
        }

        try {
            $res = $this->api('get', '/countries');
            if ($res->successful()) {
                $body = $res->json();

                // La API externa devuelve { "data": [...] }
                $lista = is_array($body)
                    ? ($body['data'] ?? (isset($body[0]) ? $body : []))
                    : [];

                if (!empty($lista)) {
                    // Normalizamos cada país al formato que espera el frontend:
                    // { id, name, code } donde code = s6_equivalence_code
                    $normalizados = [];
                    foreach ($lista as $p) {
                        $code = strtoupper(trim(
                            $p['s6_equivalence_code'] ?? $p['code'] ?? $p['iso3166_1'] ?? ''
                        ));
                        // Solo Guatemala y El Salvador
                        if (!in_array($code, ['GT', 'SV'])) {
                            continue;
                        }
                        $normalizados[] = [
                            'id'        => $p['id']   ?? $code,
                            'name'      => $p['name'] ?? $code,
                            'code'      => $code,
                            'countryId' => $code,
                            // Campos extra que puede necesitar el frontend
                            'state_text'          => $p['state_text']          ?? null,
                            'state_district_text' => $p['state_district_text'] ?? null,
                            'national_id_text'    => $p['national_id_text']    ?? null,
                        ];
                    }

                    if (!empty($normalizados)) {
                        return response()->json($normalizados);
                    }
                }
            }
        } catch (\Throwable $e) {
            report($e);
        }

        // Fallback: datos locales (solo GT y SV)
        return response()->json($this->mockPaises());
    }

    /**
     * Catálogo de estados/departamentos por país.
     * Consume GET /countries/{countryId}/states de la API externa.
     * La API real devuelve: [{ "id": 0, "name": "string", "country": 0, "latitude": 0, "longitude": 0 }]
     * GT → numericId=1 en la API externa
     * SV → numericId=2 en la API externa
     */
    public function catalogoEstados(string $countryId): JsonResponse
    {
        $code = strtoupper(trim($countryId));
        $isSV = ($code === 'SV' || $code === '2');
        $resolvedCountry = $isSV ? 'SV' : 'GT';
        $numericId = $isSV ? 2 : 1;

        if ($this->shouldUseMock()) {
            return response()->json($this->mockEstados($resolvedCountry));
        }

        try {
            $res = $this->api('get', "/countries/{$numericId}/states");
            if ($res->successful()) {
                $body = $res->json();

                // La API devuelve array directo: [{ id, name, country, latitude, longitude }]
                // o puede venir en { data: [...] }
                $lista = is_array($body)
                    ? (isset($body[0]) ? $body : ($body['data'] ?? []))
                    : [];

                if (!empty($lista)) {
                    // Normalizar al formato { id, name } esperado por el frontend
                    $normalizados = array_map(fn($s) => [
                        'id'   => $s['id']   ?? $s['stateId']   ?? '',
                        'name' => $s['name'] ?? $s['description'] ?? "Departamento",
                    ], $lista);

                    return response()->json($normalizados);
                }
            }
        } catch (\Throwable $e) {
            report($e);
        }

        return response()->json($this->mockEstados($resolvedCountry));
    }

    /**
     * Catálogo de distritos / municipios por país y departamento:
     * consume GET /countries/{countryId}/states/{stateId}/districts
     */
    public function catalogoDistritos(string $countryId, string $stateId): JsonResponse
    {
        $code = strtoupper(trim($countryId));
        $isSV = ($code === 'SV' || $code === '2');
        $resolvedCountry = $isSV ? 'SV' : 'GT';
        $numericCountry = $isSV ? 2 : 1;
        $stateNum = (int) $stateId;

        if ($this->shouldUseMock()) {
            return response()->json($this->mockDistritos($resolvedCountry, $stateNum));
        }

        try {
            $res = $this->api('get', "/countries/{$numericCountry}/states/{$stateNum}/districts");
            if ($res->successful()) {
                $distritos = $res->json();
                if (is_array($distritos) && count($distritos) > 0) {
                    return response()->json($distritos);
                }
            }

            $res2 = $this->api('get', "/states/{$stateNum}/districts");
            if ($res2->successful()) {
                $distritos = $res2->json();
                if (is_array($distritos) && count($distritos) > 0) {
                    return response()->json($distritos);
                }
            }
        } catch (\Throwable $e) {
            report($e);
        }

        return response()->json($this->mockDistritos($resolvedCountry, $stateNum));
    }

    /**
     * Recibe el payload del formulario, lo valida y lo reenvía al endpoint
     * POST /assistance/sales/transaction de la API externa.
     */
    public function emitir(Request $request): JsonResponse
    {
        // ── Validación de los campos obligatorios que vienen del formulario ──
        $d = $request->validate([
            // Transacción
            'transactionId'          => 'required|string|max:50',
            'tranTypeDescription'    => 'required|string|max:150',
            'countryId'              => 'required|string|max:5',
            'planId'                 => 'required|integer',
            'sellerId'               => 'required|string|max:50',
            'issuedDate'             => 'required|date_format:Y-m-d',
            'tranDate'               => 'required|date_format:Y-m-d',
            'admissionEffectiveDate' => 'required|date_format:Y-m-d',
            'expirationDate'         => 'required|date_format:Y-m-d',
            'sponsorId'              => 'required|string|max:50',
            'salesChannel'           => 'required|string|max:20',
            'stateId'                => 'required|integer|min:1',
            'districtId'             => 'required|integer|min:1',

            // Customer principal
            'customers'                      => 'required|array|min:1',
            'customers.0.nationalId'         => 'required|string|max:30',
            'customers.0.firstName'          => 'required|string|max:100',
            'customers.0.middleName'         => 'nullable|string|max:100',
            'customers.0.lastName'           => 'required|string|max:100',
            'customers.0.secondLastName'     => 'nullable|string|max:100',
            'customers.0.birthDate'          => 'required|date_format:Y-m-d',
            'customers.0.emailAddress'       => 'required|email|max:150',
            'customers.0.phoneNumber1'       => 'required|string|min:8|max:30',
            'customers.0.phoneNumber2'       => 'nullable|string|max:30',
            'customers.0.address'            => 'required|string|max:200',
            'customers.0.gender'             => 'required|integer|in:1,2',
            'customers.0.company'            => 'nullable|string|max:150',

            // additionalData
            'additionalData.salesman_name'   => 'required|string|max:150',
            'additionalData.location_name'   => 'required|string|max:50',

            // extraData (soporta 'price', 'saldo' o ambos)
            'extraData'                      => 'required|array|min:1',
            'extraData.*.key'                => 'required|string',
            'extraData.*.value'              => 'required|string',

            // extraDataCar (Datos del vehículo)
            'extraDataCar'                   => 'nullable|array',
            'extraDataCar.brand'             => 'nullable|string|max:100',
            'extraDataCar.model'             => 'nullable|string|max:100',
            'extraDataCar.type'              => 'nullable|string|max:100',
            'extraDataCar.year'              => 'nullable|string|max:10',
            'extraDataCar.plate'             => 'nullable|string|max:20',
            'extraDataCar.color'             => 'nullable|string|max:50',
            'extraDataCar.serialNumber'      => 'nullable|string|max:100',
            'extraDataCar.engineNumber'      => 'nullable|string|max:100',
        ]);

        // ── Construir el payload limpio para la API ──────────────────────────
        $payload = [
            'transactionType'        => 'EMI',
            'transactionId'          => $d['transactionId'],
            'tranTypeDescription'    => $d['tranTypeDescription'],
            'countryId'              => $d['countryId'],
            'planId'                 => (int) $d['planId'],
            'sellerId'               => $d['sellerId'],
            'issuedDate'             => $d['issuedDate'],
            'tranDate'               => $d['tranDate'],
            'admissionEffectiveDate' => $d['admissionEffectiveDate'],
            'expirationDate'         => $d['expirationDate'],
            'sponsorId'              => $d['sponsorId'],
            'salesChannel'           => $d['salesChannel'],
            'stateId'                => (int) $d['stateId'],
            'districtId'             => (int) $d['districtId'],

            'customers' => [
                [
                    'type'           => 'MI',
                    'nationalId'     => $d['customers'][0]['nationalId'],
                    'firstName'      => $d['customers'][0]['firstName'],
                    'middleName'     => $d['customers'][0]['middleName']     ?? '',
                    'lastName'       => $d['customers'][0]['lastName'],
                    'secondLastName' => $d['customers'][0]['secondLastName'] ?? '',
                    'birthDate'      => $d['customers'][0]['birthDate'],
                    'emailAddress'   => $d['customers'][0]['emailAddress'],
                    'phoneNumber1'   => $d['customers'][0]['phoneNumber1']   ?? '',
                    'phoneNumber2'   => $d['customers'][0]['phoneNumber2']   ?? '',
                    'phoneNumber3'   => '',
                    'phoneNumber4'   => '',
                    'address'        => $d['customers'][0]['address'],
                    'gender'         => (int) $d['customers'][0]['gender'],
                    'company'        => $d['customers'][0]['company']        ?? '',
                ],
            ],

            'additionalData' => [
                'salesman_name' => $d['additionalData']['salesman_name'],
                'location_name' => $d['additionalData']['location_name'],
            ],

            'extraData' => array_values(array_map(fn ($item) => [
                'key'   => (string) ($item['key'] ?? ''),
                'value' => (string) ($item['value'] ?? ''),
            ], $d['extraData'])),
        ];

        // ── Inclusión de extraDataCar si fue provisto ────────────────────────
        if (!empty($d['extraDataCar'])) {
            $payload['extraDataCar'] = [
                'brand'        => (string) ($d['extraDataCar']['brand'] ?? ''),
                'model'        => (string) ($d['extraDataCar']['model'] ?? ''),
                'type'         => (string) ($d['extraDataCar']['type'] ?? ''),
                'year'         => (string) ($d['extraDataCar']['year'] ?? ''),
                'plate'        => (string) ($d['extraDataCar']['plate'] ?? ''),
                'color'        => (string) ($d['extraDataCar']['color'] ?? ''),
                'serialNumber' => (string) ($d['extraDataCar']['serialNumber'] ?? ''),
                'engineNumber' => (string) ($d['extraDataCar']['engineNumber'] ?? ''),
            ];
        }

        // Si estamos en modo simulación (URL no configurada o mock activo), responde directamente como Swagger
        if ($this->shouldUseMock()) {
            return response()->json([
                'code'          => 200,
                'message'       => 'Success',
                'transactionId' => $d['transactionId'],
                '_simulado'     => true,
                '_payload'      => $payload,
            ], 200);
        }

        return $this->proxy(
            fn () => $this->api('post', '/assistance/sales/transaction', $payload)
        );
    }

    /* ------------------------------------------------------------------ */
    /*  INFRAESTRUCTURA PRIVADA & FALLBACKS                              */
    /* ------------------------------------------------------------------ */

    /**
     * Comprueba si se debe usar el modo simulación.
     * Se activa si la URL o las credenciales están vacías o son de ejemplo.
     */
    private function shouldUseMock(): bool
    {
        $url   = (string) config('services.seguro.url');
        $email = (string) config('services.seguro.email');
        $pwd   = (string) config('services.seguro.password');

        return empty($url)
            || str_contains($url, 'tu-url-de-la-api.com')
            || empty($email)
            || str_contains($email, 'tu_usuario')
            || str_contains($email, 'ejemplo.com')
            || empty($pwd)
            || str_contains($pwd, 'tu_contraseña')
            || str_contains($pwd, 'tu_password')
            || config('services.seguro.mock') === true;
    }

    private function mockPaises(): array
    {
        return [
            ['id' => 'GT', 'countryId' => 'GT', 'name' => 'Guatemala', 'code' => 'GT'],
            ['id' => 'SV', 'countryId' => 'SV', 'name' => 'El Salvador', 'code' => 'SV'],
        ];
    }

    private function mockEstados(string $countryId): array
    {
        $country = strtoupper(trim($countryId));

        if ($country === 'SV' || $country === '2') {
            return [
                ['id' => 1, 'name' => 'San Salvador'],
                ['id' => 2, 'name' => 'Santa Ana'],
                ['id' => 3, 'name' => 'San Miguel'],
                ['id' => 4, 'name' => 'La Libertad'],
                ['id' => 5, 'name' => 'Sonsonate'],
                ['id' => 6, 'name' => 'Usulután'],
                ['id' => 7, 'name' => 'Ahuachapán'],
                ['id' => 8, 'name' => 'La Paz'],
                ['id' => 9, 'name' => 'Cabañas'],
                ['id' => 10, 'name' => 'Chalatenango'],
                ['id' => 11, 'name' => 'Cuscatlán'],
                ['id' => 12, 'name' => 'Morazán'],
                ['id' => 13, 'name' => 'San Vicente'],
                ['id' => 14, 'name' => 'La Unión'],
            ];
        }

        // Default: Guatemala (GT o 1) con los 22 departamentos oficiales de Guatemala
        return [
            ['id' => 1, 'name' => 'Guatemala'],
            ['id' => 2, 'name' => 'Sacatepéquez'],
            ['id' => 3, 'name' => 'Chimaltenango'],
            ['id' => 4, 'name' => 'El Progreso'],
            ['id' => 5, 'name' => 'Escuintla'],
            ['id' => 6, 'name' => 'Santa Rosa'],
            ['id' => 7, 'name' => 'Sololá'],
            ['id' => 8, 'name' => 'Totonicapán'],
            ['id' => 9, 'name' => 'Quetzaltenango'],
            ['id' => 10, 'name' => 'Suchitepéquez'],
            ['id' => 11, 'name' => 'Retalhuleu'],
            ['id' => 12, 'name' => 'San Marcos'],
            ['id' => 13, 'name' => 'Huehuetenango'],
            ['id' => 14, 'name' => 'Quiché'],
            ['id' => 15, 'name' => 'Baja Verapaz'],
            ['id' => 16, 'name' => 'Alta Verapaz'],
            ['id' => 17, 'name' => 'Petén'],
            ['id' => 18, 'name' => 'Izabal'],
            ['id' => 19, 'name' => 'Zacapa'],
            ['id' => 20, 'name' => 'Chiquimula'],
            ['id' => 21, 'name' => 'Jalapa'],
            ['id' => 22, 'name' => 'Jutiapa'],
        ];
    }

    private function mockDistritos(string $country, int $stateId): array
    {
        $c = strtoupper(trim($country));

        // ── EL SALVADOR: Municipios / Distritos ──
        if ($c === 'SV' || $c === '2') {
            $distritosSV = [
                // 1: San Salvador
                1 => [
                    ['id' => 1, 'name' => 'San Salvador Centro'],
                    ['id' => 2, 'name' => 'Soyapango'],
                    ['id' => 3, 'name' => 'Ilopango'],
                    ['id' => 4, 'name' => 'Mejicanos'],
                    ['id' => 5, 'name' => 'Apopa'],
                    ['id' => 6, 'name' => 'Ciudad Delgado'],
                    ['id' => 7, 'name' => 'San Marcos'],
                    ['id' => 8, 'name' => 'Cuscatancingo'],
                    ['id' => 9, 'name' => 'San Martín'],
                    ['id' => 10, 'name' => 'Ayutuxtepeque'],
                    ['id' => 11, 'name' => 'Tonacatepeque'],
                    ['id' => 12, 'name' => 'Guazapa'],
                    ['id' => 13, 'name' => 'Nejapa'],
                    ['id' => 14, 'name' => 'Panchimalco'],
                ],
                // 2: Santa Ana
                2 => [
                    ['id' => 1, 'name' => 'Santa Ana Centro'],
                    ['id' => 2, 'name' => 'Chalchuapa'],
                    ['id' => 3, 'name' => 'Metapán'],
                    ['id' => 4, 'name' => 'Coatepeque'],
                    ['id' => 5, 'name' => 'El Congo'],
                    ['id' => 6, 'name' => 'Candelaria de la Frontera'],
                    ['id' => 7, 'name' => 'Texistepeque'],
                ],
                // 3: San Miguel
                3 => [
                    ['id' => 1, 'name' => 'San Miguel Centro'],
                    ['id' => 2, 'name' => 'Chinameca'],
                    ['id' => 3, 'name' => 'Ciudad Barrios'],
                    ['id' => 4, 'name' => 'Moncagua'],
                    ['id' => 5, 'name' => 'Nueva Guadalupe'],
                    ['id' => 6, 'name' => 'Sesori'],
                    ['id' => 7, 'name' => 'Uluazapa'],
                ],
                // 4: La Libertad
                4 => [
                    ['id' => 1, 'name' => 'Santa Tecla (Sur)'],
                    ['id' => 2, 'name' => 'Antiguo Cuscatlán (Este)'],
                    ['id' => 3, 'name' => 'Colón (Oeste)'],
                    ['id' => 4, 'name' => 'San Juan Opico'],
                    ['id' => 5, 'name' => 'Ciudad Arce'],
                    ['id' => 6, 'name' => 'Quezaltepeque (Norte)'],
                    ['id' => 7, 'name' => 'Puerto de La Libertad (Costa)'],
                    ['id' => 8, 'name' => 'Zaragoza'],
                ],
                // 5: Sonsonate
                5 => [
                    ['id' => 1, 'name' => 'Sonsonate Centro'],
                    ['id' => 2, 'name' => 'Acajutla'],
                    ['id' => 3, 'name' => 'Izalco'],
                    ['id' => 4, 'name' => 'Nahuizalco'],
                    ['id' => 5, 'name' => 'Juayúa'],
                    ['id' => 6, 'name' => 'Armenia'],
                    ['id' => 7, 'name' => 'Sonzacate'],
                ],
            ];

            return $distritosSV[$stateId] ?? [
                ['id' => 1, 'name' => 'Distrito Centro / Cabecera'],
                ['id' => 2, 'name' => 'Distrito Norte'],
                ['id' => 3, 'name' => 'Distrito Sur'],
                ['id' => 4, 'name' => 'Distrito Este'],
                ['id' => 5, 'name' => 'Distrito Oeste'],
            ];
        }

        // ── GUATEMALA: Municipios oficiales por departamento ──
        $distritosGT = [
            // 1: Guatemala
            1 => [
                ['id' => 1, 'name' => 'Guatemala (Ciudad Capital)'],
                ['id' => 2, 'name' => 'Santa Catarina Pinula'],
                ['id' => 3, 'name' => 'San José Pinula'],
                ['id' => 4, 'name' => 'San José del Golfo'],
                ['id' => 5, 'name' => 'Palencia'],
                ['id' => 6, 'name' => 'Chinautla'],
                ['id' => 7, 'name' => 'San Pedro Ayampuc'],
                ['id' => 8, 'name' => 'Mixco'],
                ['id' => 9, 'name' => 'San Pedro Sacatepéquez'],
                ['id' => 10, 'name' => 'San Juan Sacatepéquez'],
                ['id' => 11, 'name' => 'San Raymundo'],
                ['id' => 12, 'name' => 'Chuarrancho'],
                ['id' => 13, 'name' => 'Fraijanes'],
                ['id' => 14, 'name' => 'Amatitlán'],
                ['id' => 15, 'name' => 'Villa Nueva'],
                ['id' => 16, 'name' => 'Villa Canales'],
                ['id' => 17, 'name' => 'San Miguel Petapa'],
            ],
            // 2: Sacatepéquez
            2 => [
                ['id' => 1, 'name' => 'Antigua Guatemala'],
                ['id' => 2, 'name' => 'Jocotenango'],
                ['id' => 3, 'name' => 'Pastores'],
                ['id' => 4, 'name' => 'Sumpango'],
                ['id' => 5, 'name' => 'Santo Domingo Xenacoj'],
                ['id' => 6, 'name' => 'Santiago Sacatepéquez'],
                ['id' => 7, 'name' => 'San Bartolomé Milpas Altas'],
                ['id' => 8, 'name' => 'San Lucas Sacatepéquez'],
                ['id' => 9, 'name' => 'Santa Lucía Milpas Altas'],
                ['id' => 10, 'name' => 'Magdalena Milpas Altas'],
                ['id' => 11, 'name' => 'Santa María de Jesús'],
                ['id' => 12, 'name' => 'Ciudad Vieja'],
                ['id' => 13, 'name' => 'San Miguel Dueñas'],
                ['id' => 14, 'name' => 'Alotenango'],
                ['id' => 15, 'name' => 'San Antonio Aguas Calientes'],
                ['id' => 16, 'name' => 'Santa Catarina Barahona'],
            ],
            // 3: Chimaltenango
            3 => [
                ['id' => 1, 'name' => 'Chimaltenango'],
                ['id' => 2, 'name' => 'San José Poaquil'],
                ['id' => 3, 'name' => 'San Martín Jilotepeque'],
                ['id' => 4, 'name' => 'Comalapa'],
                ['id' => 5, 'name' => 'Santa Apolonia'],
                ['id' => 6, 'name' => 'Tecpán Guatemala'],
                ['id' => 7, 'name' => 'Patzún'],
                ['id' => 8, 'name' => 'Pochuta'],
                ['id' => 9, 'name' => 'Patzicía'],
                ['id' => 10, 'name' => 'Santa Cruz Balanyá'],
                ['id' => 11, 'name' => 'Acatenango'],
                ['id' => 12, 'name' => 'Yepocapa'],
                ['id' => 13, 'name' => 'San Andrés Itzapa'],
                ['id' => 14, 'name' => 'Parramos'],
                ['id' => 15, 'name' => 'Zaragoza'],
                ['id' => 16, 'name' => 'El Tejar'],
            ],
            // 4: El Progreso
            4 => [
                ['id' => 1, 'name' => 'Guastatoya'],
                ['id' => 2, 'name' => 'Morazán'],
                ['id' => 3, 'name' => 'San Agustín Acasaguastlán'],
                ['id' => 4, 'name' => 'San Cristóbal Acasaguastlán'],
                ['id' => 5, 'name' => 'El Jícaro'],
                ['id' => 6, 'name' => 'Sansare'],
                ['id' => 7, 'name' => 'Sanarate'],
                ['id' => 8, 'name' => 'San Antonio La Paz'],
            ],
            // 5: Escuintla
            5 => [
                ['id' => 1, 'name' => 'Escuintla'],
                ['id' => 2, 'name' => 'Santa Lucía Cotzumalguapa'],
                ['id' => 3, 'name' => 'La Democracia'],
                ['id' => 4, 'name' => 'Siquinalá'],
                ['id' => 5, 'name' => 'Masagua'],
                ['id' => 6, 'name' => 'Tiquisate'],
                ['id' => 7, 'name' => 'La Gomera'],
                ['id' => 8, 'name' => 'Guanagazapa'],
                ['id' => 9, 'name' => 'San José'],
                ['id' => 10, 'name' => 'Iztapa'],
                ['id' => 11, 'name' => 'Palín'],
                ['id' => 12, 'name' => 'San Vicente Pacaya'],
                ['id' => 13, 'name' => 'Nueva Concepción'],
                ['id' => 14, 'name' => 'Sipacate'],
            ],
            // 9: Quetzaltenango
            9 => [
                ['id' => 1, 'name' => 'Quetzaltenango'],
                ['id' => 2, 'name' => 'Salcajá'],
                ['id' => 3, 'name' => 'Olintepeque'],
                ['id' => 4, 'name' => 'San Carlos Sija'],
                ['id' => 5, 'name' => 'Sibilia'],
                ['id' => 6, 'name' => 'Cabricán'],
                ['id' => 7, 'name' => 'Cajolá'],
                ['id' => 8, 'name' => 'San Miguel Sigüilá'],
                ['id' => 9, 'name' => 'Ostuncalco'],
                ['id' => 10, 'name' => 'San Mateo'],
                ['id' => 11, 'name' => 'Almolonga'],
                ['id' => 12, 'name' => 'Cantel'],
                ['id' => 13, 'name' => 'Zunil'],
                ['id' => 14, 'name' => 'Colomba'],
                ['id' => 15, 'name' => 'Coatepeque'],
                ['id' => 16, 'name' => 'La Esperanza'],
            ],
        ];

        return $distritosGT[$stateId] ?? [
            ['id' => 1, 'name' => 'Cabecera Municipal'],
            ['id' => 2, 'name' => 'Distrito Norte'],
            ['id' => 3, 'name' => 'Distrito Sur'],
            ['id' => 4, 'name' => 'Distrito Central'],
            ['id' => 5, 'name' => 'Distrito Oriente'],
        ];
    }

    /**
     * Obtiene (o refresca) el Bearer token del endpoint de login.
     * Lo cachea 50 minutos para no re-autenticar en cada petición.
     */
    private function token(bool $refresh = false): string
    {
        if ($refresh) {
            Cache::forget(self::TOKEN_KEY);
        }

        return Cache::remember(self::TOKEN_KEY, now()->addMinutes(50), function () {
            $res = Http::acceptJson()->timeout(20)->post($this->url('/ext/insurance/token'), [
                'email'    => config('services.seguro.email'),
                'password' => config('services.seguro.password'),
            ]);

            if ($res->failed()) {
                $motivo = $res->json('message') ?? $res->json('error') ?? '';
                if ($res->status() === 404 || strtolower((string)$motivo) === 'not found') {
                    $motivo = 'Usuario no encontrado en el servidor Apolo (el email o contraseña no existen en la base de datos de la API)';
                }
                throw new RuntimeException(
                    'Error de autenticación con la API externa' . ($motivo ? ': ' . $motivo : '.')
                );
            }

            $json  = $res->json();
            $token = is_array($json)
                ? ($json['token'] ?? $json['access_token']
                    ?? data_get($json, 'data.token')
                    ?? data_get($json, 'data.access_token'))
                : trim($res->body(), "\" \n\r");

            if (! $token) {
                throw new RuntimeException('La respuesta del login no contiene un token válido.');
            }

            return $token;
        });
    }

    /**
     * Ejecuta la petición HTTP con token Bearer.
     * Si recibe 401 refresca el token y reintenta una vez.
     */
    private function api(string $method, string $path, ?array $data = null): Response
    {
        $send = fn (string $token) => Http::withToken($token)
            ->acceptJson()
            ->timeout(30)
            ->{$method}($this->url($path), $data ?? []);

        $res = $send($this->token());

        if ($res->status() === 401) {
            $res = $send($this->token(refresh: true));
        }

        return $res;
    }

    /**
     * Construye la URL base a partir de la variable de entorno SEGURO_API_URL.
     */
    private function url(string $path): string
    {
        $base = (string) config('services.seguro.url');
        return rtrim($base, '/') . '/' . ltrim($path, '/');
    }

    /**
     * Centraliza el manejo de errores y evita filtrar stack traces al cliente.
     */
    private function proxy(callable $call): JsonResponse
    {
        try {
            /** @var Response $res */
            $res = $call();

            $payload = $res->json() ?? ['message' => $res->body()];

            if ($res->failed() && empty($payload['message'])) {
                $payload['message'] = 'Servicio de asistencias fuera de línea (HTTP ' . $res->status() . ').';
            }

            return response()->json($payload, $res->status());

        } catch (ConnectionException $e) {
            report($e);
            return response()->json([
                'message' => 'No fue posible conectar con el servidor de asistencias. Verifica que SEGURO_API_URL esté correctamente configurada en tu .env.',
            ], 502);
        } catch (RuntimeException $e) {
            report($e);
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}
