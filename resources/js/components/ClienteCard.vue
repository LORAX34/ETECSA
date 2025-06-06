<script setup lang="ts">
import { computed } from 'vue';

const props = defineProps({
    cliente: Object,
    onEdit: Function,
    onDelete: Function,
});

const serviciosActivos = computed(() => {
    const servicios = [];
    if (props.cliente.matutino) servicios.push('Matutino');
    if (props.cliente.rastreo) servicios.push('Rastreo');
    if (props.cliente.linea_arrendada) servicios.push('Línea Arrendada');
    return servicios;
});

const formatTelefono = (telefono) => {
    if (telefono && telefono.length === 8) {
        return `+53 ${telefono.slice(0, 2)} ${telefono.slice(2, 5)} ${telefono.slice(5)}`;
    }
    return telefono;
};
</script>

<template>
    <div class="rounded-lg border-2 bg-white p-6 shadow-lg transition-all duration-200 hover:shadow-xl hover:border-[#005BAC]"
         :class="{ 'border-red-300 bg-red-50': cliente.moroso, 'border-gray-200': !cliente.moroso }">
        
        <!-- Header con nombre y estado -->
        <div class="flex items-start justify-between mb-4">
            <div class="flex items-center space-x-3">
                <div class="w-12 h-12 rounded-full flex items-center justify-center"
                     :class="cliente.activo ? 'bg-green-100' : 'bg-red-100'">
                    <span class="text-lg font-bold"
                          :class="cliente.activo ? 'text-green-600' : 'text-red-600'">
                        {{ cliente.nombre.charAt(0).toUpperCase() }}
                    </span>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-[#005BAC]">{{ cliente.nombre }}</h3>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                          :class="cliente.tipo === 'ESTATAL' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800'">
                        {{ cliente.tipo }}
                    </span>
                </div>
            </div>
            
            <!-- Estados -->
            <div class="flex flex-col items-end space-y-1">
                <span v-if="cliente.moroso" 
                      class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                    ⚠️ Moroso
                </span>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                      :class="cliente.activo ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'">
                    {{ cliente.activo ? '✅ Activo' : '❌ Inactivo' }}
                </span>
            </div>
        </div>

        <!-- Información básica -->
        <div class="space-y-3 mb-4">
            <div class="flex items-center space-x-2">
                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V4a2 2 0 118 0v2m-4 0a2 2 0 104 0m-4 0a2 2 0 014 0z"></path>
                </svg>
                <span class="text-sm text-gray-600">{{ cliente.carnet }}</span>
            </div>
            
            <div class="flex items-center space-x-2">
                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                </svg>
                <span class="text-sm font-mono text-gray-900">{{ formatTelefono(cliente.telefono) }}</span>
            </div>
            
            <div class="flex items-start space-x-2">
                <svg class="w-4 h-4 text-gray-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
                <span class="text-sm text-gray-600">{{ cliente.direccion }}</span>
            </div>
        </div>

        <!-- Servicios -->
        <div class="mb-4">
            <h4 class="text-sm font-medium text-gray-700 mb-2">🔧 Servicios Contratados</h4>
            <div v-if="serviciosActivos.length > 0" class="flex flex-wrap gap-2">
                <span v-for="servicio in serviciosActivos" :key="servicio"
                      class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                    {{ servicio }}
                </span>
            </div>
            <div v-else class="text-xs text-gray-500 italic">
                No tiene servicios adicionales
            </div>
        </div>

        <!-- Acciones -->
        <div class="flex space-x-2 pt-4 border-t border-gray-200">
            <button @click="onEdit(cliente)" 
                    class="flex-1 rounded bg-blue-500 px-4 py-2 text-sm text-white hover:bg-blue-600 transition-colors duration-200 flex items-center justify-center space-x-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
                <span>Editar</span>
            </button>
            <button @click="onDelete(cliente)" 
                    class="flex-1 rounded bg-red-500 px-4 py-2 text-sm text-white hover:bg-red-600 transition-colors duration-200 flex items-center justify-center space-x-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                </svg>
                <span>Eliminar</span>
            </button>
        </div>
    </div>
</template>
