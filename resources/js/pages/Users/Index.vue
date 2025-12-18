<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { Badge } from '@/components/ui/badge';
import { Users, Search, Plus, Pencil, Trash2, Shield } from 'lucide-vue-next';
import { ref, watch } from 'vue';
import { type BreadcrumbItem } from '@/types';

const props = defineProps<{
    users: {
        data: Array<{
            id: number;
            name: string;
            email: string;
            email_verified_at: string | null;
            created_at: string;
            roles: Array<{ name: string }>;
        }>;
        links: Array<{ url: string | null; label: string; active: boolean }>;
        current_page: number;
        last_page: number;
        total: number;
    };
    roles: Array<{ id: number; name: string }>;
    filters: {
        search?: string;
        role?: string;
    };
}>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: '/dashboard' },
    { title: 'Manajemen User', href: '/users' },
];

const search = ref(props.filters.search || '');
const roleFilter = ref(props.filters.role || 'all');

const applyFilters = () => {
    router.get('/users', {
        search: search.value || undefined,
        role: roleFilter.value !== 'all' ? roleFilter.value : undefined,
    }, { preserveState: true });
};

const deleteUser = (id: number, name: string) => {
    if (confirm(`Apakah Anda yakin ingin menghapus user "${name}"?`)) {
        router.delete(`/users/${id}`);
    }
};

const formatDate = (dateString: string) => {
    const date = new Date(dateString);
    return new Intl.DateTimeFormat('id-ID', {
        day: '2-digit',
        month: 'short',
        year: 'numeric',
    }).format(date);
};

const getRoleBadgeColor = (role: string) => {
    switch(role) {
        case 'admin': return 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400';
        case 'staf': return 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400';
        case 'dokter': return 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400';
        case 'viewer': return 'bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-400';
        default: return 'bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-400';
    }
};

// Debounce search
let searchTimeout: ReturnType<typeof setTimeout>;
watch(search, () => {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(applyFilters, 500);
});
</script>

