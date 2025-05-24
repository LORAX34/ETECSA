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
            ->state(['tipo' => 'ESTATAL']) // Asegurarse de que sea 'ESTATAL'
            ->create();

        // Crear clientes residenciales
        Cliente::factory()
            ->count(85)
            ->state(['tipo' => 'RESIDENCIAL']) // Asegurarse de que sea 'RESIDENCIAL'
            ->create();
    }
}
