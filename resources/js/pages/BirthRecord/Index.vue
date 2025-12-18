<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Button } from '@/components/ui/button';
import { type BreadcrumbItem } from '@/types';
import { Baby, Plus, Eye, Trash2, FileText, Calendar, User, Download, Pencil } from 'lucide-vue-next';
import { ref, computed } from 'vue';
import { usePermissions } from '@/composables/usePermissions';

const props = defineProps<{
    records: Array<any>;
}>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: '/dashboard' },
    { title: 'Data Kelahiran', href: '#' },
];

const { can } = usePermissions();

const searchQuery = ref('');

const filteredRecords = computed(() => {
    if (!searchQuery.value) return props.records;
    const query = searchQuery.value.toLowerCase();
    return props.records.filter(record => 
        record.baby_name.toLowerCase().includes(query) ||
        record.mother_name.toLowerCase().includes(query) ||
        record.skl?.document_number?.toLowerCase().includes(query)
    );
});

const deleteRecord = (id: number) => {
    if (confirm('Apakah Anda yakin ingin menghapus data ini?')) {
        router.delete(`/birth-records/${id}`);
    }
};

const formatDate = (dateString: string) => {
    if (!dateString) return '-';
    return new Date(dateString).toLocaleDateString('id-ID', { 
        day: 'numeric', 
        month: 'short', 
        year: 'numeric' 
    });
};

const getGenderBadge = (gender: string) => {
    return gender === 'Laki-laki' 
        ? 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300'
        : 'bg-pink-100 text-pink-800 dark:bg-pink-900 dark:text-pink-300';
};
</script>

