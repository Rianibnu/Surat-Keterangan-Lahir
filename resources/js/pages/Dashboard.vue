<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { dashboard } from '@/routes';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, usePage } from '@inertiajs/vue3';
import { Card, CardContent, CardHeader, CardTitle, CardDescription } from '@/components/ui/card';
import { Button } from '@/components/ui/button';
import { 
    Baby, 
    FileText, 
    Plus, 
    ArrowRight, 
    Stethoscope, 
    TrendingUp, 
    Clock, 
    Users, 
    Activity, 
    BarChart3, 
    ArrowUpRight,
    ArrowDownRight,
    Calendar,
    MoreHorizontal
} from 'lucide-vue-next';
import { Badge } from '@/components/ui/badge';
import { computed } from 'vue';
import { usePermissions } from '@/composables/usePermissions';

const { can } = usePermissions();

const page = usePage();
const props = defineProps<{
    stats: {
        total_births: number;
        total_skl: number;
        total_doctors: number;
        growth: number;
        monthly_stats: Array<{ month: string; full_date: string; count: number }>;
        gender: {
            boys: number;
            girls: number;
            boys_percentage: number;
            girls_percentage: number;
        };
        delivery_methods: Array<{ label: string; total: number; percentage: number }>;
    };
    recent_records: Array<any>;
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: dashboard().url,
    },
];

const user = page.props.auth?.user;

const getGreeting = () => {
    const hour = new Date().getHours();
    if (hour < 12) return 'Selamat Pagi';
    if (hour < 15) return 'Selamat Siang';
    if (hour < 18) return 'Selamat Sore';
    return 'Selamat Malam';
};

// Chart Scaling
const maxMonthlyCount = computed(() => {
    const max = Math.max(...props.stats.monthly_stats.map(s => s.count));
    return max > 0 ? max : 10; // Default scale if empty
});

const calculateHeight = (count: number) => {
    const percentage = (count / maxMonthlyCount.value) * 100;
    return `${Math.max(percentage, 5)}%`; // Min 5% height for visibility
};

// Format date to readable format
const formatDate = (dateString: string) => {
    if (!dateString) return '-';
    try {
        const date = new Date(dateString);
        // Check if it's a valid date
        if (isNaN(date.getTime())) return '-';
        return new Intl.DateTimeFormat('id-ID', {
            day: 'numeric',
            month: 'short',
            year: 'numeric',
        }).format(date);
    } catch {
        return '-';
    }
};

// Format time to readable format
const formatTime = (timeString: string) => {
    if (!timeString) return '-';
    // If already in HH:mm format, return as is
    if (/^\d{2}:\d{2}(:\d{2})?$/.test(timeString)) {
        return timeString.substring(0, 5);
    }
    return timeString;
};
</script>

