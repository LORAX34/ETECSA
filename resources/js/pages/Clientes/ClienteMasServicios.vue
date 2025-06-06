<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { router } from '@inertiajs/vue3';
import { onMounted, ref } from 'vue';

const clienteTop = ref(null);
const loading = ref(true);
const error = ref('');

const obtenerClienteMasServicios = async () => {
    loading.value = true;
    error.value = '';
    
    try {
        const response = await fetch('/clientes/mas-servicios');
        const data = await response.json();
        
        if (response.ok) {
            clienteTop.value = data;
        } else {
            error.value = data.message || 'Error al obtener los datos';
        }
    } catch (err) {
        error.value = 'Error de conexión al servidor';
        console.error('Error:', err);
    } finally {
        loading.value = false;
    }
};

const formatServicio = (valor) => {
    return valor ? 'Activo' : 'Inactivo';
};

const getServicioClass = (valor) => {
    return valor ? 'text-green-600 font-semibold' : 'text-red-600';
};

onMounted(() => {
    obtenerClienteMasServicios();
});
</script>

<template>
    <AppLayout>
        <div class="flex min-h-screen flex-col items-center bg-[#f8fafc] py-10">
            <div class="w-full max-w-6xl px-6">
                <h1 class="mb-6 text-center text-3xl font-bold text-[#004080]">Cliente que Más Utilizó los Servicios</h1>
                
                <!-- Loading State -->
                <div v-if="loading" class="flex justify-center items-center py-12">
                    <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-[#004080]"></div>
                    <span class="ml-3 text-[#004080] font-medium">Cargando datos...</span>
                </div>

                <!-- Error State -->
                <div v-else-if="error" class="rounded-lg bg-red-50 border border-red-200 p-6 text-center">
                    <div class="text-red-600 font-medium mb-2">Error al cargar los datos</div>
                    <p class="text-red-500">{{ error }}</p>
                    <button 
                        @click="obtenerClienteMasServicios" 
                        class="mt-4 rounded bg-[#004080] px-4 py-2 text-white hover:bg-[#003366] transition-colors"
                    >
                        Reintentar
                    </button>
                </div>

                <!-- Main Content -->
                <div v-else-if="clienteTop" class="space-y-6">
                    <!-- Cliente Destacado Card -->
                    <div class="rounded-lg bg-white p-8 shadow-lg border-t-4 border-[#004080]">
                        <div class="flex items-center justify-between mb-6">
                            <h2 class="text-2xl font-bold text-[#004080]">🏆 Cliente Destacado</h2>
                            <div class="bg-yellow-100 text-yellow-800 px-4 py-2 rounded-full font-semibold">
                                Top Usuario
                            </div>
                        </div>
                        
                        <!-- Información del Cliente -->
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                            <div class="space-y-4">
                                <div>
                                    <label class="text-sm font-medium text-gray-600">Nombre del Cliente</label>
                                    <p class="text-xl font-bold text-[#004080]">{{ clienteTop.cliente.nombre }}</p>
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-gray-600">Carnet de Identidad</label>
                                    <p class="text-lg text-gray-800">{{ clienteTop.cliente.carnet }}</p>
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-gray-600">Teléfono</label>
                                    <p class="text-lg text-gray-800 font-mono">{{ clienteTop.cliente.telefono }}</p>
                                </div>
                            </div>
                            <div class="space-y-4">
                                <div>
                                    <label class="text-sm font-medium text-gray-600">Dirección</label>
                                    <p class="text-lg text-gray-800">{{ clienteTop.cliente.direccion }}</p>
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-gray-600">Tipo de Cliente</label>
                                    <p class="text-lg font-semibold" :class="clienteTop.cliente.tipo === 'ESTATAL' ? 'text-blue-600' : 'text-green-600'">
                                        {{ clienteTop.cliente.tipo }}
                                    </p>
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-gray-600">Estado</label>
                                    <p class="text-lg font-semibold" :class="clienteTop.cliente.activo ? 'text-green-600' : 'text-red-600'">
                                        {{ clienteTop.cliente.activo ? 'Activo' : 'Inactivo' }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Estadísticas de Uso -->
                        <div class="border-t pt-6">
                            <h3 class="text-xl font-bold text-[#004080] mb-4">📊 Estadísticas de Uso</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                                <div class="bg-blue-50 rounded-lg p-4 text-center">
                                    <div class="text-2xl font-bold text-blue-600">{{ clienteTop.detalles_uso.llamadas_realizadas }}</div>
                                    <div class="text-sm text-blue-800">Llamadas Realizadas</div>
                                </div>
                                <div class="bg-green-50 rounded-lg p-4 text-center">
                                    <div class="text-2xl font-bold text-green-600">{{ clienteTop.detalles_uso.puntaje_total }}</div>
                                    <div class="text-sm text-green-800">Puntaje Total</div>
                                </div>
                                <div class="bg-purple-50 rounded-lg p-4 text-center">
                                    <div class="text-2xl font-bold text-purple-600">
                                        {{ Object.values(clienteTop.detalles_uso.servicios_activos).filter(Boolean).length }}
                                    </div>
                                    <div class="text-sm text-purple-800">Servicios Activos</div>
                                </div>
                                <div class="bg-orange-50 rounded-lg p-4 text-center">
                                    <div class="text-2xl font-bold text-orange-600">
                                        {{ (clienteTop.detalles_uso.puntaje_total / Math.max(clienteTop.detalles_uso.llamadas_realizadas, 1)).toFixed(1) }}
                                    </div>
                                    <div class="text-sm text-orange-800">Promedio por Llamada</div>
                                </div>
                            </div>
                        </div>

                        <!-- Servicios Contratados -->
                        <div class="border-t pt-6">
                            <h3 class="text-xl font-bold text-[#004080] mb-4">🔧 Servicios Contratados</h3>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div class="border rounded-lg p-4 flex items-center justify-between">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-3 h-3 rounded-full" :class="clienteTop.detalles_uso.servicios_activos.matutino ? 'bg-green-500' : 'bg-red-500'"></div>
                                        <span class="font-medium">Servicio Matutino</span>
                                    </div>
                                    <span :class="getServicioClass(clienteTop.detalles_uso.servicios_activos.matutino)">
                                        {{ formatServicio(clienteTop.detalles_uso.servicios_activos.matutino) }}
                                    </span>
                                </div>
                                <div class="border rounded-lg p-4 flex items-center justify-between">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-3 h-3 rounded-full" :class="clienteTop.detalles_uso.servicios_activos.rastreo ? 'bg-green-500' : 'bg-red-500'"></div>
                                        <span class="font-medium">Servicio de Rastreo</span>
                                    </div>
                                    <span :class="getServicioClass(clienteTop.detalles_uso.servicios_activos.rastreo)">
                                        {{ formatServicio(clienteTop.detalles_uso.servicios_activos.rastreo) }}
                                    </span>
                                </div>
                                <div class="border rounded-lg p-4 flex items-center justify-between">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-3 h-3 rounded-full" :class="clienteTop.detalles_uso.servicios_activos.linea_arrendada ? 'bg-green-500' : 'bg-red-500'"></div>
                                        <span class="font-medium">Línea Arrendada</span>
                                    </div>
                                    <span :class="getServicioClass(clienteTop.detalles_uso.servicios_activos.linea_arrendada)">
                                        {{ formatServicio(clienteTop.detalles_uso.servicios_activos.linea_arrendada) }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Explicación del Puntaje -->
                        <div class="border-t pt-6">
                            <h3 class="text-xl font-bold text-[#004080] mb-4">ℹ️ Cálculo del Puntaje</h3>
                            <div class="bg-gray-50 rounded-lg p-4">
                                <p class="text-sm text-gray-700 mb-2">
                                    <strong>Metodología de cálculo:</strong>
                                </p>
                                <ul class="text-sm text-gray-600 space-y-1 ml-4">
                                    <li>• Servicio Matutino: +10 puntos</li>
                                    <li>• Servicio de Rastreo: +10 puntos</li>
                                    <li>• Línea Arrendada: +15 puntos</li>
                                    <li>• Por cada llamada realizada: +5 puntos</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Acciones -->
                    <div class="text-center">
                        <button 
                            @click="obtenerClienteMasServicios" 
                            class="rounded bg-[#004080] px-6 py-3 text-white hover:bg-[#003366] transition-colors font-medium"
                        >
                            🔄 Actualizar Datos
                        </button>
                    </div>
                </div>

                <!-- No Data State -->
                <div v-else class="rounded-lg bg-gray-50 border border-gray-200 p-8 text-center">
                    <div class="text-gray-600 font-medium mb-2">No se encontraron datos</div>
                    <p class="text-gray-500">No hay clientes registrados en el sistema.</p>
                </div>
            </div>
        </div>
    </AppLayout>
</template>