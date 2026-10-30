<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Database\Seeders\RolesYUsuariosSeeder;
use Database\Seeders\UsuarioPlanesSeeder;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RolesYUsuariosSeeder::class,
            UsuarioPlanesSeeder::class,
        ]);
    }
}
