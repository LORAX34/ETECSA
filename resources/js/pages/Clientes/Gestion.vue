<script setup lang="ts">
import ClienteCard from '@/components/ClienteCard.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

defineProps({
    clientes: Array, // Recibir los datos de clientes desde la ruta
});

const showModal = ref(false);
const isEditing = ref(false);
const selectedCliente = ref(null);

const form = useForm({
    nombre: '',
    carnet: '',
    direccion: '',
    telefono: '',
    tipo: 'RESIDENCIAL',
    activo: true,
    matutino: false,
    rastreo: false,
    linea_arrendada: false,
});

const openCreateModal = () => {
    isEditing.value = false;
    form.reset();
    showModal.value = true;
};

const openEditModal = (cliente) => {
    isEditing.value = true;
    selectedCliente.value = cliente;
    form.reset({
        nombre: cliente.nombre,
        carnet: cliente.carnet,
        direccion: cliente.direccion,
        telefono: cliente.telefono,
        tipo: cliente.tipo,
        activo: cliente.activo,
        matutino: cliente.matutino || false,
        rastreo: cliente.rastreo || false,
        linea_arrendada: cliente.linea_arrendada || false,
    });
    showModal.value = true;
};

const closeModal = () => {
    showModal.value = false;
};

const handleSubmit = () => {
    if (isEditing.value) {
        form.put(`/clientes/${selectedCliente.value.id}`, {
            onSuccess: () => {
                closeModal();
                location.reload();
            },
        });
    } else {
        form.post('/clientes', {
            onSuccess: () => {
                closeModal();
                location.reload();
            },
        });
    }
};

const handleDelete = (cliente) => {
    if (confirm(`¿Estás seguro de que deseas eliminar a ${cliente.nombre}?`)) {
        form.delete(`/clientes/${cliente.id}`, {
            onSuccess: () => {
                location.reload();
            },
        });
    }
};
</script>

<template>
    <AppLayout>
        <div class="mb-6 flex items-center justify-between">
            <h1 class="text-xl font-bold text-[#005BAC]">Gestión de Clientes</h1>
            <button @click="openCreateModal" class="rounded bg-blue-500 px-4 py-2 text-white hover:bg-blue-600">Agregar Cliente</button>
        </div>

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
            <ClienteCard v-for="cliente in clientes" :key="cliente.id" :cliente="cliente" :onEdit="openEditModal" :onDelete="handleDelete" />
        </div>

        <div v-if="showModal" class="bg-opacity-50 fixed inset-0 flex items-center justify-center bg-black">
            <div class="w-full max-w-md rounded-lg bg-white p-6 shadow-lg">
                <h2 class="mb-4 text-lg font-bold">{{ isEditing ? 'Editar Cliente' : 'Agregar Cliente' }}</h2>
                <form @submit.prevent="handleSubmit" class="space-y-4">
                    <div>
                        <label for="nombre" class="block text-sm font-medium">Nombre</label>
                        <input v-model="form.nombre" id="nombre" type="text" class="w-full rounded border p-2" required />
                    </div>
                    <div>
                        <label for="carnet" class="block text-sm font-medium">Carnet</label>
                        <input v-model="form.carnet" id="carnet" type="text" class="w-full rounded border p-2" required />
                    </div>
                    <div>
                        <label for="direccion" class="block text-sm font-medium">Dirección</label>
                        <input v-model="form.direccion" id="direccion" type="text" class="w-full rounded border p-2" required />
                    </div>
                    <div>
                        <label for="telefono" class="block text-sm font-medium">Teléfono</label>
                        <input v-model="form.telefono" id="telefono" type="text" class="w-full rounded border p-2" required />
                    </div>
                    <div>
                        <label for="tipo" class="block text-sm font-medium">Tipo</label>
                        <select v-model="form.tipo" id="tipo" class="w-full rounded border p-2">
                            <option value="RESIDENCIAL">Residencial</option>
                            <option value="ESTATAL">Estatal</option>
                        </select>
                    </div>
                    <div class="space-y-3">
                        <label class="flex items-center space-x-2">
                            <input type="checkbox" v-model="form.activo" />
                            <span>Cliente Activo</span>
                        </label>
                        
                        <!-- Servicios Adicionales -->
                        <div class="border-t pt-3">
                            <h4 class="text-sm font-medium text-gray-700 mb-2">Servicios Adicionales</h4>
                            <div class="space-y-2">
                                <label class="flex items-center space-x-2">
                                    <input type="checkbox" v-model="form.matutino" />
                                    <span class="text-sm">Servicio Matutino (+$1.00/mes)</span>
                                </label>
                                <label class="flex items-center space-x-2" v-if="form.tipo === 'RESIDENCIAL'">
                                    <input type="checkbox" v-model="form.rastreo" />
                                    <span class="text-sm">Servicio de Rastreo (+$1.00/mes)</span>
                                </label>
                                <label class="flex items-center space-x-2" v-if="form.tipo === 'ESTATAL'">
                                    <input type="checkbox" v-model="form.linea_arrendada" />
                                    <span class="text-sm">Línea Arrendada (+$1.00/mes)</span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="flex justify-end space-x-2">
                        <button type="button" @click="closeModal" class="rounded bg-gray-300 px-4 py-2 hover:bg-gray-400">Cancelar</button>
                        <button type="submit" class="rounded bg-blue-500 px-4 py-2 text-white hover:bg-blue-600" :disabled="form.processing">
                            {{ isEditing ? 'Guardar Cambios' : 'Agregar' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AppLayout>
</template>
