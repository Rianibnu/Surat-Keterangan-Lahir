<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { Badge } from '@/components/ui/badge';
import { Activity, Search, Eye, RefreshCw, Filter, Calendar, User, FileText, Globe, Users } from 'lucide-vue-next';
import { ref, watch } from 'vue';
import { type BreadcrumbItem } from '@/types';

const props = defineProps<{
    activities: {
        data: Array<{
            id: number;
            log_name: string;
            description: string;
            subject_type: string | null;
            subject_id: number | null;
            causer: { id: number; name: string } | null;
            properties: Record<string, any>;
            event: string | null;
            created_at: string;
        }>;
        links: Array<{ url: string | null; label: string; active: boolean }>;
        current_page: number;
        last_page: number;
        total: number;
    };
    logTypes: string[];
    users: Array<{ id: number; name: string }>;
    filters: {
        type?: string;
        from?: string;
        to?: string;
        user_id?: string;
        search?: string;
        source?: string;
    };
}>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: '/dashboard' },
    { title: 'Activity Log', href: '/activity-logs' },
];

const search = ref(props.filters.search || '');
const type = ref(props.filters.type || 'all');
const userId = ref(props.filters.user_id || 'all');
const from = ref(props.filters.from || '');
const to = ref(props.filters.to || '');
const source = ref(props.filters.source || 'all');

const applyFilters = () => {
    router.get('/activity-logs', {
        search: search.value || undefined,
        type: type.value !== 'all' ? type.value : undefined,
        user_id: userId.value !== 'all' ? userId.value : undefined,
        from: from.value || undefined,
        to: to.value || undefined,
        source: source.value !== 'all' ? source.value : undefined,
    }, { preserveState: true });
};

const clearFilters = () => {
    search.value = '';
    type.value = 'all';
    userId.value = 'all';
    from.value = '';
    to.value = '';
    source.value = 'all';
    router.get('/activity-logs');
};

const formatDate = (dateString: string) => {
    const date = new Date(dateString);
    return new Intl.DateTimeFormat('id-ID', {
        day: '2-digit',
        month: 'short',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
    }).format(date);
};

const getEventColor = (event: string | null) => {
    switch(event) {
        case 'created': return 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400';
        case 'updated': return 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400';
        case 'deleted': return 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400';
        default: return 'bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-400';
    }
};

const getSubjectName = (subjectType: string | null) => {
    if (!subjectType) return '-';
    const parts = subjectType.split('\\');
    return parts[parts.length - 1];
};

// Debounce search
let searchTimeout: ReturnType<typeof setTimeout>;
watch(search, () => {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(applyFilters, 500);
});
</script>

