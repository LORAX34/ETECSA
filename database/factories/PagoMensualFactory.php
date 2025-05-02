<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class PagoMensualFactory extends Factory
{
    public function definition()
    {
        $mes = $this->faker->numberBetween(1, 12);
        $año = $this->faker->numberBetween(2022, 2023);
        $totalLlamadas = $this->faker->randomFloat(2, 10, 500);

        // Servicios adicionales (matutino y rastreo) cuestan 1 peso cada uno
        $totalServicios = $this->faker->randomFloat(2, 0, 2); // 0, 1 o 2 pesos

        return [
            'mes' => $mes,
            'año' => $año,
            'total_llamadas' => $totalLlamadas,
            'total_servicios' => $totalServicios,
            'total' => $totalLlamadas + $totalServicios,
        ];
    }
}
