<script setup lang="ts">
defineProps({
    clientes: Array,
});

const toggleEstado = (clienteId) => {
    // Lógica para cambiar el estado de moroso o activo
    console.log('Cambiar estado de cliente:', clienteId);
};
</script>

<template>
    <div>
        <h1 class="mb-6 text-xl font-bold text-[#005BAC]">Pagos Mensuales</h1>
        <table class="w-full table-auto border-collapse border border-gray-300">
            <thead>
                <tr class="bg-gray-100">
                    <th class="border border-gray-300 px-4 py-2">Nombre</th>
                    <th class="border border-gray-300 px-4 py-2">Teléfono</th>
                    <th class="border border-gray-300 px-4 py-2">Moroso</th>
                    <th class="border border-gray-300 px-4 py-2">Activo</th>
                    <th class="border border-gray-300 px-4 py-2">Monto a Pagar</th>
                    <th class="border border-gray-300 px-4 py-2">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="cliente in clientes" :key="cliente.id">
                    <td class="border border-gray-300 px-4 py-2">{{ cliente.nombre }}</td>
                    <td class="border border-gray-300 px-4 py-2">{{ cliente.telefono }}</td>
                    <td class="border border-gray-300 px-4 py-2">{{ cliente.moroso ? 'Sí' : 'No' }}</td>
                    <td class="border border-gray-300 px-4 py-2">{{ cliente.activo ? 'Sí' : 'No' }}</td>
                    <td class="border border-gray-300 px-4 py-2">{{ cliente.monto_pagar }}</td>
                    <td class="border border-gray-300 px-4 py-2">
                        <button @click="toggleEstado(cliente.id)" class="rounded bg-blue-500 px-4 py-2 text-white hover:bg-blue-600">
                            Cambiar Estado
                        </button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>
