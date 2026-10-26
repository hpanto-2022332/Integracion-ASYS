<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsuarioPlanesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       // 1. Creamos un usuario de prueba en tu sistema real si no existe
        $userId = DB::table('users')->insertGetId([
            'name' => 'Harold Panto',
            'email' => 'harold@empresa.com',
            'password' => Hash::make('12345678'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 2. Le asignamos su plan de asistencia autorizado en la tabla que creamos
        DB::table('usuario_planes')->insert([
            'user_id' => $userId,
            'assistance_plan_id' => 1231,
            'nombre_plan' => 'Plan Dental Premium Corporativo',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
