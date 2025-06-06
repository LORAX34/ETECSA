<?php

namespace Database\Seeders;

use App\Models\Cliente;
use App\Models\Llamada;
use Illuminate\Database\Seeder;

class LlamadaSeeder extends Seeder
{
    public function run()
    {
        // Crear clientes de prueba
        $clientes = Cliente::factory()->count(5)->create();

        foreach ($clientes as $cliente) {
            // Crear llamadas recibidas para cada cliente
            Llamada::factory()->count(3)->create([
                'numero_destino' => $cliente->telefono, // Número destino es el teléfono del cliente
                'numero_origen' => '5551234', // Número origen fijo para pruebas
                'precio' => rand(1, 10),
            ]);
        }

        // Crear llamadas adicionales para pruebas
        Llamada::factory()->create([
            'numero_destino' => '5555678', // Número destino específico para pruebas
            'numero_origen' => '5554321',
            'precio' => 5.50,
        ]);
    }
}
