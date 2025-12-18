<script setup lang="ts">
import { useForm, Head, Link } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { Card, CardContent, CardHeader, CardTitle, CardDescription } from '@/components/ui/card';
import { Button } from '@/components/ui/button';
import { type BreadcrumbItem } from '@/types';
import { 
    Baby, User, Heart, Stethoscope, Save, ArrowLeft, 
    Calendar, Clock, Ruler, Scale, Activity, FileText, 
    MapPin, Briefcase, Droplet, Hash, CheckCircle2, Phone
} from 'lucide-vue-next';

const props = defineProps<{
    doctors: Array<{ id: number, name: string }>;
}>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: '/dashboard' },
    { title: 'Data Kelahiran', href: '/birth-records' },
    { title: 'Input Baru', href: '#' },
];

const form = useForm({
    baby_name: '',
    birth_date: '',
    birth_time: '',
    medical_record_no: '',
    gender: 'Laki-laki',
    child_order: 1,
    delivery_method: 'Spontan',
    weight: '' as string | number,
    length: '' as string | number,
    head_circ: '' as string | number,
    chest_circ: '' as string | number,
    baby_blood_type: '',
    
    mother_name: '',
    mother_medical_record_no: '',
    mother_ktp: '',
    mother_address: '',
    mother_occupation: '',
    mother_blood_type: '',

    father_name: '',
    father_ktp: '',
    father_address: '',
    father_occupation: '',
    father_blood_type: '',
    
    doctor_id: '',
});

const submit = () => {
    form.post('/birth-records');
};

const bloodTypes = ['A', 'B', 'AB', 'O', 'A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'];
const deliveryMethods = ['Spontan', 'SC (Sectio Caesarea)', 'Vacuum', 'Forceps'];
</script>

