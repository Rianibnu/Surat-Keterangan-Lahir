<script setup lang="ts">
import {
    SidebarGroup,
    SidebarGroupLabel,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
} from '@/components/ui/sidebar';
import { urlIsActive } from '@/lib/utils';
import { type NavItem } from '@/types';
import { Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps<{
    items: NavItem[];
}>();

const page = usePage();

// Check if a user has a specific role
const userHasRole = (userRole: string | null, allowedRoles: string[] = []): boolean => {
    if (!allowedRoles.length) return true; // If no roles specified, allow all
    if (!userRole) return false;
    return allowedRoles.includes(userRole);
};

// Filter items based on user role
const filteredItems = computed(() => {
    const userRole = page.props.auth?.user?.role?.name || null;
    return props.items.filter(item => userHasRole(userRole, item.roles));
});
</script>

<template>
    <SidebarGroup class="px-2 py-0">
        <SidebarGroupLabel>Menu</SidebarGroupLabel>
        <SidebarMenu>
            <SidebarMenuItem v-for="item in filteredItems" :key="item.title">
                <SidebarMenuButton
                    as-child
                    :is-active="urlIsActive(item.href, page.url)"
                    :tooltip="item.title"
                >
                    <Link :href="item.href">
                        <component :is="item.icon" v-if="item.icon" />
                        <span>{{ item.title }}</span>
                    </Link>
                </SidebarMenuButton>
            </SidebarMenuItem>
        </SidebarMenu>
    </SidebarGroup>
</template>
