<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ServicioMatutinoFactory extends Factory
{
    public function definition()
    {
        return [
            'hora_programada' => $this->faker->time('H:i'),
            'codigo_funcion' => $this->faker->numerify('077'), // Código fijo para matutino
        ];
    }
}
