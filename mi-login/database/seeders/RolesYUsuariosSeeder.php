<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RolesYUsuariosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Crear los roles institucionales
        $roleAdmin = Role::create(['name' => 'admin']);
        $roleCliente = Role::create(['name' => 'cliente']);

        // 2. Crear tu primer usuario Administrador del sistema (Guatemala)
        $admin = User::create([
            'name' => 'Harold Admin',
            'email' => 'harold.admin@avanta.com.gt',
            'password' => Hash::make('Avanta2026*'),
            'pais' => 'Guatemala', // ⬅️ Mapeado en la sede central
        ]);
        
        // Asignar el rol de administrador
        $admin->assignRole($roleAdmin);

        // 3. Crear un usuario de prueba con rol Cliente (El Salvador)
        $cliente = User::create([
            'name' => 'Luis Orellana Colaborador',
            'email' => 'luis.orellana@avanta.com.gt',
            'password' => Hash::make('Cliente2026*'),
            'pais' => 'El Salvador', // ⬅️ Mapeado en la sede regional internacional
        ]);

        // Asignar el rol de cliente
        $cliente->assignRole($roleCliente);
    }
}
