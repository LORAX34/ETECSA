<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { ref } from 'vue';

defineProps({
    cliente: Object,
    llamadas: Array,
    error: String,
});

const telefono = ref('');
const clienteInfo = ref(cliente || null);
const llamadasInfo = ref(llamadas || []);
const errorInfo = ref(error || '');

const buscarCliente = () => {
    if (!telefono.value) {
        errorInfo.value = 'El número de teléfono es requerido.';
        clienteInfo.value = null;
        llamadasInfo.value = [];
        return;
    }

    window.location.href = `/clientes/llamadas-rastreo?telefono=${telefono.value}`;
};
</script>

<template>
    <AppLayout>
        <div class="flex min-h-screen flex-col items-center bg-[#f8fafc] py-10">
            <div class="w-full max-w-4xl px-6">
                <h1 class="mb-6 text-center text-3xl font-bold text-[#004080]">Llamadas con Rastreo</h1>
                <div class="mb-6 rounded-lg bg-white p-6 shadow-md">
                    <h2 class="mb-4 text-xl font-bold text-[#004080]">Buscar Cliente</h2>
                    <div class="flex items-center space-x-4">
                        <input
                            v-model="telefono"
                            type="text"
                            placeholder="Número de Teléfono"
                            class="w-full rounded border border-gray-300 px-4 py-2"
                        />
                        <button @click="buscarCliente" class="rounded bg-[#004080] px-4 py-2 text-white hover:bg-[#003366]">Buscar</button>
                    </div>
                    <p v-if="errorInfo" class="mt-4 text-red-500">{{ errorInfo }}</p>
                </div>

                <div v-if="clienteInfo" class="rounded-lg bg-white p-6 shadow-md">
                    <h2 class="mb-4 text-xl font-bold text-[#004080]">Información del Cliente</h2>
                    <p><strong>Nombre:</strong> {{ clienteInfo.nombre }}</p>
                    <p><strong>Teléfono:</strong> {{ clienteInfo.telefono }}</p>
                    <p><strong>Dirección:</strong> {{ clienteInfo.direccion }}</p>
                </div>

                <div v-if="llamadasInfo.length" class="mt-6 rounded-lg bg-white p-6 shadow-md">
                    <h2 class="mb-4 text-xl font-bold text-[#004080]">Llamadas Recibidas</h2>
                    <table class="w-full table-auto border-collapse border border-[#004080]">
                        <thead>
                            <tr class="bg-[#e6f2ff]">
                                <th class="border border-[#004080] px-4 py-2 text-[#004080]">Número de Origen</th>
                                <th class="border border-[#004080] px-4 py-2 text-[#004080]">Nombre</th>
                                <th class="border border-[#004080] px-4 py-2 text-[#004080]">Dirección</th>
                                <th class="border border-[#004080] px-4 py-2 text-[#004080]">Fecha</th>
                                <th class="border border-[#004080] px-4 py-2 text-[#004080]">Precio</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="llamada in llamadasInfo" :key="llamada.numero_origen" class="hover:bg-[#f0f8ff]">
                                <td class="border border-[#004080] px-4 py-2 text-center">{{ llamada.numero_origen }}</td>
                                <td class="border border-[#004080] px-4 py-2 text-center">{{ llamada.nombre_llamador }}</td>
                                <td class="border border-[#004080] px-4 py-2 text-center">{{ llamada.direccion_llamador }}</td>
                                <td class="border border-[#004080] px-4 py-2 text-center">{{ llamada.fecha_hora }}</td>
                                <td class="border border-[#004080] px-4 py-2 text-center">{{ llamada.precio }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
