<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ClienteFactory extends Factory
{
    protected $model = \App\Models\Cliente::class;

    public function definition()
    {
        $tipo = $this->faker->randomElement(['RESIDENCIAL', 'ESTATAL']);

        return [
            'nombre' => $tipo === 'ESTATAL'
                ? $this->faker->company
                : $this->faker->name,
            'carnet' => $this->faker->unique()->regexify('[A-Z0-9]{8}'), // Número de carné cubano
            'direccion' => $this->faker->streetAddress . ', ' . $this->faker->city,
            'telefono' => $this->faker->unique()->numerify('5#######'), // Números cubanos
            'tipo' => $tipo, // Asegurarse de que sea 'RESIDENCIAL' o 'ESTATAL'
            'activo' => $this->faker->boolean(85), // 85% probabilidad de estar activo
            'moroso' => $this->faker->boolean(15), // 15% probabilidad de morosidad
            'matutino' => $tipo === 'RESIDENCIAL' ? $this->faker->boolean(20) : false,
            'rastreo' => $tipo === 'RESIDENCIAL' ? $this->faker->boolean(15) : false,
            'linea_arrendada' => $tipo === 'ESTATAL' ? $this->faker->boolean(35) : false,
        ];
    }
}
