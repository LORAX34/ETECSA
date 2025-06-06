<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { router } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

const props = defineProps({
    clientes: Array,
});

const selectedCliente = ref(null);
const showModal = ref(false);
const pagoDetalle = ref(null);
const loadingPago = ref(false);

const totalClientes = computed(() => props.clientes?.length || 0);
const totalMonto = computed(() => props.clientes?.reduce((sum, cliente) => sum + cliente.monto_pagar, 0) || 0);
const clientesMorosos = computed(() => props.clientes?.filter(cliente => cliente.moroso).length || 0);

const toggleEstado = (cliente) => {
    const nuevaMorosidad = !cliente.moroso;
    router.patch(
        `/clientes/${cliente.id}/baja`,
        { moroso: nuevaMorosidad },
        {
            onSuccess: () => {
                router.reload(); // Recargar la página para mostrar los cambios
            },
        },
    );
};

const formatMonto = (monto) => {
    return new Intl.NumberFormat('es-CU', {
        style: 'currency',
        currency: 'CUP',
        minimumFractionDigits: 2
    }).format(monto);
};

const calcularPagoDetallado = async (cliente) => {
    selectedCliente.value = cliente;
    showModal.value = true;
    loadingPago.value = true;
    pagoDetalle.value = null;
    
    try {
        const response = await fetch(`/clientes/${cliente.id}/pago-mensual`);
        const data = await response.json();
        
        if (response.ok) {
            pagoDetalle.value = data;
        } else {
            console.error('Error al obtener el detalle del pago');
        }
    } catch (error) {
        console.error('Error:', error);
    } finally {
        loadingPago.value = false;
    }
};

const cerrarModal = () => {
    showModal.value = false;
    selectedCliente.value = null;
    pagoDetalle.value = null;
};
</script>

