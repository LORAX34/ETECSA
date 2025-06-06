<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;
use App\Models\Cliente;

class LlamadaFactory extends Factory
{
    protected $model = \App\Models\Llamada::class;

    public function definition()
    {
        return [
            'minutos' => $this->faker->numberBetween(1, 60),
            'fecha_hora' => $this->faker->dateTimeBetween('-6 months', 'now'),
            'numero_origen' => $this->faker->numerify('5#######'),
            'numero_destino' => $this->faker->numerify('5#######'),
            'es_internacional' => $this->faker->boolean(20), // 20% probabilidad de ser internacional
            'es_nacional' => $this->faker->boolean(50), // 50% probabilidad de ser nacional
            'es_local' => $this->faker->boolean(30), // 30% probabilidad de ser local
            'es_servicio' => $this->faker->boolean(10), // 10% probabilidad de ser servicio
            'cod_local_origen' => $this->faker->numerify('##'),
            'cod_local_dest' => $this->faker->numerify('##'),
            'cod_pais_dest' => $this->faker->numerify('##'),
            'tarifa_pais' => $this->faker->randomFloat(2, 0.10, 5.00),
            'precio' => $this->faker->randomFloat(2, 1.00, 100.00),
            'cliente_id' => Cliente::factory(),
            'es_tele_seleccion' => $this->faker->boolean,
        ];
    }
}