<template>
    <Head title="Dashboard Analytics" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-8 p-6 md:p-8 bg-slate-50/50 dark:bg-slate-950/50">
            <!-- Header Section -->
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-bold tracking-tight text-slate-900 dark:text-slate-100">
                        {{ getGreeting() }}, {{ user?.name || 'Admin' }}
                    </h1>
                    <p class="text-muted-foreground">
                        Berikut adalah ringkasan performa layanan kelahiran RS hari ini.
                    </p>
                </div>
                <div class="flex gap-3">
                    <!-- Button Input Kelahiran - dengan permission check -->
                    <Link v-if="can('birth-records.create')" href="/birth-records/create">
                        <Button class="bg-indigo-600 hover:bg-indigo-700 shadow-indigo-200 shadow-lg text-white gap-2">
                            <Plus class="h-4 w-4" /> Input Kelahiran
                        </Button>
                    </Link>
                    <div v-else class="relative group">
                        <Button 
                            disabled 
                            class="opacity-50 cursor-not-allowed gap-2"
                        >
                            <Plus class="h-4 w-4" /> Input Kelahiran
                        </Button>
                        <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 px-3 py-2 bg-gray-900 text-white text-xs rounded-lg opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap z-50">
                            Anda tidak memiliki akses untuk menambah data
                            <div class="absolute top-full left-1/2 -translate-x-1/2 border-4 border-transparent border-t-gray-900"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Metric Cards Premium - Interactive -->
            <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-4">
                <!-- Total Births - Clickable -->
                <Link href="/birth-records">
                    <Card class="relative overflow-hidden border-slate-100 dark:border-slate-800 shadow-sm hover:shadow-lg transition-all duration-300 cursor-pointer group hover:scale-[1.02] hover:border-indigo-200 dark:hover:border-indigo-800">
                        <div class="absolute right-0 top-0 h-24 w-24 translate-x-8 translate-y-[-8px] rounded-full bg-indigo-50 dark:bg-indigo-900/10 opacity-50 blur-2xl group-hover:opacity-80 transition-opacity"></div>
                        <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2 relative z-10">
                            <CardTitle class="text-sm font-medium text-muted-foreground group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors">Total Kelahiran</CardTitle>
                            <div class="p-2 bg-indigo-50 dark:bg-indigo-900/20 rounded-lg text-indigo-600 dark:text-indigo-400 group-hover:scale-110 group-hover:bg-indigo-100 dark:group-hover:bg-indigo-900/40 transition-all duration-300">
                                <Baby class="h-4 w-4" />
                            </div>
                        </CardHeader>
                        <CardContent class="relative z-10">
                            <div class="text-2xl font-bold group-hover:text-indigo-700 dark:group-hover:text-indigo-300 transition-colors">{{ stats?.total_births }}</div>
                            <div class="flex items-center text-xs mt-1" :class="stats?.growth >= 0 ? 'text-green-600' : 'text-red-500'">
                                <component :is="stats?.growth >= 0 ? ArrowUpRight : ArrowDownRight" class="mr-1 h-3 w-3" />
                                <span class="font-medium">{{ Math.abs(stats?.growth) }}%</span>
                                <span class="text-muted-foreground ml-1">bulan ini</span>
                            </div>
                            <div class="absolute bottom-2 right-4 opacity-0 group-hover:opacity-100 transition-opacity text-xs text-indigo-500 flex items-center">
                                Lihat semua <ArrowRight class="ml-1 h-3 w-3 group-hover:translate-x-1 transition-transform" />
                            </div>
                        </CardContent>
                    </Card>
                </Link>

                <!-- SKL Issued - Clickable -->
                <Link href="/birth-records">
                    <Card class="relative overflow-hidden border-slate-100 dark:border-slate-800 shadow-sm hover:shadow-lg transition-all duration-300 cursor-pointer group hover:scale-[1.02] hover:border-emerald-200 dark:hover:border-emerald-800">
                        <div class="absolute right-0 top-0 h-24 w-24 translate-x-8 translate-y-[-8px] rounded-full bg-emerald-50 dark:bg-emerald-900/10 opacity-50 blur-2xl group-hover:opacity-80 transition-opacity"></div>
                        <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2 relative z-10">
                            <CardTitle class="text-sm font-medium text-muted-foreground group-hover:text-emerald-600 dark:group-hover:text-emerald-400 transition-colors">SKL Terbit</CardTitle>
                            <div class="p-2 bg-emerald-50 dark:bg-emerald-900/20 rounded-lg text-emerald-600 dark:text-emerald-400 group-hover:scale-110 group-hover:bg-emerald-100 dark:group-hover:bg-emerald-900/40 transition-all duration-300">
                                <FileText class="h-4 w-4" />
                            </div>
                        </CardHeader>
                        <CardContent class="relative z-10">
                            <div class="text-2xl font-bold group-hover:text-emerald-700 dark:group-hover:text-emerald-300 transition-colors">{{ stats?.total_skl }}</div>
                            <p class="text-xs text-muted-foreground mt-1">
                                Dokumen digital valid
                            </p>
                            <div class="absolute bottom-2 right-4 opacity-0 group-hover:opacity-100 transition-opacity text-xs text-emerald-500 flex items-center">
                                Lihat SKL <ArrowRight class="ml-1 h-3 w-3 group-hover:translate-x-1 transition-transform" />
                            </div>
                        </CardContent>
                    </Card>
                </Link>

                <!-- Doctors - Clickable -->
                <Link href="/doctors">
                    <Card class="relative overflow-hidden border-slate-100 dark:border-slate-800 shadow-sm hover:shadow-lg transition-all duration-300 cursor-pointer group hover:scale-[1.02] hover:border-purple-200 dark:hover:border-purple-800">
                        <div class="absolute right-0 top-0 h-24 w-24 translate-x-8 translate-y-[-8px] rounded-full bg-purple-50 dark:bg-purple-900/10 opacity-50 blur-2xl group-hover:opacity-80 transition-opacity"></div>
                        <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2 relative z-10">
                            <CardTitle class="text-sm font-medium text-muted-foreground group-hover:text-purple-600 dark:group-hover:text-purple-400 transition-colors">Tim Medis</CardTitle>
                            <div class="p-2 bg-purple-50 dark:bg-purple-900/20 rounded-lg text-purple-600 dark:text-purple-400 group-hover:scale-110 group-hover:bg-purple-100 dark:group-hover:bg-purple-900/40 transition-all duration-300">
                                <Stethoscope class="h-4 w-4" />
                            </div>
                        </CardHeader>
                        <CardContent class="relative z-10">
                            <div class="text-2xl font-bold group-hover:text-purple-700 dark:group-hover:text-purple-300 transition-colors">{{ stats?.total_doctors }}</div>
                            <p class="text-xs text-muted-foreground mt-1">
                                Dokter aktif
                            </p>
                            <div class="absolute bottom-2 right-4 opacity-0 group-hover:opacity-100 transition-opacity text-xs text-purple-500 flex items-center">
                                Kelola <ArrowRight class="ml-1 h-3 w-3 group-hover:translate-x-1 transition-transform" />
                            </div>
                        </CardContent>
                    </Card>
                </Link>

                <!-- Export Report - Enhanced -->
                <Card class="relative overflow-hidden border-slate-100 dark:border-slate-800 shadow-sm hover:shadow-lg transition-all duration-300 bg-gradient-to-br from-orange-50/50 to-amber-50/30 dark:from-orange-900/10 dark:to-amber-900/5 group cursor-pointer border-dashed border-orange-200 dark:border-orange-800 hover:scale-[1.02] hover:border-orange-300 dark:hover:border-orange-700 hover:border-solid">
                    <a :href="route('birth-records.export')" class="block h-full">
                        <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
                            <CardTitle class="text-sm font-medium text-orange-700 dark:text-orange-400 group-hover:text-orange-800 dark:group-hover:text-orange-300 transition-colors">Export Data</CardTitle>
                            <div class="p-2 bg-orange-100 dark:bg-orange-900/30 rounded-lg text-orange-600 dark:text-orange-400 group-hover:scale-110 group-hover:rotate-12 group-hover:bg-orange-200 dark:group-hover:bg-orange-900/50 transition-all duration-300">
                                <TrendingUp class="h-4 w-4" />
                            </div>
                        </CardHeader>
                        <CardContent>
                            <div class="text-lg font-bold text-orange-900 dark:text-orange-200 group-hover:text-orange-950 dark:group-hover:text-orange-100 transition-colors">Unduh Excel</div>
                            <div class="flex items-center text-xs text-orange-600/80 mt-1 font-medium group-hover:text-orange-700">
                                Klik untuk download <ArrowRight class="ml-1 h-3 w-3 transition-transform group-hover:translate-x-2" />
                            </div>
                        </CardContent>
                    </a>
                </Card>
            </div>

            <div class="grid gap-6 md:grid-cols-7">
                <!-- Custom Bar Chart (Monthly Trends) -->
                <Card class="md:col-span-4 lg:col-span-5 border-slate-100 dark:border-slate-800 shadow-sm">
                    <CardHeader>
                        <div class="flex items-center justify-between">
                            <div>
                                <CardTitle class="text-base font-semibold text-slate-900">Tren Kelahiran</CardTitle>
                                <CardDescription>Statistik kelahiran 6 bulan terakhir</CardDescription>
                            </div>
                            <div class="p-2 bg-slate-50 rounded-md border border-slate-100">
                                <BarChart3 class="h-4 w-4 text-slate-500" />
                            </div>
                        </div>
                    </CardHeader>
                    <CardContent>
                        <!-- Chart Container -->
                        <div class="h-[250px] w-full flex items-end justify-between gap-2 sm:gap-4 mt-4 px-2">
                             <div v-for="(stat, index) in stats.monthly_stats" :key="index" class="relative group flex-1 flex flex-col justify-end items-center h-full">
                                <!-- Tooltip -->
                                <div class="absolute -top-12 opacity-0 group-hover:opacity-100 transition-opacity bg-slate-800 text-white text-xs rounded px-2 py-1 mb-2 whitespace-nowrap z-10">
                                    {{ stat.count }} Bayi
                                    <div class="absolute -bottom-1 left-1/2 -translate-x-1/2 border-4 border-transparent border-t-slate-800"></div>
                                </div>
                                
                                <!-- Bar -->
                                <div class="w-full max-w-[40px] bg-indigo-100 dark:bg-indigo-900/20 rounded-t-lg overflow-hidden relative group-hover:bg-indigo-200 transition-colors" :style="{ height: calculateHeight(stat.count) }">
                                     <div class="absolute bottom-0 left-0 right-0 bg-indigo-500 hover:bg-indigo-600 transition-all duration-500 w-full rounded-t-lg" :style="{ height: '100%' }"></div>
                                </div>
                                
                                <!-- Label -->
                                <span class="text-xs text-muted-foreground mt-3 font-medium">{{ stat.month }}</span>
                             </div>
                        </div>
                    </CardContent>
                </Card>

                <!-- Gender Donut / Progress -->
                <Card class="md:col-span-3 lg:col-span-2 border-slate-100 dark:border-slate-800 shadow-sm">
                    <CardHeader>
                        <CardTitle class="text-base font-semibold">Demografi</CardTitle>
                         <CardDescription>Rasio Laki-laki vs Perempuan</CardDescription>
                    </CardHeader>
                    <CardContent class="flex flex-col justify-center gap-8 py-6">
                        <!-- Custom Circular Visualization (CSS based) could go here, but using Linear as per design spec is cleaner for dashboards -->
                        <div class="space-y-6">
                             <div class="space-y-2">
                                <div class="flex justify-between text-sm font-medium">
                                    <span class="flex items-center gap-2"><div class="w-2 h-2 rounded-full bg-blue-500"></div> Laki-laki</span>
                                    <span>{{ stats?.gender?.boys_percentage }}%</span>
                                </div>
                                <div class="h-2 w-full bg-slate-100 rounded-full overflow-hidden">
                                    <div class="h-full bg-blue-500 rounded-full" :style="`width: ${stats?.gender?.boys_percentage}%`"></div>
                                </div>
                                <p class="text-xs text-muted-foreground text-right">{{ stats?.gender?.boys }} Bayi</p>
                            </div>

                             <div class="space-y-2">
                                <div class="flex justify-between text-sm font-medium">
                                    <span class="flex items-center gap-2"><div class="w-2 h-2 rounded-full bg-pink-500"></div> Perempuan</span>
                                    <span>{{ stats?.gender?.girls_percentage }}%</span>
                                </div>
                                <div class="h-2 w-full bg-slate-100 rounded-full overflow-hidden">
                                    <div class="h-full bg-pink-500 rounded-full" :style="`width: ${stats?.gender?.girls_percentage}%`"></div>
                                </div>
                                <p class="text-xs text-muted-foreground text-right">{{ stats?.gender?.girls }} Bayi</p>
                            </div>
                        </div>
                        
                        <!-- Mini Delivery Stats -->
                        <div class="bg-slate-50/50 rounded-xl p-4 border border-slate-100">
                            <h4 class="text-xs font-semibold uppercase text-muted-foreground mb-3">Persalinan Terbanyak</h4>
                            <div v-if="stats.delivery_methods.length > 0">
                                <div class="flex items-center justify-between text-sm font-bold text-slate-800">
                                    {{ stats.delivery_methods[0].label }}
                                    <span class="bg-indigo-100 text-indigo-700 px-2 py-0.5 rounded text-xs">{{ stats.delivery_methods[0].percentage }}%</span>
                                </div>
                            </div>
                            <div v-else class="text-xs text-muted-foreground">Belum ada data</div>
                        </div>
                    </CardContent>
                </Card>
            </div>

            <!-- Recent Activity Table -->
            <Card class="border-slate-100 dark:border-slate-800 shadow-sm overflow-hidden bg-white dark:bg-slate-900">
                <CardHeader class="bg-gray-50/40 dark:bg-slate-800/40 border-b border-slate-100 dark:border-slate-800 py-4">
                     <div class="flex items-center justify-between">
                        <div>
                            <CardTitle class="text-base font-semibold flex items-center gap-2 text-slate-900 dark:text-slate-100">
                                <Activity class="h-4 w-4 text-indigo-500" /> Aktivitas Terbaru
                            </CardTitle>
                        </div>
                        <Link href="/birth-records" class="text-xs font-medium text-indigo-600 hover:text-indigo-800 dark:text-indigo-400 dark:hover:text-indigo-300 flex items-center hover:underline">
                            Lihat Semua <ArrowRight class="ml-1 h-3 w-3" />
                        </Link>
                    </div>
                </CardHeader>
                <div class="p-0">
                     <div v-if="recent_records.length === 0" class="text-center py-12 text-muted-foreground text-sm">
                        <div class="bg-slate-50 dark:bg-slate-800 w-12 h-12 rounded-full flex items-center justify-center mx-auto mb-3">
                            <Calendar class="h-6 w-6 text-slate-300 dark:text-slate-500" />
                        </div>
                        Belum ada data kelahiran yang diinput.
                    </div>
                    <div v-else class="divide-y divide-slate-100 dark:divide-slate-800">
                        <div v-for="record in recent_records" :key="record.id" class="flex items-center justify-between p-4 hover:bg-slate-50/60 dark:hover:bg-slate-800/60 transition-colors group">
                             <div class="flex items-center gap-4">
                                <div class="h-10 w-10 rounded-full flex items-center justify-center text-white font-bold text-sm shadow-sm" :class="record.gender === 'Laki-laki' ? 'bg-blue-500' : 'bg-pink-500'">
                                    {{ record.baby_name.charAt(0) }}
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-slate-900 dark:text-slate-100 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors">{{ record.baby_name }}</p>
                                    <div class="flex items-center gap-2 text-xs text-muted-foreground mt-0.5">
                                        <Calendar class="h-3 w-3" /> {{ formatDate(record.birth_date) }}
                                        <span>&bull;</span>
                                        <span>{{ formatTime(record.birth_time) }} WIB</span>
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-center gap-4">
                                <Badge variant="secondary" class="font-normal hidden sm:inline-flex bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300">
                                    {{ record.delivery_method }}
                                </Badge>
                                <Badge variant="outline" class="h-6" :class="record.skl ? 'bg-green-50 dark:bg-green-900/20 text-green-700 dark:text-green-400 border-green-200 dark:border-green-800' : 'bg-yellow-50 dark:bg-yellow-900/20 text-yellow-700 dark:text-yellow-400 border-yellow-200 dark:border-yellow-800'">
                                    {{ record.skl ? 'SKL Terbit' : 'Draft' }}
                                </Badge>
                                <Link :href="`/birth-records/${record.id}`">
                                    <Button size="icon" variant="ghost" class="h-8 w-8 text-slate-400 hover:text-slate-600 dark:hover:text-slate-300">
                                        <ArrowRight class="h-4 w-4" />
                                    </Button>
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>
            </Card>
        </div>
    </AppLayout>
</template>