<template>
    <Head title="Data Kelahiran" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-6 p-6">
            <!-- Header Section -->
            <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                <div>
                    <h1 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white flex items-center gap-2">
                        <Baby class="h-7 w-7 text-indigo-600" />
                        Data Kelahiran
                    </h1>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                        Kelola data kelahiran dan Surat Keterangan Lahir (SKL)
                    </p>
                </div>
            <div class="flex gap-2">
                    <Button v-if="can('birth-records.export')" variant="outline" class="gap-2 border-slate-300 dark:border-slate-700 hover:bg-slate-100 dark:hover:bg-slate-800" as-child>
                        <a :href="route('birth-records.export')">
                            <Download class="h-4 w-4" />
                            Overview/Export
                        </a>
                    </Button>
                    
                    <!-- Button Input Data - dengan permission check -->
                    <Link v-if="can('birth-records.create')" href="/birth-records/create">
                        <Button class="gap-2 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 shadow-lg shadow-indigo-500/25 transition-all duration-300 hover:shadow-xl hover:scale-105">
                            <Plus class="h-4 w-4" />
                            Input Data Baru
                        </Button>
                    </Link>
                    <div v-else class="relative group">
                        <Button 
                            disabled 
                            class="gap-2 opacity-50 cursor-not-allowed"
                        >
                            <Plus class="h-4 w-4" />
                            Input Data Baru
                        </Button>
                        <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 px-3 py-2 bg-gray-900 text-white text-xs rounded-lg opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap z-50">
                            Anda tidak memiliki akses untuk menambah data
                            <div class="absolute top-full left-1/2 -translate-x-1/2 border-4 border-transparent border-t-gray-900"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="grid gap-4 md:grid-cols-3">
                <Card class="bg-gradient-to-br from-blue-50 to-indigo-100 dark:from-blue-950 dark:to-indigo-900 border-0 shadow-md">
                    <CardContent class="p-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-blue-600 dark:text-blue-400">Total Data</p>
                                <p class="text-3xl font-bold text-blue-900 dark:text-blue-100">{{ records.length }}</p>
                            </div>
                            <div class="h-12 w-12 rounded-full bg-blue-500/20 flex items-center justify-center">
                                <FileText class="h-6 w-6 text-blue-600 dark:text-blue-400" />
                            </div>
                        </div>
                    </CardContent>
                </Card>
                <Card class="bg-gradient-to-br from-green-50 to-emerald-100 dark:from-green-950 dark:to-emerald-900 border-0 shadow-md">
                    <CardContent class="p-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-green-600 dark:text-green-400">Laki-laki</p>
                                <p class="text-3xl font-bold text-green-900 dark:text-green-100">{{ records.filter(r => r.gender === 'Laki-laki').length }}</p>
                            </div>
                            <div class="h-12 w-12 rounded-full bg-green-500/20 flex items-center justify-center">
                                <User class="h-6 w-6 text-green-600 dark:text-green-400" />
                            </div>
                        </div>
                    </CardContent>
                </Card>
                <Card class="bg-gradient-to-br from-pink-50 to-rose-100 dark:from-pink-950 dark:to-rose-900 border-0 shadow-md">
                    <CardContent class="p-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-pink-600 dark:text-pink-400">Perempuan</p>
                                <p class="text-3xl font-bold text-pink-900 dark:text-pink-100">{{ records.filter(r => r.gender === 'Perempuan').length }}</p>
                            </div>
                            <div class="h-12 w-12 rounded-full bg-pink-500/20 flex items-center justify-center">
                                <User class="h-6 w-6 text-pink-600 dark:text-pink-400" />
                            </div>
                        </div>
                    </CardContent>
                </Card>
            </div>

            <!-- Search & Table -->
            <Card class="shadow-lg border-0 dark:bg-gray-800/50 backdrop-blur">
                <CardHeader class="border-b dark:border-gray-700 pb-4">
                    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                        <CardTitle class="text-lg font-semibold">Daftar Kelahiran</CardTitle>
                        <!-- Search -->
                        <div class="relative">
                            <input 
                                v-model="searchQuery"
                                type="text" 
                                placeholder="Cari nama bayi, ibu, atau nomor SKL..."
                                class="w-full md:w-80 pl-10 pr-4 py-2 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-200"
                            />
                            <svg class="absolute left-3 top-1/2 -translate-y-1/2 h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                    </div>
                </CardHeader>
                <CardContent class="p-0">
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="bg-gray-50 dark:bg-gray-800/80 border-b dark:border-gray-700">
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">No</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Nama Bayi</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Jenis Kelamin</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Tanggal Lahir</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Nama Ibu</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">No. SKL</th>
                                    <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                                <tr 
                                    v-for="(record, index) in filteredRecords" 
                                    :key="record.id" 
                                    class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors duration-150"
                                >
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-gray-100 dark:bg-gray-700 text-sm font-medium text-gray-700 dark:text-gray-300">
                                            {{ index + 1 }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center gap-3">
                                            <div class="h-10 w-10 rounded-full bg-gradient-to-br from-indigo-400 to-purple-500 flex items-center justify-center text-white font-bold text-sm">
                                                {{ record.baby_name.charAt(0).toUpperCase() }}
                                            </div>
                                            <div>
                                                <p class="font-semibold text-gray-900 dark:text-white">{{ record.baby_name }}</p>
                                                <p class="text-xs text-gray-500 dark:text-gray-400">MR: {{ record.medical_record_no }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span :class="['inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium', getGenderBadge(record.gender)]">
                                            {{ record.gender }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center gap-2 text-gray-600 dark:text-gray-300">
                                            <Calendar class="h-4 w-4 text-gray-400" />
                                            {{ formatDate(record.birth_date) }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-gray-700 dark:text-gray-300">
                                        {{ record.mother_name }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <code class="px-2 py-1 rounded bg-gray-100 dark:bg-gray-700 text-xs font-mono text-gray-700 dark:text-gray-300">
                                            {{ record.skl?.document_number || '-' }}
                                        </code>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center justify-center gap-2">
                                            <Link :href="`/birth-records/${record.id}`">
                                                <Button variant="ghost" size="sm" class="gap-1.5 text-indigo-600 hover:text-indigo-700 hover:bg-indigo-50 dark:hover:bg-indigo-950">
                                                    <Eye class="h-4 w-4" />
                                                    Lihat
                                                </Button>
                                            </Link>
                                            <Link v-if="can('birth-records.edit')" :href="`/birth-records/${record.id}/edit`">
                                                <Button variant="ghost" size="sm" class="gap-1.5 text-blue-600 hover:text-blue-700 hover:bg-blue-50 dark:hover:bg-blue-950">
                                                    <Pencil class="h-4 w-4" />
                                                    Edit
                                                </Button>
                                            </Link>
                                            <Button 
                                                v-if="can('birth-records.delete')"
                                                variant="ghost" 
                                                size="sm" 
                                                class="gap-1.5 text-red-600 hover:text-red-700 hover:bg-red-50 dark:hover:bg-red-950"
                                                @click="deleteRecord(record.id)"
                                            >
                                                <Trash2 class="h-4 w-4" />
                                                Hapus
                                            </Button>
                                        </div>
                                    </td>
                                </tr>
                                <tr v-if="filteredRecords.length === 0">
                                    <td colspan="7" class="px-6 py-16 text-center">
                                        <div class="flex flex-col items-center gap-3">
                                            <div class="h-16 w-16 rounded-full bg-gray-100 dark:bg-gray-800 flex items-center justify-center">
                                                <Baby class="h-8 w-8 text-gray-400" />
                                            </div>
                                            <p class="text-gray-500 dark:text-gray-400 font-medium">Belum ada data kelahiran</p>
                                            <Link v-if="can('birth-records.create')" href="/birth-records/create">
                                                <Button variant="outline" size="sm" class="gap-2">
                                                    <Plus class="h-4 w-4" />
                                                    Tambah Data Pertama
                                                </Button>
                                            </Link>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </CardContent>
            </Card>
        </div>
    </AppLayout>
</template>