<template>
    <Head title="Activity Log" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-6 p-6">
            <!-- Header Section -->
            <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                <div>
                    <h1 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white flex items-center gap-2">
                        <Activity class="h-7 w-7 text-indigo-600 dark:text-indigo-400" />
                        Activity Log
                    </h1>
                    <p class="text-sm text-muted-foreground mt-1">
                        Pantau semua aktivitas dalam sistem
                    </p>
                </div>
                <Button @click="clearFilters" variant="outline" size="sm">
                    <RefreshCw class="h-4 w-4 mr-2" />
                    Reset Filter
                </Button>
            </div>

            <!-- Source Tabs -->
            <div class="flex gap-2">
                <Button 
                    @click="source = 'all'; applyFilters()" 
                    :variant="source === 'all' ? 'default' : 'outline'"
                    size="sm"
                    class="gap-2"
                >
                    <Activity class="h-4 w-4" />
                    Semua
                </Button>
                <Button 
                    @click="source = 'user'; applyFilters()" 
                    :variant="source === 'user' ? 'default' : 'outline'"
                    size="sm"
                    class="gap-2"
                >
                    <Users class="h-4 w-4" />
                    User / Akun
                </Button>
                <Button 
                    @click="source = 'public'; applyFilters()" 
                    :variant="source === 'public' ? 'default' : 'outline'"
                    size="sm"
                    class="gap-2"
                >
                    <Globe class="h-4 w-4" />
                    Publik
                </Button>
            </div>

            <!-- Filters -->
            <Card class="shadow-sm border-0 dark:bg-gray-800/50">
                <CardHeader class="pb-4">
                    <CardTitle class="text-base flex items-center gap-2">
                        <Filter class="h-4 w-4" />
                        Filter Aktivitas
                    </CardTitle>
                </CardHeader>
                <CardContent>
                    <div class="grid gap-4 md:grid-cols-6">
                        <!-- Search -->
                        <div class="relative md:col-span-2">
                            <Search class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-muted-foreground" />
                            <Input
                                v-model="search"
                                placeholder="Cari aktivitas..."
                                class="pl-10"
                            />
                        </div>

                        <!-- Type Filter -->
                        <Select v-model="type" @update:modelValue="applyFilters">
                            <SelectTrigger>
                                <SelectValue placeholder="Tipe Log" />
                            </SelectTrigger>
                            <SelectContent>
                                <SelectItem value="all">Semua Tipe</SelectItem>
                                <SelectItem v-for="logType in logTypes" :key="logType" :value="logType">
                                    {{ logType }}
                                </SelectItem>
                            </SelectContent>
                        </Select>

                        <!-- User Filter -->
                        <Select v-model="userId" @update:modelValue="applyFilters">
                            <SelectTrigger>
                                <SelectValue placeholder="User" />
                            </SelectTrigger>
                            <SelectContent>
                                <SelectItem value="all">Semua User</SelectItem>
                                <SelectItem v-for="user in users" :key="user.id" :value="String(user.id)">
                                    {{ user.name }}
                                </SelectItem>
                            </SelectContent>
                        </Select>

                        <!-- Date Range -->
                        <div class="flex gap-2 items-center">
                            <div class="relative flex-1">
                                <label class="absolute -top-2 left-2 bg-white dark:bg-gray-800 px-1 text-xs text-muted-foreground">Dari</label>
                                <Input
                                    :value="from || ''"
                                    type="date"
                                    class="pt-2"
                                    @input="from = $event.target.value; applyFilters()"
                                />
                            </div>
                            <span class="text-muted-foreground">-</span>
                            <div class="relative flex-1">
                                <label class="absolute -top-2 left-2 bg-white dark:bg-gray-800 px-1 text-xs text-muted-foreground">Sampai</label>
                                <Input
                                    :value="to || ''"
                                    type="date"
                                    class="pt-2"
                                    @input="to = $event.target.value; applyFilters()"
                                />
                            </div>
                        </div>
                    </div>
                </CardContent>
            </Card>

            <!-- Activity Table -->
            <Card class="shadow-lg border-0 dark:bg-gray-800/50 backdrop-blur">
                <CardHeader class="border-b dark:border-gray-700 pb-4">
                    <div class="flex items-center justify-between">
                        <CardTitle class="text-lg">Log Aktivitas</CardTitle>
                        <Badge variant="secondary">{{ activities.total }} aktivitas</Badge>
                    </div>
                </CardHeader>
                <CardContent class="p-0">
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="bg-gray-50 dark:bg-gray-800/80 border-b dark:border-gray-700">
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Waktu</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">User</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Event</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Model</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Deskripsi</th>
                                    <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                                <tr 
                                    v-for="activity in activities.data" 
                                    :key="activity.id"
                                    class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors duration-150"
                                >
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center gap-2 text-sm text-muted-foreground">
                                            <Calendar class="h-4 w-4" />
                                            {{ formatDate(activity.created_at) }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center gap-2">
                                            <div class="h-8 w-8 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white text-xs font-bold">
                                                {{ activity.causer?.name?.charAt(0).toUpperCase() || '?' }}
                                            </div>
                                            <span class="text-sm font-medium">{{ activity.causer?.name || 'System' }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <Badge :class="getEventColor(activity.event)">
                                            {{ activity.event || 'unknown' }}
                                        </Badge>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center gap-2">
                                            <FileText class="h-4 w-4 text-muted-foreground" />
                                            <span class="text-sm">{{ getSubjectName(activity.subject_type) }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <p class="text-sm text-gray-700 dark:text-gray-300 max-w-md truncate">
                                            {{ activity.description }}
                                        </p>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <Link :href="`/activity-logs/${activity.id}`">
                                            <Button variant="ghost" size="sm">
                                                <Eye class="h-4 w-4" />
                                            </Button>
                                        </Link>
                                    </td>
                                </tr>
                                <tr v-if="activities.data.length === 0">
                                    <td colspan="6" class="px-6 py-12 text-center text-muted-foreground">
                                        <Activity class="h-12 w-12 mx-auto mb-3 opacity-50" />
                                        <p>Tidak ada aktivitas ditemukan</p>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div v-if="activities.last_page > 1" class="flex items-center justify-between px-6 py-4 border-t dark:border-gray-700">
                        <p class="text-sm text-muted-foreground">
                            Halaman {{ activities.current_page }} dari {{ activities.last_page }}
                        </p>
                        <div class="flex gap-2">
                            <template v-for="link in activities.links" :key="link.label">
                                <Link
                                    v-if="link.url"
                                    :href="link.url"
                                    class="px-3 py-1 text-sm rounded-md transition-colors"
                                    :class="link.active 
                                        ? 'bg-primary text-primary-foreground' 
                                        : 'bg-secondary hover:bg-secondary/80'"
                                    v-html="link.label"
                                />
                                <span
                                    v-else
                                    class="px-3 py-1 text-sm text-muted-foreground"
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
