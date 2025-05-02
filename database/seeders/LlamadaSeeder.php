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
        $paises = Pais::all();

        foreach ($clientes as $cliente) {
            // Crear entre 5 y 30 llamadas por cliente
            $llamadas = Llamada::factory()
                ->count(rand(5, 30))
                ->create([
                    'cliente_id' => $cliente->id,
                    'cod_local_origen' => rand(1, 99), // Código localidad aleatorio
                ]);

            // Para algunas llamadas de servicio, crear registro de servicio matutino
            foreach ($llamadas->where('es_servicio', true) as $llamadaServicio) {
                ServicioMatutino::factory()->create([
                    'llamada_id' => $llamadaServicio->id,
                    'hora_programada' => $llamadaServicio->fecha_hora->format('H:i'),
                ]);
            }
        }
    }
}
