<script setup lang="ts">
import NavFooter from '@/components/NavFooter.vue';
import NavMain from '@/components/NavMain.vue';
import NavUser from '@/components/NavUser.vue';
import { Sidebar, SidebarContent, SidebarFooter, SidebarHeader, SidebarMenu, SidebarMenuButton, SidebarMenuItem } from '@/components/ui/sidebar';
import { type NavItem } from '@/types';
import { Link } from '@inertiajs/vue3';
import { Calendar, BriefcaseBusiness, LayoutGrid, Baby, fileTe } from 'lucide-vue-next';
import AppLogo from './AppLogo.vue';
import AppLogoIcon2 from './AppLogoIcon2.vue';

const mainNavItems: NavItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
        icon: LayoutGrid,
    },
    {
        title: 'Babysitter',
        href: '/search',
        icon: Baby,
    },
    {
        title: 'Mes réservations',
        href: '/reservations',
        icon: Calendar,
    },
    {
        title: 'Mes Jobs',
        href: '/works',
        icon: BriefcaseBusiness,
    },
    {
        title: 'Mes Factures',
        href: '/works',
        icon: BriefcaseBusiness,
    },
];

</script>

<template>
    <Sidebar collapsible="icon" variant="inset">
        <SidebarHeader class="py-6">
            <Link :href="route('dashboard')" class="flex items-center justify-center">
            <!-- Adjust h-12 (3rem) or h-16 (4rem) as you like -->
            <AppLogoIcon2 class="h-16 w-auto" />
            </Link>
        </SidebarHeader>
        <SidebarContent>
            <NavMain :items="mainNavItems" />
        </SidebarContent>

        <SidebarFooter>
            <NavUser />
        </SidebarFooter>
    </Sidebar>
    <slot />
</template>
