<script setup lang="ts">
import ClienteCard from '@/components/ClienteCard.vue';
import { Link } from '@inertiajs/vue3';

defineProps({
    clientes: Array,
});

const handleEdit = (cliente) => {
    // Lógica para editar cliente
    console.log('Editar cliente:', cliente);
};

const handleDelete = (cliente) => {
    // Lógica para eliminar cliente
    console.log('Eliminar cliente:', cliente);
};
</script>

<template>
    <div>
        <div class="mb-6 flex items-center justify-between">
            <h1 class="text-xl font-bold text-[#005BAC]">Gestión de Clientes</h1>
            <Link href="/clientes/create" class="rounded bg-blue-500 px-4 py-2 text-white hover:bg-blue-600">Agregar Cliente</Link>
        </div>

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
            <ClienteCard v-for="cliente in clientes" :key="cliente.id" :cliente="cliente" :onEdit="handleEdit" :onDelete="handleDelete" />
        </div>
    </div>
</template>
