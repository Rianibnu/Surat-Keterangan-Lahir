<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Button } from '@/components/ui/button';
import { Badge } from '@/components/ui/badge';
import { ConfirmDialog } from '@/components/ui/confirm-dialog';
import { Activity, ArrowLeft, Calendar, User, FileText, Code, Globe, Monitor, RotateCcw, AlertTriangle } from 'lucide-vue-next';
import { type BreadcrumbItem } from '@/types';
import { ref } from 'vue';

const props = defineProps<{
    activity: {
        id: number;
        log_name: string;
        description: string;
        subject_type: string | null;
        subject_id: number | null;
        causer: { id: number; name: string; email: string } | null;
        properties: {
            attributes?: Record<string, any>;
            old?: Record<string, any>;
            ip_address?: string;
            user_agent?: string;
            [key: string]: any;
        };
        event: string | null;
        created_at: string;
    };
    canRestore?: boolean;
}>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: '/dashboard' },
    { title: 'Activity Log', href: '/activity-logs' },
    { title: `Detail #${props.activity.id}`, href: `/activity-logs/${props.activity.id}` },
];

// Restore dialog state
const showRestoreDialog = ref(false);
const isRestoring = ref(false);

const formatDate = (dateString: string) => {
    const date = new Date(dateString);
    return new Intl.DateTimeFormat('id-ID', {
        weekday: 'long',
        day: '2-digit',
        month: 'long',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
        second: '2-digit',
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

const formatValue = (value: any): string => {
    if (value === null || value === undefined) return '-';
    if (typeof value === 'object') return JSON.stringify(value, null, 2);
    
    // Check if it's an ISO date string (e.g., 2025-12-18T14:43:24.000000Z)
    const isoDatePattern = /^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}:\d{2}/;
    if (typeof value === 'string' && isoDatePattern.test(value)) {
        try {
            const date = new Date(value);
            if (!isNaN(date.getTime())) {
                return new Intl.DateTimeFormat('id-ID', {
                    day: 'numeric',
                    month: 'short',
                    year: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit',
                }).format(date);
            }
        } catch {
            // Fall through to return original value
        }
    }
    
    return String(value);
};

const getChangedFields = () => {
    const old = props.activity.properties?.old || {};
    const attributes = props.activity.properties?.attributes || {};
    const fields: Array<{ key: string; old: any; new: any }> = [];

    const allKeys = new Set([...Object.keys(old), ...Object.keys(attributes)]);
    
    allKeys.forEach(key => {
        if (old[key] !== attributes[key]) {
            fields.push({
                key,
                old: old[key],
                new: attributes[key],
            });
        }
    });

    return fields;
};

const getDeletedData = () => {
    return props.activity.properties?.old || {};
};

const confirmRestore = () => {
    isRestoring.value = true;
    router.post(`/activity-logs/${props.activity.id}/restore`, {}, {
        onFinish: () => {
            isRestoring.value = false;
        }
    });
};
</script>

<template>
    <Head :title="`Activity Log #${activity.id}`" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-6 p-6">
            <!-- Header Section -->
            <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                <div>
                    <h1 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white flex items-center gap-2">
                        <Activity class="h-7 w-7 text-indigo-600 dark:text-indigo-400" />
                        Detail Aktivitas #{{ activity.id }}
                    </h1>
                    <p class="text-sm text-muted-foreground mt-1">
                        {{ activity.description }}
                    </p>
                </div>
                <div class="flex items-center gap-3">
                    <!-- Restore Button - Only show for deleted events -->
                    <Button 
                        v-if="canRestore && activity.event === 'deleted'"
                        @click="showRestoreDialog = true"
                        class="bg-gradient-to-r from-emerald-500 to-green-600 hover:from-emerald-600 hover:to-green-700 text-white shadow-lg shadow-emerald-500/25"
                    >
                        <RotateCcw class="h-4 w-4 mr-2" />
                        Pulihkan Data
                    </Button>
                    <Link href="/activity-logs">
                        <Button variant="outline" size="sm">
                            <ArrowLeft class="h-4 w-4 mr-2" />
                            Kembali
                        </Button>
                    </Link>
                </div>
            </div>

            <!-- Restore Alert Banner -->
            <div 
                v-if="canRestore && activity.event === 'deleted'" 
                class="bg-gradient-to-r from-amber-50 to-orange-50 dark:from-amber-900/20 dark:to-orange-900/20 border border-amber-200 dark:border-amber-800 rounded-xl p-4 flex items-start gap-4"
            >
                <div class="p-2 bg-amber-100 dark:bg-amber-900/40 rounded-lg">
                    <AlertTriangle class="h-5 w-5 text-amber-600 dark:text-amber-400" />
                </div>
                <div class="flex-1">
                    <h3 class="font-semibold text-amber-800 dark:text-amber-200">Data Dapat Dipulihkan</h3>
                    <p class="text-sm text-amber-700 dark:text-amber-300 mt-1">
                        Data <strong>{{ getSubjectName(activity.subject_type) }}</strong> yang dihapus dapat dipulihkan. 
                        Klik tombol "Pulihkan Data" untuk mengembalikan data ke kondisi sebelum dihapus.
                    </p>
                </div>
            </div>

            <div class="grid gap-6 md:grid-cols-2">
                <!-- Activity Info -->
                <Card class="shadow-sm border-0 dark:bg-gray-800/50">
                    <CardHeader>
                        <CardTitle class="text-base flex items-center gap-2">
                            <Activity class="h-4 w-4" />
                            Informasi Aktivitas
                        </CardTitle>
                    </CardHeader>
                    <CardContent class="space-y-4">
                        <div class="flex items-center justify-between py-2 border-b dark:border-gray-700">
                            <span class="text-sm text-muted-foreground">Event</span>
                            <Badge :class="getEventColor(activity.event)">
                                {{ activity.event || 'unknown' }}
                            </Badge>
                        </div>
                        <div class="flex items-center justify-between py-2 border-b dark:border-gray-700">
                            <span class="text-sm text-muted-foreground">Log Name</span>
                            <span class="text-sm font-medium">{{ activity.log_name }}</span>
                        </div>
                        <div class="flex items-center justify-between py-2 border-b dark:border-gray-700">
                            <span class="text-sm text-muted-foreground flex items-center gap-1">
                                <Calendar class="h-4 w-4" />
                                Waktu
                            </span>
                            <span class="text-sm">{{ formatDate(activity.created_at) }}</span>
                        </div>
                        <div class="flex items-center justify-between py-2 border-b dark:border-gray-700">
                            <span class="text-sm text-muted-foreground flex items-center gap-1">
                                <FileText class="h-4 w-4" />
                                Model
                            </span>
                            <span class="text-sm">{{ getSubjectName(activity.subject_type) }} #{{ activity.subject_id || '-' }}</span>
                        </div>
                    </CardContent>
                </Card>

                <!-- User Info - Enhanced Design -->
                <Card class="shadow-sm border-0 dark:bg-gray-800/50 overflow-hidden">
                    <CardHeader class="bg-gradient-to-r from-indigo-50/50 to-purple-50/50 dark:from-indigo-900/20 dark:to-purple-900/20">
                        <CardTitle class="text-base flex items-center gap-2">
                            <div class="p-1.5 bg-white dark:bg-gray-800 rounded-lg shadow-sm">
                                <User class="h-4 w-4 text-indigo-600 dark:text-indigo-400" />
                            </div>
                            Dilakukan Oleh
                        </CardTitle>
                    </CardHeader>
                    <CardContent class="pt-6">
                        <!-- Authenticated User -->
                        <div v-if="activity.causer" class="flex items-center gap-5 group">
                            <div class="relative">
                                <div class="h-20 w-20 rounded-2xl bg-gradient-to-br from-indigo-500 via-purple-500 to-pink-500 flex items-center justify-center text-white text-3xl font-bold shadow-lg shadow-indigo-500/30 group-hover:shadow-xl group-hover:shadow-indigo-500/40 transition-all duration-300 group-hover:scale-105">
                                    {{ activity.causer.name.charAt(0).toUpperCase() }}
                                </div>
                                <div class="absolute -bottom-1 -right-1 h-6 w-6 bg-green-500 rounded-full border-4 border-white dark:border-gray-800 flex items-center justify-center">
                                    <svg class="h-3 w-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </div>
                            <div class="flex-1">
                                <div class="flex items-center gap-2 mb-1">
                                    <p class="font-bold text-xl text-gray-900 dark:text-gray-100">{{ activity.causer.name }}</p>
                                    <span class="px-2 py-0.5 bg-indigo-100 dark:bg-indigo-900/40 text-indigo-700 dark:text-indigo-300 text-xs font-medium rounded-full">
                                        Verified
                                    </span>
                                </div>
                                <div class="flex items-center gap-2 text-gray-500 dark:text-gray-400 mb-2">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                    <span class="text-sm">{{ activity.causer.email }}</span>
                                </div>
                                <div class="flex items-center gap-4">
                                    <div class="flex items-center gap-1.5 text-xs text-gray-400 dark:text-gray-500">
                                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                                        </svg>
                                        <span>ID: {{ activity.causer.id }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Anonymous/Public User with Device Info -->
                        <div v-else-if="activity.properties?.ip_address" class="space-y-5">
                            <div class="flex items-center gap-5 group">
                                <div class="relative">
                                    <div class="h-20 w-20 rounded-2xl bg-gradient-to-br from-gray-400 via-gray-500 to-gray-600 flex items-center justify-center text-white shadow-lg shadow-gray-400/30 group-hover:shadow-xl transition-all duration-300">
                                        <Globe class="h-10 w-10" />
                                    </div>
                                    <div class="absolute -bottom-1 -right-1 h-6 w-6 bg-amber-500 rounded-full border-4 border-white dark:border-gray-800 flex items-center justify-center">
                                        <svg class="h-3 w-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                </div>
                                <div class="flex-1">
                                    <div class="flex items-center gap-2 mb-1">
                                        <p class="font-bold text-xl text-gray-900 dark:text-gray-100">Pengunjung Publik</p>
                                        <span class="px-2 py-0.5 bg-amber-100 dark:bg-amber-900/40 text-amber-700 dark:text-amber-300 text-xs font-medium rounded-full">
                                            Anonymous
                                        </span>
                                    </div>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Akses tanpa login (via QR Code)</p>
                                </div>
                            </div>
                            <div class="bg-gradient-to-br from-gray-50 to-slate-50 dark:from-gray-900 dark:to-slate-900 rounded-xl p-5 space-y-4 border border-gray-100 dark:border-gray-700">
                                <div class="flex items-start gap-4">
                                    <div class="p-2.5 bg-blue-100 dark:bg-blue-900/30 rounded-lg">
                                        <Globe class="h-5 w-5 text-blue-600 dark:text-blue-400" />
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">IP Address</p>
                                        <p class="text-sm font-mono bg-white dark:bg-gray-800 px-3 py-1.5 rounded-lg inline-block border border-gray-200 dark:border-gray-700">{{ activity.properties.ip_address }}</p>
                                    </div>
                                </div>
                                <div class="flex items-start gap-4">
                                    <div class="p-2.5 bg-purple-100 dark:bg-purple-900/30 rounded-lg">
                                        <Monitor class="h-5 w-5 text-purple-600 dark:text-purple-400" />
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">Perangkat / Browser</p>
                                        <p class="text-sm text-gray-600 dark:text-gray-300 break-all leading-relaxed">{{ activity.properties.user_agent || '-' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- System Activity -->
                        <div v-else class="text-center py-8">
                            <div class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-br from-gray-100 to-gray-200 dark:from-gray-700 dark:to-gray-800 rounded-2xl mb-4">
                                <User class="h-10 w-10 text-gray-400 dark:text-gray-500" />
                            </div>
                            <p class="text-gray-500 dark:text-gray-400 font-medium">Aktivitas oleh Sistem</p>
                            <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">Otomatis dijalankan oleh sistem</p>
                        </div>
                    </CardContent>
                </Card>
            </div>

            <!-- Changes Detail -->
            <Card v-if="activity.event === 'updated' && getChangedFields().length > 0" class="shadow-sm border-0 dark:bg-gray-800/50">
                <CardHeader>
                    <CardTitle class="text-base flex items-center gap-2">
                        <Code class="h-4 w-4" />
                        Perubahan Data
                    </CardTitle>
                </CardHeader>
                <CardContent>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="bg-gray-50 dark:bg-gray-800/80 border-b dark:border-gray-700">
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase">Field</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase">Nilai Lama</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase">Nilai Baru</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                                <tr v-for="field in getChangedFields()" :key="field.key">
                                    <td class="px-4 py-3 font-mono text-sm font-medium">{{ field.key }}</td>
                                    <td class="px-4 py-3">
                                        <code class="px-2 py-1 bg-red-50 dark:bg-red-900/20 text-red-700 dark:text-red-400 rounded text-xs">
                                            {{ formatValue(field.old) }}
                                        </code>
                                    </td>
                                    <td class="px-4 py-3">
                                        <code class="px-2 py-1 bg-green-50 dark:bg-green-900/20 text-green-700 dark:text-green-400 rounded text-xs">
                                            {{ formatValue(field.new) }}
                                        </code>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </CardContent>
            </Card>

            <!-- Deleted Data Detail (for deleted events) -->
            <Card v-if="activity.event === 'deleted' && Object.keys(getDeletedData()).length > 0" class="shadow-sm border-0 dark:bg-gray-800/50 border-l-4 border-l-red-500">
                <CardHeader class="bg-red-50/50 dark:bg-red-900/10">
                    <CardTitle class="text-base flex items-center gap-2 text-red-700 dark:text-red-400">
                        <AlertTriangle class="h-4 w-4" />
                        Data yang Dihapus
                    </CardTitle>
                </CardHeader>
                <CardContent>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="bg-gray-50 dark:bg-gray-800/80 border-b dark:border-gray-700">
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase w-1/3">Field</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase">Nilai</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                                <tr v-for="(value, key) in getDeletedData()" :key="key">
                                    <td class="px-4 py-3 font-mono text-sm font-medium text-gray-700 dark:text-gray-300">{{ key }}</td>
                                    <td class="px-4 py-3">
                                        <code class="px-2 py-1 bg-red-50 dark:bg-red-900/20 text-red-700 dark:text-red-400 rounded text-xs">
                                            {{ formatValue(value) }}
                                        </code>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </CardContent>
            </Card>

            <!-- Raw Properties -->
            <Card class="shadow-sm border-0 dark:bg-gray-800/50">
                <CardHeader>
                    <CardTitle class="text-base flex items-center gap-2">
                        <Code class="h-4 w-4" />
                        Data Properties (Raw)
                    </CardTitle>
                </CardHeader>
                <CardContent>
                    <pre class="bg-gray-50 dark:bg-gray-900 rounded-lg p-4 text-xs overflow-x-auto font-mono">{{ JSON.stringify(activity.properties, null, 2) }}</pre>
                </CardContent>
            </Card>
        </div>

        <!-- Restore Confirmation Dialog -->
        <ConfirmDialog
            v-model="showRestoreDialog"
            title="Pulihkan Data"
            :message="`Apakah Anda yakin ingin memulihkan data '${getSubjectName(activity.subject_type)}' yang telah dihapus? Data akan dibuat ulang dengan nilai sebelum penghapusan.`"
            confirm-text="Ya, Pulihkan"
            cancel-text="Batal"
            variant="info"
            icon="check"
            @confirm="confirmRestore"
        />
    </AppLayout>
</template>

