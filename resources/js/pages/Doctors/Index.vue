<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';

import { 
    Card,
    CardContent,
    CardDescription,
    CardFooter,
    CardHeader,
    CardTitle,
} from '@/components/ui/card';
import { 
    DropdownMenu, 
    DropdownMenuContent, 
    DropdownMenuItem, 
    DropdownMenuLabel, 
    DropdownMenuSeparator, 
    DropdownMenuTrigger 
} from '@/components/ui/dropdown-menu';
import { 
    Plus, 
    Search, 
    MoreHorizontal, 
    Pencil, 
    Trash2, 
    Stethoscope, 
    Filter
} from 'lucide-vue-next';
import { ref, watch } from 'vue';
import { Badge } from '@/components/ui/badge';
import { usePermissions } from '@/composables/usePermissions';

const { can } = usePermissions();

const props = defineProps<{
    doctors: {
        data: any[];
        links: any[];
        current_page: number;
        per_page: number;
        from: number;
        to: number;
        total: number;
    };
    filters: {
        search?: string;
    };
}>();

const search = ref(props.filters.search || '');

watch(search, (value) => {
    router.get(
        route('doctors.index'),
        { search: value },
        { preserveState: true, replace: true }
    );
});

const deleteDoctor = (id: number) => {
    if (confirm('Apakah Anda yakin ingin menghapus data dokter ini?')) {
        router.delete(route('doctors.destroy', id));
    }
};
</script>

