<script setup lang="ts">
import AppLayout from '@/layouts/app/AppSidebarLayout.vue';
import type { BreadcrumbItemType } from '@/types';

interface Props {
    breadcrumbs?: BreadcrumbItemType[];
}

withDefaults(defineProps<Props>(), {
    breadcrumbs: () => [],
});
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <slot />
    </AppLayout>
</template>

<style scoped>
/* Estilos para el layout */
header {
    background-color: var(--primary-color);
    color: var(--background-color);
}

header a {
    color: var(--background-color);
}

header a:hover {
    color: var(--primary-color-light);
}
</style>
