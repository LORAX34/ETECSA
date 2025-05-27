<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <div class="flex min-h-screen items-center justify-center bg-gradient-to-r from-blue-500 to-blue-700 text-white">
        <div class="w-full max-w-md rounded-lg bg-white p-6 text-black shadow-lg">
            <h1 class="text-center text-2xl font-bold text-blue-700">Iniciar Sesión</h1>
            <p class="mt-2 text-center text-sm text-gray-600">Introduce tus credenciales para acceder a tu cuenta</p>
            <form @submit.prevent="submit" class="mt-6 space-y-4">
                <div>
                    <label for="email" class="block text-sm font-medium">Correo Electrónico</label>
                    <input v-model="form.email" id="email" type="email" class="w-full rounded border p-2" required autofocus />
                </div>
                <div>
                    <label for="password" class="block text-sm font-medium">Contraseña</label>
                    <input v-model="form.password" id="password" type="password" class="w-full rounded border p-2" required />
                </div>
                <div class="flex items-center space-x-2">
                    <input v-model="form.remember" id="remember" type="checkbox" />
                    <label for="remember" class="text-sm">Recuérdame</label>
                </div>
                <button
                    type="submit"
                    class="w-full rounded bg-blue-500 px-4 py-2 font-semibold text-white hover:bg-blue-600"
                    :disabled="form.processing"
                >
                    Iniciar Sesión
                </button>
            </form>
            <p class="mt-4 text-center text-sm text-gray-600">
                ¿No tienes una cuenta?
                <a href="/register" class="text-blue-500 hover:underline">Regístrate aquí</a>
            </p>
        </div>
    </div>
</template>