<template>
    <Head title="Data Dokter" />

    <AppLayout :breadcrumbs="[
        { title: 'Dashboard', href: '/dashboard' },
        { title: 'Data Dokter', href: '/doctors' },
    ]">
        <div class="flex flex-col gap-6 p-4 md:p-6 min-h-screen bg-slate-50/50 dark:bg-slate-900/50">
            <!-- Header Section -->
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                <div>
                    <h1 class="text-2xl font-bold tracking-tight text-slate-900 dark:text-slate-100">
                        Manajemen Dokter
                    </h1>
                    <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">
                        Kelola data dokter penanggung jawab persalinan.
                    </p>
                </div>
                <!-- Button Tambah Dokter - dengan permission check -->
                <div v-if="can('doctors.create')" class="flex items-center gap-2">
                    <Button as-child class="bg-indigo-600 hover:bg-indigo-700 text-white shadow-sm shadow-indigo-200 dark:shadow-none transition-all">
                        <Link :href="route('doctors.create')">
                            <Plus class="mr-2 h-4 w-4" />
                            Tambah Dokter
                        </Link>
                    </Button>
                </div>
                <div v-else class="relative group">
                    <Button 
                        disabled 
                        class="opacity-50 cursor-not-allowed"
                    >
                        <Plus class="mr-2 h-4 w-4" />
                        Tambah Dokter
                    </Button>
                    <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 px-3 py-2 bg-gray-900 text-white text-xs rounded-lg opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap z-50">
                        Anda tidak memiliki akses untuk menambah dokter
                        <div class="absolute top-full left-1/2 -translate-x-1/2 border-4 border-transparent border-t-gray-900"></div>
                    </div>
                </div>
            </div>

            <!-- Stats Overview (Optional, simple stats could be added here later) -->

            <!-- Main Content Card -->
            <Card class="border-slate-200 dark:border-slate-800 shadow-sm">
                <CardHeader class="border-b border-slate-100 dark:border-slate-800/50 pb-4 bg-white dark:bg-slate-900 rounded-t-xl">
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                        <CardTitle class="text-lg font-semibold flex items-center gap-2">
                           <div class="p-2 bg-indigo-50 dark:bg-indigo-900/20 rounded-lg">
                                <Stethoscope class="h-5 w-5 text-indigo-600 dark:text-indigo-400" />
                           </div>
                           Daftar Dokter
                        </CardTitle>
                        
                        <!-- Search & Filter Area -->
                        <div class="flex items-center gap-2 w-full md:w-auto">
                            <div class="relative w-full md:w-64">
                                <Search class="absolute left-2.5 top-2.5 h-4 w-4 text-slate-400" />
                                <Input 
                                    v-model="search"
                                    type="search" 
                                    placeholder="Cari nama, RS, atau SIP..." 
                                    class="pl-9 h-9 border-slate-200 dark:border-slate-700 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg text-sm bg-slate-50/50 dark:bg-slate-900"
                                />
                            </div>
                            <Button variant="outline" size="icon" class="h-9 w-9 shrink-0 border-slate-200 dark:border-slate-700 text-slate-500">
                                <Filter class="h-4 w-4" />
                            </Button>
                        </div>
                    </div>
                </CardHeader>

                <CardContent class="p-0">
                    <div class="relative w-full overflow-auto">
                        <table class="w-full caption-bottom text-sm">
                            <thead class="bg-slate-50/80 dark:bg-slate-800/50 [&_tr]:border-b">
                                <tr class="border-b transition-colors hover:bg-muted/50 data-[state=selected]:bg-muted">
                                    <th class="h-12 px-4 text-left align-middle font-semibold text-muted-foreground w-[50px] text-center">#</th>
                                    <th class="h-12 px-4 text-left align-middle font-semibold text-muted-foreground">Nama Dokter</th>
                                    <th class="h-12 px-4 text-left align-middle font-semibold text-muted-foreground">Rumah Sakit</th>
                                    <th class="h-12 px-4 text-left align-middle font-semibold text-muted-foreground">No. SIP</th>
                                    <th class="h-12 px-4 align-middle font-semibold text-muted-foreground text-right w-[100px]">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="[&_tr:last-child]:border-0">
                                <tr v-if="doctors.data.length === 0" class="border-b transition-colors hover:bg-muted/50">
                                    <td colspan="5" class="p-4 align-middle h-24 text-center text-slate-500">
                                        Belum ada data dokter.
                                    </td>
                                </tr>
                                
                                <tr v-for="(doctor, index) in doctors.data" :key="doctor.id" class="border-b transition-colors hover:bg-slate-50/50 dark:hover:bg-slate-800/50">
                                    <td class="p-4 align-middle text-center font-medium text-slate-500">
                                        {{ (doctors.current_page - 1) * doctors.per_page + index + 1 }}
                                    </td>
                                    <td class="p-4 align-middle">
                                        <div class="flex flex-col">
                                            <span class="font-medium text-slate-900 dark:text-slate-100">dr. {{ doctor.name }}</span>
                                        </div>
                                    </td>
                                    <td class="p-4 align-middle">
                                        <Badge variant="outline" class="font-normal text-slate-600 dark:text-slate-400 bg-slate-50 dark:bg-slate-900 border-slate-200 dark:border-slate-700">
                                            {{ doctor.hospital }}
                                        </Badge>
                                    </td>
                                    <td class="p-4 align-middle text-slate-600 dark:text-slate-400 font-mono text-xs">
                                        {{ doctor.license_no || '-' }}
                                    </td>
                                    <td class="p-4 align-middle text-right">
                                        <DropdownMenu v-if="can('doctors.edit') || can('doctors.delete')">
                                            <DropdownMenuTrigger as-child>
                                                <Button variant="ghost" class="h-8 w-8 p-0 text-slate-500 hover:text-indigo-600 hover:bg-indigo-50 dark:hover:bg-indigo-900/20 rounded-full">
                                                    <span class="sr-only">Open menu</span>
                                                    <MoreHorizontal class="h-4 w-4" />
                                                </Button>
                                            </DropdownMenuTrigger>
                                            <DropdownMenuContent align="end" class="w-40 border-slate-200 dark:border-slate-800 rounded-lg shadow-lg">
                                                <DropdownMenuLabel class="text-xs text-slate-500 uppercase tracking-wider font-semibold">Aksi</DropdownMenuLabel>
                                                <DropdownMenuSeparator />
                                                <DropdownMenuItem v-if="can('doctors.edit')" as-child>
                                                    <Link :href="route('doctors.edit', doctor.id)" class="cursor-pointer flex items-center gap-2 text-slate-700 dark:text-slate-200">
                                                        <Pencil class="h-3.5 w-3.5" /> Edit
                                                    </Link>
                                                </DropdownMenuItem>
                                                <DropdownMenuItem v-if="can('doctors.delete')" @click="deleteDoctor(doctor.id)" class="cursor-pointer flex items-center gap-2 text-red-600 focus:text-red-700 focus:bg-red-50 dark:focus:bg-red-900/20">
                                                    <Trash2 class="h-3.5 w-3.5" /> Hapus
                                                </DropdownMenuItem>
                                            </DropdownMenuContent>
                                        </DropdownMenu>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </CardContent>
                
                <CardFooter class="border-t border-slate-100 dark:border-slate-800/50 p-4 bg-slate-50/30 dark:bg-slate-900/30 rounded-b-xl flex justify-between items-center text-xs text-slate-500">
                     <div v-if="doctors.data.length > 0">
                        Menampilkan <strong>{{ doctors.from }}</strong> - <strong>{{ doctors.to }}</strong> dari <strong>{{ doctors.total }}</strong> dokter
                    </div>
                    <!-- Pagination could be added here -->
                </CardFooter>
            </Card>
        </div>
    </AppLayout>
</template>
