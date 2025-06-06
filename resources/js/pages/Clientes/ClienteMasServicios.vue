<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { defineProps } from 'vue';

defineProps({
    clienteTop: Object,
    error: String,
});
</script>

<template>
    <AppLayout>
        <div class="flex min-h-screen flex-col items-center bg-[#f8fafc] py-10">
            <div class="w-full max-w-6xl px-6">
                <h1 class="mb-6 text-center text-3xl font-bold text-[#004080]">Cliente que Más Utilizó los Servicios</h1>

                <!-- Error State -->
                <div v-if="error" class="rounded-lg border border-red-200 bg-red-50 p-6 text-center">
                    <div class="mb-2 font-medium text-red-600">Error al cargar los datos</div>
                    <p class="text-red-500">{{ error }}</p>
                </div>

                <!-- Main Content -->
                <div v-else-if="clienteTop && clienteTop.cliente" class="space-y-6">
                    <!-- Cliente Destacado Card -->
                    <div class="rounded-lg border-t-4 border-[#004080] bg-white p-8 shadow-lg">
                        <div class="mb-6 flex items-center justify-between">
                            <h2 class="text-2xl font-bold text-[#004080]">🏆 Cliente Destacado</h2>
                            <div class="rounded-full bg-yellow-100 px-4 py-2 font-semibold text-yellow-800">Top Usuario</div>
                        </div>

                        <!-- Información del Cliente -->
                        <div class="mb-8 grid grid-cols-1 gap-6 lg:grid-cols-2">
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
                                    <p class="font-mono text-lg text-gray-800">{{ clienteTop.cliente.telefono }}</p>
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-gray-600">Dirección</label>
                                    <p class="text-lg text-gray-800">{{ clienteTop.cliente.direccion }}</p>
                                </div>
                            </div>
                            <div class="space-y-4">
                                <div>
                                    <label class="text-sm font-medium text-gray-600">Tipo de Cliente</label>
                                    <p
                                        class="text-lg font-semibold"
                                        :class="clienteTop.cliente.tipo === 'ESTATAL' ? 'text-blue-600' : 'text-green-600'"
                                    >
                                        {{ clienteTop.cliente.tipo }}
                                    </p>
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-gray-600">Estado</label>
                                    <p class="text-lg font-semibold" :class="clienteTop.cliente.activo ? 'text-green-600' : 'text-red-600'">
                                        {{ clienteTop.cliente.activo ? 'Activo' : 'Inactivo' }}
                                    </p>
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-gray-600">Moroso</label>
                                    <p class="text-lg font-semibold" :class="clienteTop.cliente.moroso ? 'text-red-600' : 'text-green-600'">
                                        {{ clienteTop.cliente.moroso ? 'Sí' : 'No' }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Estadísticas de Uso -->
                        <div class="border-t pt-6">
                            <h3 class="mb-4 text-xl font-bold text-[#004080]">📊 Estadísticas de Uso</h3>
                            <div class="mb-6 grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-4">
                                <div class="rounded-lg bg-blue-50 p-4 text-center">
                                    <div class="text-2xl font-bold text-blue-600">{{ clienteTop.detalles_uso.llamadas_realizadas }}</div>
                                    <div class="text-sm text-blue-800">Llamadas Realizadas</div>
                                </div>
                                <div class="rounded-lg bg-green-50 p-4 text-center">
                                    <div class="text-2xl font-bold text-green-600">{{ clienteTop.detalles_uso.puntaje_total }}</div>
                                    <div class="text-sm text-green-800">Puntaje Total</div>
                                </div>
                                <div class="rounded-lg bg-purple-50 p-4 text-center">
                                    <div class="text-2xl font-bold text-purple-600">
                                        {{ Object.values(clienteTop.detalles_uso.servicios_activos).filter(Boolean).length }}
                                    </div>
                                    <div class="text-sm text-purple-800">Servicios Activos</div>
                                </div>
                                <div class="rounded-lg bg-orange-50 p-4 text-center">
                                    <div class="text-2xl font-bold text-orange-600">
                                        {{
                                            (
                                                clienteTop.detalles_uso.puntaje_total / Math.max(clienteTop.detalles_uso.llamadas_realizadas, 1)
                                            ).toFixed(1)
                                        }}
                                    </div>
                                    <div class="text-sm text-orange-800">Promedio por Llamada</div>
                                </div>
                            </div>
                        </div>

                        <!-- Servicios Contratados -->
                        <div class="border-t pt-6">
                            <h3 class="mb-4 text-xl font-bold text-[#004080]">🔧 Servicios Contratados</h3>
                            <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                                <div class="flex items-center justify-between rounded-lg border p-4">
                                    <div class="flex items-center space-x-3">
                                        <div
                                            class="h-3 w-3 rounded-full"
                                            :class="clienteTop.detalles_uso.servicios_activos.matutino ? 'bg-green-500' : 'bg-red-500'"
                                        ></div>
                                        <span class="font-medium">Servicio Matutino</span>
                                    </div>
                                    <span :class="clienteTop.detalles_uso.servicios_activos.matutino ? 'text-green-600' : 'text-red-600'">
                                        {{ clienteTop.detalles_uso.servicios_activos.matutino ? 'Activo' : 'Inactivo' }}
                                    </span>
                                </div>
                                <div class="flex items-center justify-between rounded-lg border p-4">
                                    <div class="flex items-center space-x-3">
                                        <div
                                            class="h-3 w-3 rounded-full"
                                            :class="clienteTop.detalles_uso.servicios_activos.rastreo ? 'bg-green-500' : 'bg-red-500'"
                                        ></div>
                                        <span class="font-medium">Servicio de Rastreo</span>
                                    </div>
                                    <span :class="clienteTop.detalles_uso.servicios_activos.rastreo ? 'text-green-600' : 'text-red-600'">
                                        {{ clienteTop.detalles_uso.servicios_activos.rastreo ? 'Activo' : 'Inactivo' }}
                                    </span>
                                </div>
                                <div class="flex items-center justify-between rounded-lg border p-4">
                                    <div class="flex items-center space-x-3">
                                        <div
                                            class="h-3 w-3 rounded-full"
                                            :class="clienteTop.detalles_uso.servicios_activos.linea_arrendada ? 'bg-green-500' : 'bg-red-500'"
                                        ></div>
                                        <span class="font-medium">Línea Arrendada</span>
                                    </div>
                                    <span :class="clienteTop.detalles_uso.servicios_activos.linea_arrendada ? 'text-green-600' : 'text-red-600'">
                                        {{ clienteTop.detalles_uso.servicios_activos.linea_arrendada ? 'Activo' : 'Inactivo' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- No Data State -->
                <div v-else class="rounded-lg border border-gray-200 bg-gray-50 p-8 text-center">
                    <div class="mb-2 font-medium text-gray-600">No se encontraron datos</div>
                    <p class="text-gray-500">No hay clientes registrados en el sistema.</p>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
