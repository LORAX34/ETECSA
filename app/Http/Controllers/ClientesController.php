<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cliente;
use App\Models\Llamada;

class ClientesController extends Controller
{
    // Mostrar la lista de clientes
    public function index()
    {
        $clientes = Cliente::all();
        return inertia('Dashboard', ['clientes' => $clientes]);
    }

    // 1. Incluir un cliente en la central
    public function incluirCliente(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'carnet' => 'required|string|max:255|unique:clientes',
            'direccion' => 'required|string|max:255',
            'telefono' => 'required|string|max:15',
            'tipo' => 'required|string|in:RESIDENCIAL,ESTATAL', // Asegurarse de que los valores sean válidos
            'activo' => 'required|boolean',
        ]);

        $validated['tipo'] = strtoupper($validated['tipo']); // Convertir a mayúsculas

        Cliente::create($validated);

        return redirect()->route('clientes')->with('success', 'Cliente incluido exitosamente.');
    }

    // 2. Dar de baja temporal a un cliente por morosidad
    public function darBajaPorMorosidad($id)
    {
        $cliente = Cliente::findOrFail($id);
        $cliente->activo = false;
        $cliente->save();

        return redirect()->route('clientes.index')->with('success', 'Cliente dado de baja temporalmente.');
    }

    // 3. Calcular cuánto debe pagar un cliente en el mes
    public function calcularPagoMensual($id)
    {
        $cliente = Cliente::findOrFail($id);
        $totalServicios = ($cliente->matutino ? 1 : 0) + ($cliente->rastreo ? 1 : 0);
        $totalLlamadas = Llamada::where('numero_origen', $cliente->telefono)->sum('precio');
        $total = $totalServicios + $totalLlamadas;

        return response()->json(['cliente' => $cliente, 'total_a_pagar' => $total]);
    }

    // 4. Información de llamadas realizadas a un cliente residencial con rastreo habilitado
    public function llamadasConRastreo($id)
    {
        $cliente = Cliente::where('id', $id)->where('tipo', 'residencial')->where('rastreo', true)->firstOrFail();
        $llamadas = Llamada::where('numero_destino', $cliente->telefono)->get();

        return response()->json(['cliente' => $cliente, 'llamadas' => $llamadas]);
    }

    // 5. Datos del cliente que más utilizó los servicios de la empresa
    public function clienteMasServicios()
    {
        $cliente = Cliente::withCount('llamadas')->orderByDesc('llamadas_count')->first();

        return response()->json(['cliente' => $cliente]);
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
                'cliente' => $cliente,
                'total_cobrado' => $total,
            ];
        });

        return response()->json($resultado);
    }

    // Actualizar un cliente
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'carnet' => 'required|string|max:255|unique:clientes,carnet,' . $id,
            'direccion' => 'required|string|max:255',
            'telefono' => 'required|string|max:15',
            'tipo' => 'required|string|in:RESIDENCIAL,ESTATAL', // Asegurarse de que los valores sean válidos
            'activo' => 'required|boolean',
        ]);

        $validated['tipo'] = strtoupper($validated['tipo']); // Convertir a mayúsculas

        $cliente = Cliente::findOrFail($id);
        $cliente->update($validated);

        return redirect()->route('clientes')->with('success', 'Cliente actualizado exitosamente.');
    }

    // Eliminar un cliente
    public function destroy($id)
    {
        $cliente = Cliente::findOrFail($id);

        // Eliminar los pagos mensuales relacionados
        $cliente->pagosMensuales()->delete();

        // Eliminar el cliente
        $cliente->delete();

        return redirect()->route('clientes')->with('success', 'Cliente eliminado exitosamente.');
    }
}