<template>
    <Head title="Manajemen User" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-6 p-6">
            <!-- Header Section -->
            <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                <div>
                    <h1 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white flex items-center gap-2">
                        <Users class="h-7 w-7 text-indigo-600 dark:text-indigo-400" />
                        Manajemen User
                    </h1>
                    <p class="text-sm text-muted-foreground mt-1">
                        Kelola pengguna dan hak akses sistem
                    </p>
                </div>
                <Link href="/users/create">
                    <Button class="bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700">
                        <Plus class="h-4 w-4 mr-2" />
                        Tambah User
                    </Button>
                </Link>
            </div>

            <!-- Stats Cards -->
            <div class="grid gap-4 md:grid-cols-4">
                <Card class="bg-gradient-to-br from-blue-50 to-indigo-100 dark:from-blue-950 dark:to-indigo-900 border-0 shadow-md">
                    <CardContent class="p-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-blue-600 dark:text-blue-400">Total User</p>
                                <p class="text-2xl font-bold text-blue-800 dark:text-blue-200">{{ users.total }}</p>
                            </div>
                            <Users class="h-8 w-8 text-blue-600 dark:text-blue-400" />
                        </div>
                    </CardContent>
                </Card>
                <Card v-for="role in roles" :key="role.id" class="bg-gradient-to-br from-gray-50 to-slate-100 dark:from-gray-900 dark:to-slate-800 border-0 shadow-md">
                    <CardContent class="p-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-600 dark:text-gray-400 capitalize">{{ role.name }}</p>
                                <p class="text-2xl font-bold text-gray-800 dark:text-gray-200">
                                    {{ users.data.filter(u => u.roles.some(r => r.name === role.name)).length }}
                                </p>
                            </div>
                            <Shield class="h-6 w-6 text-gray-500" />
                        </div>
                    </CardContent>
                </Card>
            </div>

            <!-- Filters & Table -->
            <Card class="shadow-lg border-0 dark:bg-gray-800/50 backdrop-blur">
                <CardHeader class="border-b dark:border-gray-700 pb-4">
                    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                        <CardTitle class="text-lg">Daftar User</CardTitle>
                        <div class="flex gap-3">
                            <!-- Search -->
                            <div class="relative">
                                <Search class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-muted-foreground" />
                                <Input
                                    v-model="search"
                                    placeholder="Cari user..."
                                    class="pl-10 w-64"
                                />
                            </div>
                            <!-- Role Filter -->
                            <Select v-model="roleFilter" @update:modelValue="applyFilters">
                                <SelectTrigger class="w-40">
                                    <SelectValue placeholder="Semua Role" />
                                </SelectTrigger>
                            <SelectContent>
                                    <SelectItem value="all">Semua Role</SelectItem>
                                    <SelectItem v-for="role in roles" :key="role.id" :value="role.name">
                                        {{ role.name }}
                                    </SelectItem>
                                </SelectContent>
                            </Select>
                        </div>
                    </div>
                </CardHeader>
                <CardContent class="p-0">
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="bg-gray-50 dark:bg-gray-800/80 border-b dark:border-gray-700">
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">#</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">User</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Role</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Dibuat</th>
                                    <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                                <tr 
                                    v-for="(user, index) in users.data" 
                                    :key="user.id"
                                    class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors duration-150"
                                >
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-muted-foreground">
                                        {{ (users.current_page - 1) * 15 + index + 1 }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center gap-3">
                                            <div class="h-10 w-10 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white font-bold">
                                                {{ user.name.charAt(0).toUpperCase() }}
                                            </div>
                                            <div>
                                                <p class="font-medium text-gray-900 dark:text-white">{{ user.name }}</p>
                                                <p class="text-sm text-muted-foreground">{{ user.email }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex flex-wrap gap-1">
                                            <Badge 
                                                v-for="role in user.roles" 
                                                :key="role.name"
                                                :class="getRoleBadgeColor(role.name)"
                                            >
                                                {{ role.name }}
                                            </Badge>
                                            <span v-if="user.roles.length === 0" class="text-sm text-muted-foreground">-</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <Badge v-if="user.email_verified_at" class="bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400">
                                            Verified
                                        </Badge>
                                        <Badge v-else class="bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400">
                                            Pending
                                        </Badge>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-muted-foreground">
                                        {{ formatDate(user.created_at) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <div class="flex items-center justify-center gap-2">
                                            <Link :href="`/users/${user.id}/edit`">
                                                <Button variant="ghost" size="sm" class="text-blue-600 hover:text-blue-700">
                                                    <Pencil class="h-4 w-4" />
                                                </Button>
                                            </Link>
                                            <Button 
                                                variant="ghost" 
                                                size="sm" 
                                                class="text-red-600 hover:text-red-700"
                                                @click="deleteUser(user.id, user.name)"
                                            >
                                                <Trash2 class="h-4 w-4" />
                                            </Button>
                                        </div>
                                    </td>
                                </tr>
                                <tr v-if="users.data.length === 0">
                                    <td colspan="6" class="px-6 py-12 text-center text-muted-foreground">
                                        <Users class="h-12 w-12 mx-auto mb-3 opacity-50" />
                                        <p>Tidak ada user ditemukan</p>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div v-if="users.last_page > 1" class="flex items-center justify-between px-6 py-4 border-t dark:border-gray-700">
                        <p class="text-sm text-muted-foreground">
                            Halaman {{ users.current_page }} dari {{ users.last_page }}
                        </p>
                        <div class="flex gap-2">
                            <template v-for="link in users.links" :key="link.label">
                                <Link
                                    v-if="link.url"
                                    :href="link.url"
                                    class="px-3 py-1 text-sm rounded-md transition-colors"
                                    :class="link.active 
                                        ? 'bg-primary text-primary-foreground' 
                                        : 'bg-secondary hover:bg-secondary/80'"
                                    v-html="link.label"
                                />
                            </template>
                        </div>
                    </div>
                </CardContent>
            </Card>
        </div>
    </AppLayout>
</template>
