<?php

namespace Database\Seeders;

use App\Models\Pais;
use Illuminate\Database\Seeder;

class PaisSeeder extends Seeder
{
    public function run()
    {

        Pais::truncate();
        $paises = [
            ['codigo_pais' => '01', 'nombre' => 'Estados Unidos', 'tarifa' => 2.50],
            ['codigo_pais' => '53', 'nombre' => 'México', 'tarifa' => 1.20],
            ['codigo_pais' => '34', 'nombre' => 'España', 'tarifa' => 1.80],
            ['codigo_pais' => '58', 'nombre' => 'Venezuela', 'tarifa' => 0.90],
            ['codigo_pais' => '52', 'nombre' => 'Canadá', 'tarifa' => 2.00],
            ['codigo_pais' => '54', 'nombre' => 'Argentina', 'tarifa' => 1.50],
            ['codigo_pais' => '55', 'nombre' => 'Brasil', 'tarifa' => 1.30],
        ];

        foreach ($paises as $pais) {
            Pais::create($pais);
        }

        // Crear algunos países adicionales
        $codigosUsados = collect($paises)->pluck('codigo_pais')->toArray();

        Pais::factory()
            ->count(5)
            ->create([
                'codigo_pais' => function() use (&$codigosUsados) {
                    do {
                        $codigo = str_pad(rand(10, 99), 2, '0', STR_PAD_LEFT);
                    } while(in_array($codigo, $codigosUsados));

                    $codigosUsados[] = $codigo;
                    return $codigo;
                }
            ]);
    }
}
