<script setup lang="ts">
defineProps({
    cliente: Object,
    onEdit: Function,
    onDelete: Function,
});
</script>

<template>
    <div class="rounded-lg border bg-white p-4 shadow-md">
        <h3 class="text-lg font-semibold text-[#005BAC]">{{ cliente.nombre }}</h3>
        <p class="text-sm text-gray-600">Carnet: {{ cliente.carnet }}</p>
        <p class="text-sm text-gray-600">Dirección: {{ cliente.direccion }}</p>
        <p class="text-sm text-gray-600">Teléfono: {{ cliente.telefono }}</p>
        <p class="text-sm text-gray-600">Tipo: {{ cliente.tipo }}</p>
        <p class="text-sm text-gray-600">
            Servicio Matutino:
            <span :class="cliente.matutino ? 'text-green-500' : 'text-red-500'">
                {{ cliente.matutino ? 'Sí' : 'No' }}
            </span>
        </p>
        <p class="text-sm text-gray-600">
            Rastreo:
            <span :class="cliente.rastreo ? 'text-green-500' : 'text-red-500'">
                {{ cliente.rastreo ? 'Sí' : 'No' }}
            </span>
        </p>
        <p class="text-sm text-gray-600">
            Línea Arrendada:
            <span :class="cliente.linea_arrendada ? 'text-green-500' : 'text-red-500'">
                {{ cliente.linea_arrendada ? 'Sí' : 'No' }}
            </span>
        </p>
        <div class="mt-4 flex space-x-2">
            <button @click="onEdit(cliente)" class="rounded bg-blue-500 px-4 py-2 text-white hover:bg-blue-600">Editar</button>
            <button @click="onDelete(cliente)" class="rounded bg-red-500 px-4 py-2 text-white hover:bg-red-600">Eliminar</button>
        </div>
    </div>
</template>
s
