<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Llamada;
use App\Models\PagoMensual;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        $clientesActivos = Cliente::where('activo', true)->count();
        $clientesMorosos = Cliente::where('moroso', true)->count();
        $clientesInactivos = Cliente::where('activo', false)->count();
        $llamadasHoy = Llamada::whereDate('fecha_hora', now()->toDateString())->count();
        $pagosPendientes = Cliente::where('moroso', true)->count();

        // Total de deuda acumulada por clientes morosos
        $deudaTotal = (float) PagoMensual::whereHas('cliente', function ($query) {
            $query->where('moroso', true);
        })->sum('total');

        // Estadísticas de llamadas
        $llamadasEstadisticas = [
            'internacionales' => Llamada::where('es_internacional', true)->count(),
            'nacionales' => Llamada::where('es_nacional', true)->count(),
            'locales' => Llamada::where('es_local', true)->count(),
        ];

        // Servicios contratados
        $serviciosContratados = [
            'matutino' => Cliente::where('matutino', true)->count(),
            'rastreo' => Cliente::where('rastreo', true)->count(),
            'linea_arrendada' => Cliente::where('linea_arrendada', true)->count(),
        ];

        return Inertia::render('Dashboard', [
            'clientes' => [
                'activos' => $clientesActivos,
                'morosos' => $clientesMorosos,
                'inactivos' => $clientesInactivos,
            ],
            'llamadasHoy' => $llamadasHoy,
            'pagosPendientes' => $pagosPendientes,
            'deudaTotal' => $deudaTotal,
            'llamadasEstadisticas' => $llamadasEstadisticas,
            'serviciosContratados' => $serviciosContratados,
        ]);
    }
}
