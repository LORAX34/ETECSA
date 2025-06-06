<?php

namespace Database\Seeders;

use App\Models\Cliente;
use App\Models\Llamada;
use App\Models\Pais;
use App\Models\ServicioMatutino;
use Illuminate\Database\Seeder;

class LlamadaSeeder extends Seeder
{
    public function run()
    {
        $clientes = Cliente::where('activo', true)->get();

        foreach ($clientes as $cliente) {
            // Crear entre 10 y 50 llamadas por cliente
            Llamada::factory()
                ->count(rand(10, 50))
                ->create([
                    'cliente_id' => $cliente->id,
                    'numero_origen' => $cliente->telefono, // Usar el teléfono del cliente como origen
                    'numero_destino' => function () use ($clientes, $cliente) {
                        // Seleccionar un número de destino aleatorio de otro cliente
                        $destino = $clientes->where('id', '!=', $cliente->id)->random();
                        return $destino->telefono;
                    },
                ]);
        }
    }
}