<template>
    <Head title="Input Data Kelahiran" />
    
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="max-w-7xl mx-auto p-6 md:p-8 space-y-8 animate-in fade-in slide-in-from-bottom-4 duration-500">
            <!-- Header -->
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-bold tracking-tight text-slate-900 dark:text-white bg-clip-text text-transparent bg-gradient-to-r from-indigo-600 to-purple-600 dark:from-indigo-400 dark:to-purple-400">
                        Input Data Kelahiran
                    </h1>
                    <p class="text-slate-500 dark:text-slate-400 mt-2 text-lg">
                        Buat data kelahiran baru untuk penerbitan SKL digital.
                    </p>
                </div>
                <Link href="/birth-records">
                    <Button variant="outline" class="gap-2 border-slate-200 hover:bg-slate-50 hover:text-indigo-600 transition-colors">
                        <ArrowLeft class="h-4 w-4" />
                        Kembali ke Daftar
                    </Button>
                </Link>
            </div>

            <form @submit.prevent="submit" class="space-y-8">
                
                <!-- Section: Data Bayi -->
                <div class="space-y-4">
                    <div class="flex items-center gap-2 text-indigo-600 dark:text-indigo-400 font-semibold px-1">
                        <Baby class="h-5 w-5" />
                        <h2>Identitas Bayi</h2>
                        <div class="h-px bg-indigo-100 dark:bg-indigo-900/30 flex-1 ml-4"></div>
                    </div>

                    <Card class="border-indigo-100/50 dark:border-indigo-900/20 shadow-xl shadow-indigo-500/5 overflow-hidden">
                        <CardContent class="p-6 md:p-8 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-12 gap-6 md:gap-8">
                            
                            <!-- Foto / Icon Placeholder Left (Optional, keeping it simple for now) -->
                            
                            <!-- Nama Bayi & RM -->
                            <div class="lg:col-span-8 space-y-6">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div class="space-y-2">
                                        <label class="text-sm font-medium text-slate-700 dark:text-slate-300">Nama Lengkap Bayi <span class="text-red-500">*</span></label>
                                        <div class="relative group">
                                            <Baby class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-slate-400 group-focus-within:text-indigo-500 transition-colors" />
                                            <input v-model="form.baby_name" type="text" class="w-full pl-10 pr-4 py-2.5 rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-800 focus:bg-white dark:focus:bg-slate-900 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all" placeholder="Nama lengkap bayi" required />
                                            <p v-if="form.errors.baby_name" class="text-xs text-red-500 mt-1">{{ form.errors.baby_name }}</p>
                                        </div>
                                    </div>
                                    <div class="space-y-2">
                                        <label class="text-sm font-medium text-slate-700 dark:text-slate-300">No. Rekam Medis <span class="text-red-500">*</span></label>
                                        <div class="relative group">
                                            <Hash class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-slate-400 group-focus-within:text-indigo-500 transition-colors" />
                                            <input v-model="form.medical_record_no" type="text" class="w-full pl-10 pr-4 py-2.5 rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-800 focus:bg-white dark:focus:bg-slate-900 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all font-mono" placeholder="123456" required />
                                        </div>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                    <!-- Tanggal Lahir -->
                                    <div class="space-y-2">
                                        <label class="text-sm font-medium text-slate-700 dark:text-slate-300">Tanggal Lahir <span class="text-red-500">*</span></label>
                                        <div class="relative group">
                                            <Calendar class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-slate-400 group-focus-within:text-indigo-500 transition-colors" />
                                            <input v-model="form.birth_date" type="date" class="w-full pl-10 pr-4 py-2.5 rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-800 focus:bg-white dark:focus:bg-slate-900 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all" required />
                                        </div>
                                    </div>
                                    <!-- Jam Lahir -->
                                    <div class="space-y-2">
                                        <label class="text-sm font-medium text-slate-700 dark:text-slate-300">Jam Lahir <span class="text-red-500">*</span></label>
                                        <div class="relative group">
                                            <Clock class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-slate-400 group-focus-within:text-indigo-500 transition-colors" />
                                            <input v-model="form.birth_time" type="time" step="1" class="w-full pl-10 pr-4 py-2.5 rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-800 focus:bg-white dark:focus:bg-slate-900 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all" required />
                                        </div>
                                    </div>
                                    <!-- Jenis Kelamin -->
                                    <div class="space-y-2">
                                        <label class="text-sm font-medium text-slate-700 dark:text-slate-300">Jenis Kelamin <span class="text-red-500">*</span></label>
                                        <div class="flex bg-slate-50 dark:bg-slate-800 rounded-lg p-1 border border-slate-200 dark:border-slate-700">
                                            <button 
                                                type="button" 
                                                @click="form.gender = 'Laki-laki'"
                                                :class="[
                                                    'flex-1 py-1.5 px-3 rounded-md text-sm font-medium transition-all duration-200 flex items-center justify-center gap-2',
                                                    form.gender === 'Laki-laki' 
                                                        ? 'bg-blue-100 text-blue-700 shadow-sm border border-blue-200 dark:bg-blue-900/30 dark:text-blue-300 dark:border-blue-800' 
                                                        : 'text-slate-500 hover:text-slate-700 dark:text-slate-400'
                                                ]"
                                            >
                                                Laki-laki
                                            </button>
                                            <button 
                                                type="button" 
                                                @click="form.gender = 'Perempuan'"
                                                :class="[
                                                    'flex-1 py-1.5 px-3 rounded-md text-sm font-medium transition-all duration-200 flex items-center justify-center gap-2',
                                                    form.gender === 'Perempuan' 
                                                        ? 'bg-pink-100 text-pink-700 shadow-sm border border-pink-200 dark:bg-pink-900/30 dark:text-pink-300 dark:border-pink-800' 
                                                        : 'text-slate-500 hover:text-slate-700 dark:text-slate-400'
                                                ]"
                                            >
                                                Perempuan
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Right Side: Detail Fisik -->
                            <div class="lg:col-span-4 bg-slate-50/80 dark:bg-slate-800/50 rounded-xl p-6 border border-slate-100 dark:border-slate-700/50 space-y-5">
                                <h3 class="font-semibold text-slate-700 dark:text-slate-200 flex items-center gap-2 text-sm uppercase tracking-wider">
                                    <Activity class="h-4 w-4" /> Pengukuran Fisik
                                </h3>
                                
                                <div class="grid grid-cols-2 gap-4">
                                    <div class="space-y-2">
                                        <label class="text-xs font-medium text-slate-500 uppercase">Berat (gram)</label>
                                        <div class="relative">
                                            <Scale class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-slate-400" />
                                            <input v-model="form.weight" type="number" step="0.01" class="w-full pl-9 pr-3 py-2 rounded-lg border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 text-sm" placeholder="3200" required />
                                        </div>
                                    </div>
                                    <div class="space-y-2">
                                        <label class="text-xs font-medium text-slate-500 uppercase">Panjang (cm)</label>
                                        <div class="relative">
                                            <Ruler class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-slate-400" />
                                            <input v-model="form.length" type="number" step="0.1" class="w-full pl-9 pr-3 py-2 rounded-lg border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 text-sm" placeholder="48" required />
                                        </div>
                                    </div>
                                    <div class="space-y-2">
                                        <label class="text-xs font-medium text-slate-500 uppercase">Lingkar Kepala</label>
                                        <div class="relative">
                                            <Activity class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-slate-400" />
                                            <input v-model="form.head_circ" type="number" step="0.1" class="w-full pl-9 pr-3 py-2 rounded-lg border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 text-sm" placeholder="34" required />
                                        </div>
                                    </div>
                                    <div class="space-y-2">
                                        <label class="text-xs font-medium text-slate-500 uppercase">Lingkar Dada</label>
                                        <div class="relative">
                                            <Activity class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-slate-400" />
                                            <input v-model="form.chest_circ" type="number" step="0.1" class="w-full pl-9 pr-3 py-2 rounded-lg border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 text-sm" placeholder="32" required />
                                        </div>
                                    </div>
                                </div>

                                <div class="grid grid-cols-2 gap-4 pt-2 border-t border-slate-200 dark:border-slate-700">
                                    <div class="space-y-2">
                                        <label class="text-xs font-medium text-slate-500 uppercase">Gol. Darah</label>
                                        <div class="relative">
                                            <Droplet class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-slate-400" />
                                            <select v-model="form.baby_blood_type" class="w-full pl-9 pr-3 py-2 rounded-lg border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 text-sm appearance-none">
                                                <option value="">-</option>
                                                <option v-for="bt in bloodTypes" :key="bt" :value="bt">{{ bt }}</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="space-y-2">
                                        <label class="text-xs font-medium text-slate-500 uppercase">Anak Ke-</label>
                                        <input v-model="form.child_order" type="number" min="1" class="w-full px-3 py-2 rounded-lg border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 text-sm" required />
                                    </div>
                                </div>
                                
                                <div class="pt-2">
                                    <label class="text-xs font-medium text-slate-500 uppercase mb-2 block">Metode Persalinan</label>
                                    <select v-model="form.delivery_method" class="w-full px-4 py-2 rounded-lg border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 text-sm">
                                        <option v-for="method in deliveryMethods" :key="method" :value="method">{{ method }}</option>
                                    </select>
                                </div>
                            </div>
                        </CardContent>
                    </Card>
                </div>

                <!-- Section: Data Orang Tua -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <!-- Data Ibu -->
                    <div class="space-y-4">
                        <div class="flex items-center gap-2 text-pink-600 dark:text-pink-400 font-semibold px-1">
                            <Heart class="h-5 w-5" />
                            <h2>Data Ibu</h2>
                            <div class="h-px bg-pink-100 dark:bg-pink-900/30 flex-1 ml-4"></div>
                        </div>

                        <Card class="border-pink-100/50 dark:border-pink-900/20 shadow-xl shadow-pink-500/5 h-full">
                            <CardContent class="p-6 space-y-5">
                                <div class="space-y-2">
                                    <label class="text-sm font-medium text-slate-700 dark:text-slate-300">Nama Ibu <span class="text-red-500">*</span></label>
                                    <div class="relative group">
                                        <User class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-slate-400 group-focus-within:text-pink-500 transition-colors" />
                                        <input v-model="form.mother_name" type="text" class="w-full pl-10 pr-4 py-2.5 rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-800 focus:bg-white dark:focus:bg-slate-900 focus:ring-2 focus:ring-pink-500/20 focus:border-pink-500 transition-all" placeholder="Nama lengkap ibu" required />
                                    </div>
                                </div>

                                <div class="grid grid-cols-2 gap-4">
                                     <div class="space-y-2">
                                        <label class="text-xs font-medium text-slate-600 uppercase">No. RM (Opsional)</label>
                                        <input v-model="form.mother_medical_record_no" type="text" class="w-full px-3 py-2 rounded-lg border border-slate-200 focus:border-pink-500 focus:ring-2 focus:ring-pink-200 text-sm bg-slate-50/50" placeholder="RM Ibu" />
                                    </div>
                                    <div class="space-y-2">
                                        <label class="text-xs font-medium text-slate-600 uppercase">KTP / Paspor</label>
                                        <input v-model="form.mother_ktp" type="text" class="w-full px-3 py-2 rounded-lg border border-slate-200 focus:border-pink-500 focus:ring-2 focus:ring-pink-200 text-sm bg-slate-50/50" placeholder="Nomor identitas" />
                                    </div>
                                </div>

                                <div class="grid grid-cols-2 gap-4">
                                    <div class="space-y-2">
                                        <label class="text-xs font-medium text-slate-600 uppercase">Gol. Darah</label>
                                        <select v-model="form.mother_blood_type" class="w-full px-3 py-2 rounded-lg border border-slate-200 focus:border-pink-500 focus:ring-2 focus:ring-pink-200 text-sm bg-slate-50/50">
                                            <option value="">- Pilih -</option>
                                            <option v-for="bt in bloodTypes" :key="bt" :value="bt">{{ bt }}</option>
                                        </select>
                                    </div>
                                    <div class="space-y-2">
                                        <label class="text-xs font-medium text-slate-600 uppercase">Pekerjaan</label>
                                        <input v-model="form.mother_occupation" type="text" class="w-full px-3 py-2 rounded-lg border border-slate-200 focus:border-pink-500 focus:ring-2 focus:ring-pink-200 text-sm bg-slate-50/50" placeholder="Pekerjaan" />
                                    </div>
                                </div>

                                <div class="space-y-2">
                                    <label class="text-sm font-medium text-slate-700 dark:text-slate-300">Alamat Lengkap <span class="text-red-500">*</span></label>
                                    <div class="relative group">
                                        <MapPin class="absolute left-3 top-3 h-4 w-4 text-slate-400 group-focus-within:text-pink-500 transition-colors" />
                                        <textarea v-model="form.mother_address" rows="3" class="w-full pl-10 pr-4 py-2.5 rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-800 focus:bg-white dark:focus:bg-slate-900 focus:ring-2 focus:ring-pink-500/20 focus:border-pink-500 transition-all resize-none" placeholder="Alamat sesuai KTP/Domisili" required></textarea>
                                    </div>
                                </div>
                            </CardContent>
                        </Card>
                    </div>

                    <!-- Data Ayah -->
                    <div class="space-y-4">
                        <div class="flex items-center gap-2 text-emerald-600 dark:text-emerald-400 font-semibold px-1">
                            <Briefcase class="h-5 w-5" />
                            <h2>Data Ayah</h2>
                            <div class="h-px bg-emerald-100 dark:bg-emerald-900/30 flex-1 ml-4"></div>
                        </div>

                        <Card class="border-emerald-100/50 dark:border-emerald-900/20 shadow-xl shadow-emerald-500/5 h-full">
                            <CardContent class="p-6 space-y-5">
                                <div class="space-y-2">
                                    <label class="text-sm font-medium text-slate-700 dark:text-slate-300">Nama Ayah <span class="text-red-500">*</span></label>
                                    <div class="relative group">
                                        <User class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-slate-400 group-focus-within:text-emerald-500 transition-colors" />
                                        <input v-model="form.father_name" type="text" class="w-full pl-10 pr-4 py-2.5 rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-800 focus:bg-white dark:focus:bg-slate-900 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all" placeholder="Nama lengkap ayah" required />
                                    </div>
                                </div>

                                <div class="grid grid-cols-2 gap-4">
                                     <div class="space-y-2">
                                        <label class="text-xs font-medium text-slate-600 uppercase">KTP / Paspor</label>
                                        <input v-model="form.father_ktp" type="text" class="w-full px-3 py-2 rounded-lg border border-slate-200 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 text-sm bg-slate-50/50" placeholder="Nomor identitas" />
                                    </div>
                                    <div class="space-y-2">
                                        <label class="text-xs font-medium text-slate-600 uppercase">Pekerjaan</label>
                                        <input v-model="form.father_occupation" type="text" class="w-full px-3 py-2 rounded-lg border border-slate-200 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 text-sm bg-slate-50/50" placeholder="Pekerjaan" />
                                    </div>
                                </div>

                                <div class="grid grid-cols-2 gap-4">
                                    <div class="space-y-2">
                                        <label class="text-xs font-medium text-slate-600 uppercase">Gol. Darah</label>
                                        <select v-model="form.father_blood_type" class="w-full px-3 py-2 rounded-lg border border-slate-200 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 text-sm bg-slate-50/50">
                                            <option value="">- Pilih -</option>
                                            <option v-for="bt in bloodTypes" :key="bt" :value="bt">{{ bt }}</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="space-y-2">
                                    <div class="flex justify-between items-center">
                                        <label class="text-sm font-medium text-slate-700 dark:text-slate-300">Alamat</label>
                                        <button 
                                            type="button" 
                                            @click="form.father_address = form.mother_address" 
                                            class="text-xs text-indigo-600 hover:text-indigo-700 hover:underline"
                                        >
                                            Sama dengan Ibu
                                        </button>
                                    </div>
                                    <div class="relative group">
                                        <MapPin class="absolute left-3 top-3 h-4 w-4 text-slate-400 group-focus-within:text-emerald-500 transition-colors" />
                                        <textarea v-model="form.father_address" rows="3" class="w-full pl-10 pr-4 py-2.5 rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-800 focus:bg-white dark:focus:bg-slate-900 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all resize-none" placeholder="Alamat tempat tinggal"></textarea>
                                    </div>
                                </div>
                            </CardContent>
                        </Card>
                    </div>
                </div>

                <!-- Section: Validation -->
                <div class="space-y-4">
                    <div class="flex items-center gap-2 text-violet-600 dark:text-violet-400 font-semibold px-1">
                        <CheckCircle2 class="h-5 w-5" />
                        <h2>Validasi Medis</h2>
                        <div class="h-px bg-violet-100 dark:bg-violet-900/30 flex-1 ml-4"></div>
                    </div>

                    <Card class="border-violet-100/50 dark:border-violet-900/20 shadow-xl shadow-violet-500/5 bg-gradient-to-br from-white to-violet-50/50 dark:from-slate-800 dark:to-violet-900/10">
                        <CardContent class="p-8">
                             <div class="flex flex-col md:flex-row items-center gap-8">
                                <div class="hidden md:flex h-16 w-16 items-center justify-center rounded-2xl bg-violet-100 text-violet-600 shadow-inner">
                                    <Stethoscope class="h-8 w-8" />
                                </div>
                                <div class="flex-1 w-full space-y-2">
                                    <label class="text-sm font-medium text-slate-700 dark:text-slate-300">Dokter Penanggung Jawab Persalinan <span class="text-red-500">*</span></label>
                                    <div class="relative group">
                                        <User class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-slate-400 group-focus-within:text-violet-500 transition-colors" />
                                        <select 
                                            v-model="form.doctor_id"
                                            class="w-full pl-10 pr-4 py-3 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 focus:bg-white dark:focus:bg-slate-900 focus:ring-2 focus:ring-violet-500/20 focus:border-violet-500 transition-all cursor-pointer"
                                            required
                                        >
                                            <option value="" disabled>-- Pilih Dokter yang Bertugas --</option>
                                            <option v-for="doc in doctors" :key="doc.id" :value="doc.id">{{ doc.name }}</option>
                                        </select>
                                    </div>
                                    <p class="text-xs text-slate-500 ml-1">Nama dokter ini akan tercantum pada Surat Keterangan Lahir (SKL) digital.</p>
                                </div>
                             </div>
                        </CardContent>
                    </Card>
                </div>

                <!-- Action Bar -->
                <div class="sticky bottom-4 z-10 bg-white/80 dark:bg-slate-900/80 backdrop-blur-lg border border-slate-200 dark:border-slate-700 p-4 rounded-2xl shadow-2xl flex items-center justify-between">
                    <p class="text-sm text-slate-500 hidden md:block">
                        Pastikan seluruh data telah sesuai dengan dokumen rekam medis.
                    </p>
                    <div class="flex items-center gap-3 w-full md:w-auto justify-end">
                        <Link href="/birth-records">
                            <Button type="button" variant="ghost" class="text-slate-600 hover:text-slate-800 hover:bg-slate-100">
                                Batal
                            </Button>
                        </Link>
                        <Button 
                            type="submit" 
                            :disabled="form.processing"
                            class="min-w-[180px] gap-2 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white shadow-lg shadow-indigo-500/25 transition-all duration-300 hover:shadow-xl hover:-translate-y-0.5"
                        >
                            <Save class="h-4 w-4" />
                            {{ form.processing ? 'Menyimpan...' : 'Simpan & Buat SKL' }}
                        </Button>
                    </div>
                </div>

            </form>
        </div>
    </AppLayout>
</template>
