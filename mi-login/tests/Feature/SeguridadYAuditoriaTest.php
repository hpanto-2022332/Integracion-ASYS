<?php

namespace Tests\Feature;

use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class SeguridadYAuditoriaTest extends TestCase
{
    use RefreshDatabase; // 🛡️ Limpia y reestructura la BD en memoria para cada prueba

    private User $admin;
    private User $cliente;

    protected function setUp(): void
    {
        parent::setUp();

        // 1. Instanciar los roles necesarios para el ecosistema Avanta
        $roleAdmin = Role::create(['name' => 'admin']);
        $roleCliente = Role::create(['name' => 'cliente']);

        // 2. Crear usuario Administrador de prueba
        $this->admin = User::create([
            'name' => 'Harold QA Admin',
            'email' => 'harold.qa@avanta.com',
            'password' => Hash::make('Avanta2026*'),
            'pais' => 'Guatemala'
        ]);
        $this->admin->assignRole($roleAdmin);

        // 3. Crear usuario Cliente de prueba
        $this->cliente = User::create([
            'name' => 'Luis QA Cliente',
            'email' => 'luis.qa@avanta.com',
            'password' => Hash::make('Cliente2026*'),
            'pais' => 'El Salvador'
        ]);
        $this->cliente->assignRole($roleCliente);
    }

    /**
     * 🧪 TEST 1: Valida que un cliente común sufra un bloqueo perimetral 403
     * si intenta inyectar la URL de administración en el navegador.
     */

        public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/');

        // 🛡️ Corregido: Ahora valida la redirección al login corporativo
        $response->assertStatus(302);
    }

    public function test_cliente_recibe_error_403_en_rutas_de_administrador(): void
    {
        $response = $this->actingAs($this->cliente)->get('/admin/usuarios');

        $response->assertStatus(403); // Valida el bloqueo de Spatie
    }

    /**
     * 🧪 TEST 2: Valida la redirección estratégica del Administrador
     * hacia su correspondiente Panel de Gestión tras autenticarse.
     */
    public function test_administrador_es_redirigido_a_su_panel_exclusivo(): void
    {
        $response = $this->post('/login', [
            'email' => 'harold.qa@avanta.com',
            'password' => 'Avanta2026*',
        ]);

        $response->assertRedirect('/admin/usuarios'); // Valida la redirección del AuthController
    }

    /**
     * 🧪 TEST 3: Valida que el Middleware de Auditoría esté activo
     * e indexe las transacciones y consultas SQL en la bitácora.
     */
    public function test_middleware_de_auditoria_registra_actividad_en_base_de_datos(): void
    {
        // Realizamos una navegación simulada estando logueados
        $this->actingAs($this->admin)->get('/admin/usuarios');

        // Comprobamos que el Middleware interceptó el flujo e insertó el registro de bitácora
        $this->assertDatabaseHas('auditoria_logs', [
            'user_id' => $this->admin->id,
            'usuario_nombre' => 'Harold QA Admin',
            'metodo' => 'GET'
        ]);
    }
}
