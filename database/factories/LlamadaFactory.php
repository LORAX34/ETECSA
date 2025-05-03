<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;
use App\Models\Cliente;

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

        // Obtener un cliente aleatorio para el número de origen
        $clienteOrigen = Cliente::inRandomOrder()->first();

        // Si no hay clientes, crear uno nuevo
        if (!$clienteOrigen) {
            $clienteOrigen = Cliente::factory()->create();
        }

        // Para destino nacional, obtener otro cliente diferente al de origen
        $clienteDestinoNacional = null;
        if ($esNacional) {
            $clienteDestinoNacional = Cliente::where('id', '!=', $clienteOrigen->id)
                ->inRandomOrder()
                ->first();

            // Si no hay otro cliente, crear uno nuevo
            if (!$clienteDestinoNacional) {
                $clienteDestinoNacional = Cliente::factory()->create();
            }
        }

        return [
            'minutos' => $esServicio ? 0 : $this->faker->numberBetween(1, 60),
            'fecha_hora' => $fechaHora,
            'numero_origen' => $clienteOrigen->telefono, // Usar teléfono del cliente origen
            'numero_destino' => $this->generateNumeroDestino($esInternacional, $esNacional, $esServicio, $clienteDestinoNacional),
            'es_internacional' => $esInternacional,
            'es_nacional' => $esNacional,
            'es_local' => $esLocal,
            'es_servicio' => $esServicio,
            'cod_local_origen' => $this->faker->numerify('##'), // Código localidad 2 dígitos
            'cod_local_dest' => $esNacional ? $this->faker->numerify('##') : null,
            'cod_pais_dest' => $esInternacional ? $this->faker->numerify('##') : null, // Código país 2 dígitos
            'tarifa_pais' => $esInternacional ? $this->faker->randomFloat(2, 0.10, 5.00) : null,
            'cliente_id' => $clienteOrigen->id, // Relación con el cliente
            'precio' => function (array $attributes) use ($nocturna) {
                return $this->calculatePrecio($attributes, $nocturna);
            },
        ];
    }

    private function generateNumeroDestino($esInternacional, $esNacional, $esServicio, $clienteDestinoNacional = null)
    {
        if ($esServicio) {
            return '*-' . $this->faker->numerify('###-####'); // Formato *-077-2244
        }

        if ($esInternacional) {
            return '0-' . $this->faker->numerify('##-##-#######'); // Formato 0-04-35-8819395
        }

        if ($esNacional) {
            // 50% de usar número de cliente destino o generar uno aleatorio
            return $this->faker->boolean(50)
                ? $clienteDestinoNacional->telefono
                : $this->faker->numerify('###-5#######'); // Nacional con código localidad
        }

        // Local: 5% probabilidad de ser a línea arrendada (777777)
        return $this->faker->boolean(5) ? '777777' : $this->faker->numerify('5#######');
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
