<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { computed } from 'vue';

const props = defineProps({
    clientes: Array,
});

const totalClientes = computed(() => props.clientes?.length || 0);
const totalCobrado = computed(() => props.clientes?.reduce((sum, cliente) => sum + cliente.total_cobrado, 0) || 0);
const totalLlamadas = computed(() => props.clientes?.reduce((sum, cliente) => sum + cliente.llamadas_teleseleccion, 0) || 0);

const formatMonto = (monto) => {
    return new Intl.NumberFormat('es-CU', {
        style: 'currency',
        currency: 'CUP',
        minimumFractionDigits: 2
    }).format(monto);
};

const formatTelefono = (telefono) => {
    // Formatear teléfono cubano (ej: 5XXXXXXX -> +53 5X XXX XXX)
    if (telefono && telefono.length === 8) {
        return `+53 ${telefono.slice(0, 2)} ${telefono.slice(2, 5)} ${telefono.slice(5)}`;
    }
    return telefono;
};
</script>

<template>
    <AppLayout>
        <div class="flex min-h-screen flex-col items-center bg-[#f8fafc] py-10">
            <div class="w-full max-w-7xl px-6">
                <!-- Header Section -->
                <div class="text-center mb-8">
                    <h1 class="text-4xl font-bold text-[#004080] mb-2">📞 Clientes con Tele Selección</h1>
                    <p class="text-lg text-gray-600">Resumen del uso de servicios de teleselección del mes actual</p>
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
                    
                    <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-lg p-6 text-white shadow-lg">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-lg font-medium opacity-90">Total Llamadas</h3>
                                <p class="text-3xl font-bold">{{ totalLlamadas }}</p>
                            </div>
                            <div class="bg-white bg-opacity-20 rounded-full p-3">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-gradient-to-r from-purple-500 to-purple-600 rounded-lg p-6 text-white shadow-lg">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-lg font-medium opacity-90">Total Cobrado</h3>
                                <p class="text-3xl font-bold">{{ formatMonto(totalCobrado) }}</p>
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
                        <h2 class="text-xl font-bold text-white">Detalle por Cliente</h2>
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
                                    <th class="px-6 py-4 text-left text-sm font-semibold text-[#004080] uppercase tracking-wider">
                                        Tipo
                                    </th>
                                    <th class="px-6 py-4 text-center text-sm font-semibold text-[#004080] uppercase tracking-wider">
                                        Llamadas
                                    </th>
                                    <th class="px-6 py-4 text-right text-sm font-semibold text-[#004080] uppercase tracking-wider">
                                        Total Cobrado
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr v-for="(cliente, index) in clientes" :key="cliente.id" 
                                    class="hover:bg-[#f0f8ff] transition-colors duration-200"
                                    :class="{ 'bg-yellow-50': index === 0 && clientes.length > 1 }">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0">
                                                <div class="w-10 h-10 bg-[#004080] rounded-full flex items-center justify-center">
                                                    <span class="text-white font-bold text-sm">
                                                        {{ cliente.nombre.charAt(0).toUpperCase() }}
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ cliente.nombre }}
                                                    <span v-if="index === 0 && clientes.length > 1" 
                                                          class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                        🏆 Top
                                                    </span>
                                                </div>
                                                <div class="text-sm text-gray-500">{{ cliente.direccion }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-mono text-gray-900">
                                            {{ formatTelefono(cliente.telefono) }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                                              :class="cliente.tipo === 'ESTATAL' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800'">
                                            {{ cliente.tipo }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <div class="flex items-center justify-center">
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-purple-100 text-purple-800">
                                                {{ cliente.llamadas_teleseleccion }}
                                            </span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <div class="text-sm font-bold text-gray-900">
                                            {{ formatMonto(cliente.total_cobrado) }}
                                        </div>
                                        <div class="text-xs text-gray-500">
                                            Promedio: {{ formatMonto(cliente.total_cobrado / Math.max(cliente.llamadas_teleseleccion, 1)) }}
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Empty State -->
                    <div v-else class="text-center py-12">
                        <div class="text-6xl mb-4">📞</div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">No hay datos de teleselección</h3>
                        <p class="text-gray-500">No se encontraron clientes que hayan utilizado el servicio de teleselección este mes.</p>
                    </div>
                </div>

                <!-- Footer Info -->
                <div class="mt-8 bg-blue-50 rounded-lg p-6 border border-blue-200">
                    <div class="flex items-start space-x-3">
                        <div class="flex-shrink-0">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-blue-900">Información sobre Teleselección</h3>
                            <div class="mt-2 text-sm text-blue-700">
                                <p>• El servicio de teleselección permite a los clientes realizar llamadas internacionales con tarifas preferenciales.</p>
                                <p>• Los datos mostrados corresponden únicamente al mes actual.</p>
                                <p>• Los clientes están ordenados por el monto total cobrado (de mayor a menor).</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
