<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { ArcElement, BarController, BarElement, CategoryScale, Chart as ChartJS, Legend, LinearScale, PieController, Title, Tooltip } from 'chart.js';
import { BarChart, PieChart } from 'vue-chart-3';

// Registrar los componentes y controladores de Chart.js
ChartJS.register(Title, Tooltip, Legend, ArcElement, PieController, BarController, BarElement, CategoryScale, LinearScale);

// Datos para gráficos
const pieChartData = {
    labels: ['Activos', 'Morosos', 'Inactivos'],
    datasets: [
        {
            label: 'Clientes',
            data: [100, 20, 10], // Datos de prueba
            backgroundColor: ['#4CAF50', '#FF5722', '#9E9E9E'],
        },
    ],
};

const pieChartOptions = {
    responsive: true,
    plugins: {
        legend: {
            position: 'top',
        },
        title: {
            display: true,
            text: 'Estado de Clientes',
        },
    },
};

const barChartData = {
    labels: ['Llamadas Realizadas', 'Servicios Contratados', 'Pagos Pendientes'],
    datasets: [
        {
            label: 'Estadísticas Generales',
            data: [300, 150, 45], // Datos de prueba
            backgroundColor: ['#FFC107', '#673AB7', '#FF5722'],
        },
    ],
};

const barChartOptions = {
    responsive: true,
    plugins: {
        legend: {
            position: 'top',
        },
        title: {
            display: true,
            text: 'Estadísticas Generales',
        },
    },
};
</script>

<template>
    <AppLayout>
        <div class="flex min-h-screen flex-col items-center bg-[#f8fafc] py-10">
            <div class="w-full max-w-6xl px-6">
                <h1 class="mb-6 text-center text-3xl font-bold text-[#004080]">Dashboard</h1>

                <!-- Gráfico de Estado de Clientes -->
                <div class="mb-8 rounded-lg bg-white p-6 shadow-lg">
                    <h2 class="mb-4 text-xl font-bold text-[#004080]">Estado de Clientes</h2>
                    <PieChart :chart-data="pieChartData" :chart-options="pieChartOptions" />
                </div>

                <!-- Gráfico de Estadísticas Generales -->
                <div class="mb-8 rounded-lg bg-white p-6 shadow-lg">
                    <h2 class="mb-4 text-xl font-bold text-[#004080]">Estadísticas Generales</h2>
                    <BarChart :chart-data="barChartData" :chart-options="barChartOptions" />
                </div>
            </div>
        </div>
    </AppLayout>
</template>
