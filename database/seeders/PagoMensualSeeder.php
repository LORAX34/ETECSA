<?php

namespace Database\Seeders;

use App\Models\Cliente;
use App\Models\PagoMensual;
use Illuminate\Database\Seeder;

class PagoMensualSeeder extends Seeder
{
    public function run()
    {
        $clientes = Cliente::all();

        foreach ($clientes as $cliente) {
            // Crear pagos para los últimos 6 meses
            for ($i = 1; $i <= 6; $i++) {
                $tieneServicios = ($cliente->matutino ? 1 : 0) + ($cliente->rastreo ? 1 : 0);

                PagoMensual::factory()->create([
                    'cliente_id' => $cliente->id,
                    'mes' => now()->subMonths($i)->month,
                    'año' => now()->subMonths($i)->year,
                    'total_servicios' => $tieneServicios,
                    'total' => function ($attributes) {
                        return $attributes['total_llamadas'] + $attributes['total_servicios'];
                    },
                ]);
            }
        }
    }
}
