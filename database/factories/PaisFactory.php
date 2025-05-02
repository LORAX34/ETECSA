<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class PaisFactory extends Factory
{
    public function definition()
    {
        return [
            'codigo_pais' => $this->faker->unique()->numerify('##'), // Códigos de 2 dígitos
            'nombre' => $this->faker->country,
            'tarifa' => $this->faker->randomFloat(2, 0.10, 5.00), // Tarifas realistas
        ];
    }
}
