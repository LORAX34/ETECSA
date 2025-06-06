<script setup lang="ts">
import NavMain from '@/components/NavMain.vue';
import NavUser from '@/components/NavUser.vue';
import { Sidebar, SidebarContent, SidebarFooter, SidebarHeader, SidebarMenu, SidebarMenuButton, SidebarMenuItem } from '@/components/ui/sidebar';
import { type NavItem } from '@/types';
import { Link, useForm } from '@inertiajs/vue3';
import { Award, FileText, LogOut, PhoneCall, Search, Users } from 'lucide-vue-next';
import AppLogo from './AppLogo.vue';

const mainNavItems: NavItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
        icon: Users,
    },
    {
        title: 'Gestión de Clientes',
        href: '/clientes',
        icon: Users,
    },
    {
        title: 'Buscar Llamadas',
        href: '/clientes/buscar-llamadas',
        icon: Search,
    },
    {
        title: 'Pagos Mensuales',
        href: '/clientes/pagos-mensuales',
        icon: FileText,
    },
    {
        title: 'Clientes con Tele Selección',
        href: '/clientes/tele-seleccion',
        icon: PhoneCall,
    },
    {
        title: 'Cliente Top Servicios',
        href: '/clientes/mas-servicios',
        icon: Award,
    },
];

const logoutForm = useForm({});
const handleLogout = () => {
    logoutForm.post('/logout', {
        onSuccess: () => {
            window.location.href = '/';
        },
    });
};
</script>

<template>
    <Sidebar collapsible="icon" variant="inset">
        <SidebarHeader>
            <SidebarMenu>
                <SidebarMenuItem>
                    <SidebarMenuButton size="lg" as-child>
                        <Link :href="route('dashboard')">
                            <AppLogo />
                        </Link>
                    </SidebarMenuButton>
                </SidebarMenuItem>
            </SidebarMenu>
        </SidebarHeader>

        <SidebarContent>
            <NavMain :items="mainNavItems" />
        </SidebarContent>

        <SidebarFooter>
            <NavUser />
            <button @click="handleLogout" class="mt-4 flex items-center space-x-2 rounded bg-red-500 px-4 py-2 text-white hover:bg-red-600">
                <LogOut />
                <span>Cerrar Sesión</span>
            </button>
        </SidebarFooter>
    </Sidebar>
    <slot />
</template>
