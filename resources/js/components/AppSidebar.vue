<script setup lang="ts">
import NavFooter from '@/components/NavFooter.vue';
import NavMain from '@/components/NavMain.vue';
import NavUser from '@/components/NavUser.vue';
import {
    Sidebar,
    SidebarContent,
    SidebarFooter,
    SidebarHeader,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
} from '@/components/ui/sidebar';
import { dashboard } from '@/routes';
import { Link, usePage } from '@inertiajs/vue3';
import { LayoutGrid, Baby, Stethoscope, Users, Activity, Shield } from 'lucide-vue-next';
import AppLogo from './AppLogo.vue';
import { type NavItem } from '@/types';
import { computed } from 'vue';

const page = usePage();

// Check if user has a specific permission
const userCan = (permission: string): boolean => {
    const permissions = page.props.auth?.user?.permissions || [];
    const roles = page.props.auth?.user?.roles || [];
    
    // Admin has all permissions
    if (roles.some((r: any) => r.name === 'admin')) return true;
    
    return permissions.some((p: any) => p.name === permission);
};

const mainNavItems: NavItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
        icon: LayoutGrid,
    },
    {
        title: 'Data Kelahiran',
        href: '/birth-records',
        icon: Baby,
    },
    {
        title: 'Data Dokter',
        href: '/doctors',
        icon: Stethoscope,
    },
];

const adminNavItems = computed<NavItem[]>(() => {
    const items: NavItem[] = [];
    
    if (userCan('users.view')) {
        items.push({
            title: 'Manajemen User',
            href: '/users',
            icon: Users,
        });
    }
    
    if (userCan('activity-log.view')) {
        items.push({
            title: 'Activity Log',
            href: '/activity-logs',
            icon: Activity,
        });
    }
    
    return items;
});
</script>

<template>
    <Sidebar collapsible="icon" variant="floating">
        <SidebarHeader>
            <SidebarMenu>
                <SidebarMenuItem>
                    <SidebarMenuButton size="lg" as-child>
                        <Link :href="dashboard()">
                            <AppLogo />
                        </Link>
                    </SidebarMenuButton>
                </SidebarMenuItem>
            </SidebarMenu>
        </SidebarHeader>

        <SidebarContent>
            <NavMain :items="mainNavItems" />
            <NavMain v-if="adminNavItems.length > 0" :items="adminNavItems" />
        </SidebarContent>

        <SidebarFooter>
            <NavFooter :items="[]" />
            <NavUser />
        </SidebarFooter>
    </Sidebar>
    <slot />
</template>