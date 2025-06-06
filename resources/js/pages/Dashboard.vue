<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { ref } from 'vue';

const stats = ref({
    totalClientes: 120,
    clientesActivos: 100,
    clientesMorosos: 20,
    llamadasHoy: 45,
    pagosPendientes: 15,
});
</script>

<template>
    <AppLayout>
        <div class="text-center">
            <h1 class="text-3xl font-bold text-[#005BAC]">Bienvenido a la Central Telefónica</h1>
            <p class="mt-4 text-lg text-gray-600">Administra tus clientes, llamadas y pagos de manera eficiente desde este sistema.</p>
        </div>

        <div class="mt-8 grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
            <div class="rounded-lg bg-blue-500 p-6 text-white shadow-lg">
                <h2 class="text-xl font-bold">Total de Clientes</h2>
                <p class="mt-2 text-2xl">{{ stats.totalClientes }}</p>
            </div>
            <div class="rounded-lg bg-green-500 p-6 text-white shadow-lg">
                <h2 class="text-xl font-bold">Clientes Activos</h2>
                <p class="mt-2 text-2xl">{{ stats.clientesActivos }}</p>
            </div>
            <div class="rounded-lg bg-red-500 p-6 text-white shadow-lg">
                <h2 class="text-xl font-bold">Clientes Morosos</h2>
                <p class="mt-2 text-2xl">{{ stats.clientesMorosos }}</p>
            </div>
            <div class="rounded-lg bg-yellow-500 p-6 text-white shadow-lg">
                <h2 class="text-xl font-bold">Llamadas Hoy</h2>
                <p class="mt-2 text-2xl">{{ stats.llamadasHoy }}</p>
            </div>
            <div class="rounded-lg bg-purple-500 p-6 text-white shadow-lg">
                <h2 class="text-xl font-bold">Pagos Pendientes</h2>
                <p class="mt-2 text-2xl">{{ stats.pagosPendientes }}</p>
            </div>
        </div>
    </AppLayout>
</template>
