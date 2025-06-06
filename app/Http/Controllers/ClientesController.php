<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cliente;
use App\Models\Llamada;
use Inertia\Inertia;

class ClientesController extends Controller
{
    // Mostrar la lista de clientes
    public function index()
    {
        $clientes = Cliente::all();
        return Inertia::render('Clientes/Gestion', ['clientes' => $clientes]);
    }

    // 1. Incluir un cliente en la central
    public function store(Request $request) // Cambiar el nombre del método de incluirCliente a store
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'carnet' => 'required|string|max:255|unique:clientes',
            'direccion' => 'required|string|max:255',
            'telefono' => 'required|string|max:15|unique:clientes,telefono',
            'tipo' => 'required|string|in:RESIDENCIAL,ESTATAL', // Asegurarse de que los valores sean válidos
            'activo' => 'required|boolean',
        ]);

        Cliente::create($validated);

        return redirect()->route('clientes')->with('success', 'Cliente incluido exitosamente.');
    }

    // 2. Dar de baja temporal a un cliente por morosidad
    public function darBajaPorMorosidad(Request $request, $id)
    {
        $cliente = Cliente::findOrFail($id);
        $estadoAnterior = $cliente->moroso;
        $cliente->moroso = $request->input('moroso', false); // Cambiar el estado de morosidad
        $cliente->save();

        $mensaje = $cliente->moroso
            ? "Cliente marcado como moroso y servicio desactivado temporalmente."
            : "Cliente removido de estado moroso.";

        return response()->json([
            'success' => true,
            'cliente' => $cliente,
            'mensaje' => $mensaje,
            'estado_anterior' => $estadoAnterior,
            'estado_actual' => $cliente->moroso,
            'servicio_activo' => $cliente->activo
        ]);
    }

    // 3. Calcular cuánto debe pagar un cliente en el mes
    public function calcularPagoMensual($id)
    {
        $cliente = Cliente::findOrFail($id);

        // Calcular costo adicional por servicios (1 peso por cada servicio)
        $serviciosAdicionales = [];
        $costoServicios = 0;

        if ($cliente->matutino) {
            $serviciosAdicionales[] = 'Matutino';
            $costoServicios += 1;
        }

        if ($cliente->rastreo) {
            $serviciosAdicionales[] = 'Rastreo';
            $costoServicios += 1;
        }

        // Calcular costo de llamadas
        $llamadas = Llamada::where('numero_origen', $cliente->telefono)->get();
        $totalLlamadas = $llamadas->sum('precio');

        // Sumar todos los costos
        $total = $costoServicios + $totalLlamadas;

        return response()->json([
            'cliente' => $cliente,
            'detalles' => [
                'servicios_adicionales' => $serviciosAdicionales,
                'costo_servicios' => $costoServicios,
                'costo_llamadas' => $totalLlamadas
            ],
            'total_a_pagar' => $total
        ]);
    }

    // 4. Información de llamadas realizadas a un cliente residencial con rastreo habilitado
    public function llamadasConRastreo($id)
    {
        $cliente = Cliente::where('id', $id)->where('tipo', 'RESIDENCIAL')->where('rastreo', true)->firstOrFail();
        $llamadas = Llamada::where('numero_destino', $cliente->telefono)->get();

        return response()->json(['cliente' => $cliente, 'llamadas' => $llamadas]);
    }

    // 5. Datos del cliente que más utilizó los servicios de la empresa
    public function clienteMasServicios()
    {
        // Obtener todos los clientes con el conteo de sus llamadas
        $clientes = Cliente::withCount('llamadas')->get();

        if ($clientes->isEmpty()) {
            return Inertia::render('Clientes/ClienteMasServicios', [
                'error' => 'No hay clientes registrados en el sistema.',
            ]);
        }

        // Calcular un puntaje de uso de servicios para cada cliente
        $clientesConPuntaje = $clientes->map(function ($cliente) {
            $puntajeServicios = 0;

            // Considerar servicios adicionales
            if ($cliente->matutino) $puntajeServicios += 10;
            if ($cliente->rastreo) $puntajeServicios += 10;
            if ($cliente->linea_arrendada) $puntajeServicios += 15;

            // Considerar llamadas
            $puntajeServicios += $cliente->llamadas_count * 5;

            return [
                'cliente' => $cliente,
                'puntaje_servicios' => $puntajeServicios,
            ];
        });

        // Ordenar por puntaje y obtener el cliente con mayor uso
        $clienteTop = $clientesConPuntaje->sortByDesc('puntaje_servicios')->first();

        return Inertia::render('Clientes/ClienteMasServicios', [
            'clienteTop' => [
                'cliente' => $clienteTop['cliente'],
                'detalles_uso' => [
                    'llamadas_realizadas' => $clienteTop['cliente']->llamadas_count,
                    'servicios_activos' => [
                        'matutino' => $clienteTop['cliente']->matutino,
                        'rastreo' => $clienteTop['cliente']->rastreo,
                        'linea_arrendada' => $clienteTop['cliente']->linea_arrendada,
                    ],
                    'puntaje_total' => $clienteTop['puntaje_servicios'],
                ],
            ],
        ]);
    }

    // 6. Listado de clientes que hicieron uso de la tele selección
    public function clientesTeleSeleccion()
    {
        $clientes = Cliente::whereHas('llamadas', function ($query) {
            $query->where('es_tele_seleccion', true);
        })->with(['llamadas' => function ($query) {
            $query->where('es_tele_seleccion', true);
        }])->get();

        $resultado = $clientes->map(function ($cliente) {
            $total = $cliente->llamadas->sum('precio');
            return [
                'id' => $cliente->id,
                'nombre' => $cliente->nombre,
                'telefono' => $cliente->telefono,
                'direccion' => $cliente->direccion,
                'tipo' => $cliente->tipo,
                'llamadas_teleseleccion' => $cliente->llamadas->count(),
                'total_cobrado' => $total,
            ];
        });

        $resultadoOrdenado = $resultado->sortByDesc('total_cobrado')->values();

        return inertia('Clientes/TeleSeleccion', [
            'clientes' => $resultadoOrdenado
        ]);
    }

    // Actualizar un cliente
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'carnet' => 'required|string|max:255|unique:clientes,carnet,' . $id,
            'direccion' => 'required|string|max:255',
            'telefono' => 'required|string|max:15|unique:clientes,telefono,' . $id,
            'tipo' => 'required|string|in:RESIDENCIAL,ESTATAL', // Asegurarse de que los valores sean válidos
            'activo' => 'required|boolean',
        ]);

        $cliente = Cliente::findOrFail($id);
        $cliente->update($validated);

        return redirect()->route('clientes')->with('success', 'Cliente actualizado exitosamente.');
    }

    // Eliminar un cliente
    public function destroy($id)
    {
        $cliente = Cliente::findOrFail($id);
        $cliente->delete();

        return redirect()->route('clientes')->with('success', 'Cliente eliminado exitosamente.');
    }

    // Verificar el controlador `ClientesController`
    public function pagosMensuales()
    {
        $clientes = Cliente::with('pagosMensuales')->get()->map(function ($cliente) {
            $montoPagar = $cliente->pagosMensuales->sum('total');
            return [
                'id' => $cliente->id,
                'nombre' => $cliente->nombre,
                'telefono' => $cliente->telefono,
                'activo' => $cliente->activo,
                'moroso' => $cliente->moroso,
                'monto_pagar' => $montoPagar,
            ];
        });

        return inertia('Clientes/PagoMensual', ['clientes' => $clientes]);
    }

    public function llamadasRecibidas(Request $request)
    {
        $telefono = $request->query('telefono');

        if (!$telefono) {
            return Inertia::render('Clientes/LlamadasRastreo', [
                'error' => 'El número de teléfono es requerido.',
                'cliente' => null,
                'llamadas' => [],
            ]);
        }

        $cliente = Cliente::where('telefono', $telefono)
            ->where('tipo', 'RESIDENCIAL')
            ->where('rastreo', true)
            ->first();

        if (!$cliente) {
            return Inertia::render('Clientes/LlamadasRastreo', [
                'error' => 'No se encontró un cliente residencial con rastreo habilitado.',
                'cliente' => null,
                'llamadas' => [],
            ]);
        }

        $llamadas = Llamada::join('clientes as llamador', 'llamadas.numero_origen', '=', 'llamador.telefono')
            ->where('llamadas.numero_destino', $telefono)
            ->select(
                'llamadas.numero_origen',
                'llamador.nombre as nombre_llamador',
                'llamador.direccion as direccion_llamador',
                'llamadas.fecha_hora',
                'llamadas.precio'
            )
            ->get();

        return Inertia::render('Clientes/LlamadasRastreo', [
            'error' => null,
            'cliente' => $cliente,
            'llamadas' => $llamadas,
        ]);
    }
}
