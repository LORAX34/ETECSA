<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <div class="flex min-h-screen flex-col items-center justify-center bg-gradient-to-r from-blue-500 to-blue-700 text-white">
        <header class="mb-8 text-center">
            <h1 class="text-4xl font-bold">Bienvenido a Central ETECSA</h1>
            <p class="mt-2 text-lg">Gestión eficiente de las centrales telefónicas</p>
        </header>

        <main class="w-full max-w-4xl rounded-lg bg-white p-6 text-black shadow-lg">
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                <!-- Información -->
                <div>
                    <h2 class="text-2xl font-semibold text-blue-700">¿Qué es Central ETECSA?</h2>
                    <p class="mt-4 text-gray-700">
                        Central ETECSA es una plataforma diseñada para optimizar la gestión de clientes, servicios y llamadas en las centrales
                        telefónicas. Ofrecemos herramientas avanzadas para monitorear y administrar los servicios de manera eficiente.
                    </p>
                    <ul class="mt-4 list-inside list-disc text-gray-700">
                        <li>Gestión de clientes residenciales y estatales</li>
                        <li>Monitoreo de llamadas con rastreo</li>
                        <li>Gestión de pagos mensuales</li>
                        <li>Estadísticas de uso de servicios</li>
                    </ul>
                </div>

                <!-- Acciones -->
                <div class="flex flex-col items-center justify-center">
                    <img src="/images/etecsa-logo.jpg" alt="Logotipo de ETECSA" class="mb-6 h-32 w-32" />
                    <div class="space-y-4">
                        <Link href="/login" class="block w-full rounded bg-blue-500 px-4 py-2 text-center font-semibold text-white hover:bg-blue-600">
                            Iniciar Sesión
                        </Link>
                        <Link
                            href="/register"
                            class="block w-full rounded bg-gray-200 px-4 py-2 text-center font-semibold text-blue-700 hover:bg-gray-300"
                        >
                            Registrarse
                        </Link>
                    </div>
                </div>
            </div>
        </main>

        <footer class="mt-8 text-center text-sm text-gray-200">&copy; 2025 Central ETECSA. Todos los derechos reservados.</footer>
    </div>
</template>
