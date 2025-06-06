<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use App\Models\Cliente;
use App\Models\Llamada;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Verificar si el usuario ya existe antes de crearlo
        if (!User::where('email', 'test@example.com')->exists()) {
            User::factory()->create([
                'name' => 'Test User',
                'email' => 'test@example.com',
            ]);
        }

        $this->call([
            PaisSeeder::class,
            ClienteSeeder::class,
            LlamadaSeeder::class,
            PagoMensualSeeder::class,
        ]);

        // Crear clientes de prueba
        Cliente::factory()->count(5)->create()->each(function ($cliente) {
            // Crear llamadas asociadas a cada cliente
            Llamada::factory()->count(3)->create([
                'numero_origen' => $cliente->telefono,
                'numero_destino' => '555-1234',
                'precio' => rand(1, 10),
                'es_tele_seleccion' => rand(0, 1) === 1, // Poblar la columna con valores aleatorios
            ]);
        });
    }
}
