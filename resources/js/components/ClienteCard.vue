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
        <p class="text-sm text-gray-600">Teléfono: {{ cliente.telefono }}</p>
        <p class="text-sm" :class="cliente.activo ? 'text-green-500' : 'text-red-500'">
            {{ cliente.activo ? 'Activo' : 'Moroso' }}
        </p>
        <div class="mt-4 flex space-x-2">
            <button @click="onEdit(cliente)" class="rounded bg-blue-500 px-4 py-2 text-white hover:bg-blue-600">Editar</button>
            <button @click="onDelete(cliente)" class="rounded bg-red-500 px-4 py-2 text-white hover:bg-red-600">Eliminar</button>
        </div>
    </div>
</template>
