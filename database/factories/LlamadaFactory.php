<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;

class LlamadaFactory extends Factory
{
    public function definition()
    {
        $esServicio = $this->faker->boolean(5); // 5% probabilidad de ser servicio matutino
        $esInternacional = !$esServicio && $this->faker->boolean(15); // 15% probabilidad
        $esNacional = !$esServicio && !$esInternacional && $this->faker->boolean(40);
        $esLocal = !$esServicio && !$esInternacional && !$esNacional;

        $fechaHora = $this->faker->dateTimeBetween('-6 months', 'now');
        $hora = Carbon::parse($fechaHora)->hour;
        $nocturna = $hora >= 23 || $hora < 5;

        return [
            'minutos' => $esServicio ? 0 : $this->faker->numberBetween(1, 60),
            'fecha_hora' => $fechaHora,
            'numero_origen' => $this->faker->numerify('5#######'), // Número cubano
            'numero_destino' => $this->generateNumeroDestino($esInternacional, $esNacional, $esServicio),
            'es_internacional' => $esInternacional,
            'es_nacional' => $esNacional,
            'es_local' => $esLocal,
            'es_servicio' => $esServicio,
            'cod_local_origen' => $this->faker->numerify('##'), // Código localidad 2 dígitos
            'cod_local_dest' => $esNacional ? $this->faker->numerify('##') : null,
            'cod_pais_dest' => $esInternacional ? $this->faker->numerify('##') : null, // Código país 2 dígitos
            'tarifa_pais' => $esInternacional ? $this->faker->randomFloat(2, 0.10, 5.00) : null,
            'precio' => function (array $attributes) use ($nocturna) {
                return $this->calculatePrecio($attributes, $nocturna);
            },
        ];
    }

    private function generateNumeroDestino($esInternacional, $esNacional, $esServicio)
    {
        if ($esServicio) {
            return '*-' . $this->faker->numerify('###-####'); // Formato *-077-2244
        }

        if ($esInternacional) {
            return '0-' . $this->faker->numerify('##-##-#######'); // Formato 0-04-35-8819395
        }

        if ($esNacional) {
            return $this->faker->numerify('###-#######'); // Nacional con código localidad
        }

        // Local: 50% probabilidad de ser a línea arrendada (777777)
        return $this->faker->boolean(5) ? '777777' : $this->faker->numerify('#######');
    }

    private function calculatePrecio($attributes, $nocturna)
    {
        if ($attributes['es_servicio']) {
            return 0; // Servicios matutinos no tienen costo en llamada
        }

        if ($attributes['es_internacional']) {
            $adicional = $nocturna ? 0 : 0.50;
            return $attributes['minutos'] * ($attributes['tarifa_pais'] + $adicional);
        }

        if ($attributes['es_nacional']) {
            $diferenciaLocalidad = abs(
                (int)$attributes['cod_local_origen'] - (int)$attributes['cod_local_dest']
            );
            $adicional = $nocturna ? 0 : 0.50;
            return $attributes['minutos'] * ($diferenciaLocalidad + $adicional);
        }

        // Local
        if ($attributes['numero_destino'] === '777777') {
            return $attributes['minutos'] * 1.00; // Línea arrendada
        }

        return $attributes['minutos'] * (0.05 / 3); // Llamada local normal
    }
}
