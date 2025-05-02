<?php

namespace Database\Seeders;

use App\Models\Cliente;
use Illuminate\Database\Seeder;

class ClienteSeeder extends Seeder
{
    public function run()
    {
        // Crear clientes estatales (empresas)
        Cliente::factory()
            ->count(15)
            ->state(['tipo' => 'ESTATAL'])
            ->create();

        // Crear clientes residenciales
        Cliente::factory()
            ->count(85)
            ->state(['tipo' => 'RESIDENCIAL'])
            ->create();
    }
}
