<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { ref } from 'vue';

const telefono = ref('');
const error = ref('');
const cliente = ref(null);
const llamadas = ref([]);

const buscarLlamadas = async () => {
    if (!telefono.value) {
        error.value = 'Por favor, ingrese un número de teléfono.';
        return;
    }

    error.value = '';
    cliente.value = null;
    llamadas.value = [];

    try {
        const response = await fetch(`/clientes/llamadas-rastreo?telefono=${telefono.value}`);
        if (!response.ok) {
            throw new Error('Error en la respuesta del servidor.');
        }

        const data = await response.json();
        cliente.value = data.cliente;
        llamadas.value = data.llamadas;
        error.value = data.error || '';
    } catch (err) {
        error.value = 'Error al buscar las llamadas. Por favor, inténtelo nuevamente.';
        console.error(err);
    }
};
</script>

<template>
    <AppLayout>
        <div class="flex min-h-screen flex-col items-center bg-[#f8fafc] py-10">
            <div class="w-full max-w-6xl px-6">
                <h1 class="mb-6 text-center text-3xl font-bold text-[#004080]">Buscar Llamadas por Teléfono</h1>

                <!-- Formulario de búsqueda -->
                <div class="mb-8 rounded-lg bg-white p-6 shadow-lg">
                    <label for="telefono" class="block text-sm font-medium text-gray-700">Número de Teléfono</label>
                    <div class="mt-2 flex items-center space-x-4">
                        <input
                            id="telefono"
                            type="text"
                            v-model="telefono"
                            class="w-full rounded border-gray-300 shadow-sm focus:border-[#004080] focus:ring-[#004080]"
                            placeholder="Ingrese el número de teléfono"
                        />
                        <button @click="buscarLlamadas" class="rounded bg-[#004080] px-4 py-2 text-white transition-colors hover:bg-[#003366]">
                            Buscar
                        </button>
                    </div>
                    <p v-if="error" class="mt-2 text-sm text-red-600">{{ error }}</p>
                </div>

                <!-- Error State -->
                <div v-if="error && !cliente" class="rounded-lg border border-red-200 bg-red-50 p-6 text-center">
                    <div class="mb-2 font-medium text-red-600">Error</div>
                    <p class="text-red-500">{{ error }}</p>
                </div>

                <!-- Cliente y Llamadas -->
                <div v-else-if="cliente && llamadas.length > 0" class="space-y-6">
                    <!-- Información del Cliente -->
                    <div class="rounded-lg border-t-4 border-[#004080] bg-white p-8 shadow-lg">
                        <h2 class="text-2xl font-bold text-[#004080]">📞 Información del Cliente</h2>
                        <div class="mt-4 grid grid-cols-1 gap-6 lg:grid-cols-2">
                            <div>
                                <label class="text-sm font-medium text-gray-600">Nombre</label>
                                <p class="text-lg font-bold text-[#004080]">{{ cliente.nombre }}</p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-600">Teléfono</label>
                                <p class="font-mono text-lg text-gray-800">{{ cliente.telefono }}</p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-600">Dirección</label>
                                <p class="text-lg text-gray-800">{{ cliente.direccion }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Llamadas Realizadas -->
                    <div class="rounded-lg border-t-4 border-[#004080] bg-white p-8 shadow-lg">
                        <h2 class="text-2xl font-bold text-[#004080]">📊 Llamadas Realizadas</h2>
                        <table class="mt-4 w-full border-collapse border border-gray-200">
                            <thead>
                                <tr class="bg-gray-100">
                                    <th class="border border-gray-300 px-4 py-2 text-left text-sm font-medium text-gray-600">Número Origen</th>
                                    <th class="border border-gray-300 px-4 py-2 text-left text-sm font-medium text-gray-600">Nombre Llamador</th>
                                    <th class="border border-gray-300 px-4 py-2 text-left text-sm font-medium text-gray-600">Dirección Llamador</th>
                                    <th class="border border-gray-300 px-4 py-2 text-left text-sm font-medium text-gray-600">Fecha y Hora</th>
                                    <th class="border border-gray-300 px-4 py-2 text-left text-sm font-medium text-gray-600">Precio</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="llamada in llamadas" :key="llamada.numero_origen" class="hover:bg-gray-50">
                                    <td class="border border-gray-300 px-4 py-2 text-sm text-gray-800">{{ llamada.numero_origen }}</td>
                                    <td class="border border-gray-300 px-4 py-2 text-sm text-gray-800">{{ llamada.nombre_llamador }}</td>
                                    <td class="border border-gray-300 px-4 py-2 text-sm text-gray-800">{{ llamada.direccion_llamador }}</td>
                                    <td class="border border-gray-300 px-4 py-2 text-sm text-gray-800">{{ llamada.fecha_hora }}</td>
                                    <td class="border border-gray-300 px-4 py-2 text-sm text-gray-800">{{ llamada.precio }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- No Data State -->
                <div v-else class="rounded-lg border border-gray-200 bg-gray-50 p-8 text-center">
                    <div class="mb-2 font-medium text-gray-600">No se encontraron llamadas</div>
                    <p class="text-gray-500">No hay datos disponibles para el cliente seleccionado.</p>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
