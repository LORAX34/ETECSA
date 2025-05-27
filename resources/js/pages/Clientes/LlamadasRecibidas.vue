<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const clienteTelefono = ref('');
const llamadas = ref([]);

const form = useForm();

const obtenerLlamadas = () => {
    form.get(`/clientes/llamadas-recibidas?telefono=${clienteTelefono.value}`, {
        onSuccess: (response) => {
            llamadas.value = response.data.llamadas;
        },
    });
};
</script>

<template>
    <div class="flex min-h-screen items-center justify-center bg-gradient-to-r from-blue-500 to-blue-700 text-white">
        <div class="w-full max-w-lg rounded-lg bg-white p-6 text-black shadow-lg">
            <h1 class="text-center text-2xl font-bold text-blue-700">Llamadas Recibidas</h1>
            <form @submit.prevent="obtenerLlamadas" class="mt-6 space-y-4">
                <div>
                    <label for="telefono" class="block text-sm font-medium">Número de Teléfono del Cliente</label>
                    <input v-model="clienteTelefono" id="telefono" type="text" class="w-full rounded border p-2" required />
                </div>
                <button
                    type="submit"
                    class="w-full rounded bg-green-500 px-4 py-2 font-semibold text-white hover:bg-green-600"
                    :disabled="form.processing"
                >
                    Obtener Llamadas
                </button>
            </form>
            <div v-if="llamadas.length" class="mt-6">
                <h2 class="text-lg font-semibold text-blue-700">Listado de Llamadas:</h2>
                <table class="mt-4 w-full border-collapse border border-gray-300">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="border border-gray-300 px-4 py-2 text-left">Número</th>
                            <th class="border border-gray-300 px-4 py-2 text-left">Nombre</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="llamada in llamadas" :key="llamada.id">
                            <td class="border border-gray-300 px-4 py-2">{{ llamada.numero_origen }}</td>
                            <td class="border border-gray-300 px-4 py-2">{{ llamada.nombre_origen }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>