<template>
    <AppLayout>
        <div class="flex min-h-screen flex-col items-center bg-[#f8fafc] py-10">
            <div class="w-full max-w-7xl px-6">
                <!-- Header Section -->
                <div class="text-center mb-8">
                    <h1 class="text-4xl font-bold text-[#004080] mb-2">💰 Pagos Mensuales</h1>
                    <p class="text-lg text-gray-600">Gestión y control de pagos de todos los clientes</p>
                </div>

                <!-- Statistics Cards -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg p-6 text-white shadow-lg">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-lg font-medium opacity-90">Total Clientes</h3>
                                <p class="text-3xl font-bold">{{ totalClientes }}</p>
                            </div>
                            <div class="bg-white bg-opacity-20 rounded-full p-3">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-gradient-to-r from-red-500 to-red-600 rounded-lg p-6 text-white shadow-lg">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-lg font-medium opacity-90">Clientes Morosos</h3>
                                <p class="text-3xl font-bold">{{ clientesMorosos }}</p>
                            </div>
                            <div class="bg-white bg-opacity-20 rounded-full p-3">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-lg p-6 text-white shadow-lg">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-lg font-medium opacity-90">Total a Cobrar</h3>
                                <p class="text-3xl font-bold">{{ formatMonto(totalMonto) }}</p>
                            </div>
                            <div class="bg-white bg-opacity-20 rounded-full p-3">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Main Table -->
                <div class="rounded-lg bg-white shadow-lg overflow-hidden">
                    <div class="bg-[#004080] px-6 py-4">
                        <h2 class="text-xl font-bold text-white">Lista de Clientes y Pagos</h2>
                    </div>
                    
                    <div v-if="clientes && clientes.length > 0" class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="bg-[#e6f2ff] border-b border-[#004080]">
                                    <th class="px-6 py-4 text-left text-sm font-semibold text-[#004080] uppercase tracking-wider">
                                        Cliente
                                    </th>
                                    <th class="px-6 py-4 text-left text-sm font-semibold text-[#004080] uppercase tracking-wider">
                                        Teléfono
                                    </th>
                                    <th class="px-6 py-4 text-center text-sm font-semibold text-[#004080] uppercase tracking-wider">
                                        Estado
                                    </th>
                                    <th class="px-6 py-4 text-right text-sm font-semibold text-[#004080] uppercase tracking-wider">
                                        Monto a Pagar
                                    </th>
                                    <th class="px-6 py-4 text-center text-sm font-semibold text-[#004080] uppercase tracking-wider">
                                        Acciones
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr v-for="cliente in clientes" :key="cliente.id" 
                                    class="hover:bg-[#f0f8ff] transition-colors duration-200"
                                    :class="{ 'bg-red-50': cliente.moroso }">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0">
                                                <div class="w-10 h-10 rounded-full flex items-center justify-center"
                                                     :class="cliente.activo ? 'bg-green-100' : 'bg-red-100'">
                                                    <span class="font-bold text-sm"
                                                          :class="cliente.activo ? 'text-green-600' : 'text-red-600'">
                                                        {{ cliente.nombre.charAt(0).toUpperCase() }}
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ cliente.nombre }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-mono text-gray-900">
                                            {{ cliente.telefono }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <div class="space-y-1">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                                                  :class="cliente.activo ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'">
                                                {{ cliente.activo ? 'Activo' : 'Inactivo' }}
                                            </span>
                                            <span v-if="cliente.moroso" 
                                                  class="block inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                Moroso
                                            </span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <div class="text-lg font-bold text-gray-900">
                                            {{ formatMonto(cliente.monto_pagar) }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <div class="space-x-2">
                                            <button 
                                                @click="calcularPagoDetallado(cliente)" 
                                                class="rounded bg-blue-500 px-3 py-1 text-sm text-white hover:bg-blue-600 transition-colors"
                                                title="Ver detalle del pago"
                                            >
                                                📋 Detalle
                                            </button>
                                            <button 
                                                @click="toggleEstado(cliente)" 
                                                class="rounded px-3 py-1 text-sm text-white transition-colors"
                                                :class="cliente.moroso ? 'bg-green-500 hover:bg-green-600' : 'bg-red-500 hover:bg-red-600'"
                                            >
                                                {{ cliente.moroso ? '✅ Activar' : '⚠️ Morosidad' }}
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Empty State -->
                    <div v-else class="text-center py-12">
                        <div class="text-6xl mb-4">💰</div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">No hay clientes registrados</h3>
                        <p class="text-gray-500">No se encontraron clientes en el sistema para mostrar sus pagos.</p>
                    </div>
                </div>

                <!-- Modal para Detalle de Pago -->
                <div v-if="showModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                    <div class="bg-white rounded-lg p-8 max-w-2xl w-full mx-4 max-h-96 overflow-y-auto">
                        <div class="flex justify-between items-center mb-6">
                            <h3 class="text-2xl font-bold text-[#004080]">
                                💰 Detalle de Pago - {{ selectedCliente?.nombre }}
                            </h3>
                            <button @click="cerrarModal" class="text-gray-500 hover:text-gray-700">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>

                        <!-- Loading State -->
                        <div v-if="loadingPago" class="text-center py-8">
                            <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-[#004080] mx-auto"></div>
                            <p class="mt-4 text-gray-600">Calculando detalle del pago...</p>
                        </div>

                        <!-- Detalle del Pago -->
                        <div v-else-if="pagoDetalle" class="space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="text-sm font-medium text-gray-600">Cliente</label>
                                    <p class="text-lg font-semibold">{{ pagoDetalle.cliente.nombre }}</p>
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-gray-600">Teléfono</label>
                                    <p class="text-lg font-mono">{{ pagoDetalle.cliente.telefono }}</p>
                                </div>
                            </div>

                            <div class="border-t pt-4">
                                <h4 class="text-lg font-semibold text-[#004080] mb-4">Desglose de Costos</h4>
                                
                                <!-- Servicios Adicionales -->
                                <div class="bg-blue-50 rounded-lg p-4 mb-4">
                                    <h5 class="font-medium text-blue-900 mb-2">Servicios Adicionales</h5>
                                    <div v-if="pagoDetalle.detalles.servicios_adicionales.length > 0" class="space-y-2">
                                        <div v-for="servicio in pagoDetalle.detalles.servicios_adicionales" :key="servicio" 
                                             class="flex justify-between text-sm">
                                            <span>{{ servicio }}</span>
                                            <span class="font-medium">$1.00</span>
                                        </div>
                                    </div>
                                    <div v-else class="text-sm text-blue-700">No tiene servicios adicionales</div>
                                    <div class="border-t border-blue-200 mt-2 pt-2 flex justify-between font-medium">
                                        <span>Subtotal Servicios:</span>
                                        <span>{{ formatMonto(pagoDetalle.detalles.costo_servicios) }}</span>
                                    </div>
                                </div>

                                <!-- Costo de Llamadas -->
                                <div class="bg-green-50 rounded-lg p-4 mb-4">
                                    <h5 class="font-medium text-green-900 mb-2">Costo de Llamadas</h5>
                                    <div class="flex justify-between font-medium">
                                        <span>Total Llamadas:</span>
                                        <span>{{ formatMonto(pagoDetalle.detalles.costo_llamadas) }}</span>
                                    </div>
                                </div>

                                <!-- Total -->
                                <div class="bg-[#004080] text-white rounded-lg p-4">
                                    <div class="flex justify-between items-center">
                                        <span class="text-lg font-medium">Total a Pagar:</span>
                                        <span class="text-2xl font-bold">{{ formatMonto(pagoDetalle.total_a_pagar) }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-6 text-center">
                            <button @click="cerrarModal" class="rounded bg-gray-500 px-6 py-2 text-white hover:bg-gray-600">
                                Cerrar
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
