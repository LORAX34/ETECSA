<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <div class="flex min-h-screen items-center justify-center bg-gradient-to-r from-blue-500 to-blue-700 text-white">
        <div class="w-full max-w-md rounded-lg bg-white p-6 text-black shadow-lg">
            <h1 class="text-center text-2xl font-bold text-blue-700">Registrarse</h1>
            <p class="mt-2 text-center text-sm text-gray-600">Introduce tus datos para crear una cuenta</p>
            <form @submit.prevent="submit" class="mt-6 space-y-4">
                <div>
                    <label for="name" class="block text-sm font-medium">Nombre Completo</label>
                    <input v-model="form.name" id="name" type="text" class="w-full rounded border p-2" required autofocus />
                </div>
                <div>
                    <label for="email" class="block text-sm font-medium">Correo Electrónico</label>
                    <input v-model="form.email" id="email" type="email" class="w-full rounded border p-2" required />
                </div>
                <div>
                    <label for="password" class="block text-sm font-medium">Contraseña</label>
                    <input v-model="form.password" id="password" type="password" class="w-full rounded border p-2" required />
                </div>
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium">Confirmar Contraseña</label>
                    <input
                        v-model="form.password_confirmation"
                        id="password_confirmation"
                        type="password"
                        class="w-full rounded border p-2"
                        required
                    />
                </div>
                <button
                    type="submit"
                    class="w-full rounded bg-blue-500 px-4 py-2 font-semibold text-white hover:bg-blue-600"
                    :disabled="form.processing"
                >
                    Registrarse
                </button>
            </form>
            <p class="mt-4 text-center text-sm text-gray-600">
                ¿Ya tienes una cuenta?
                <a href="/login" class="text-blue-500 hover:underline">Inicia sesión aquí</a>
            </p>
        </div>
    </div>
</template>
