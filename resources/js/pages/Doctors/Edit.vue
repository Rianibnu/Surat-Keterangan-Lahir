<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Card, CardContent, CardHeader, CardTitle, CardDescription, CardFooter } from '@/components/ui/card';
import { ArrowLeft, Save, Stethoscope, Building2, FileBadge } from 'lucide-vue-next';
import SignaturePad from '@/components/SignaturePad.vue';
const props = defineProps<{
    doctor: any;
}>();

const form = useForm({
    name: props.doctor.name as string,
    hospital: props.doctor.hospital as string,
    license_no: (props.doctor.license_no || '') as string,
    signature: '',
});

const submit = () => {
    console.log('Submit function called!', form.data());
    form.put(`/doctors/${props.doctor.id}`, {
        preserveScroll: true,
        onSuccess: () => {
            console.log('Form submitted successfully');
        },
        onError: (errors) => {
            console.error('Form submission errors:', errors);
        },
    });
};
</script>


<template>
    <Head title="Edit Data Dokter" />

    <AppLayout :breadcrumbs="[
        { title: 'Dashboard', href: '/dashboard' },
        { title: 'Data Dokter', href: '/doctors' },
        { title: 'Edit Dokter', href: `/doctors/${props.doctor.id}/edit` },
    ]">
        <div class="max-w-3xl mx-auto p-4 md:p-8">
            <div class="mb-8 flex items-center gap-4">
                <Button variant="ghost" size="icon" as-child class="-ml-2">
                    <Link href="/doctors">
                        <ArrowLeft class="h-5 w-5" />
                    </Link>
                </Button>
                <div>
                    <h1 class="text-2xl font-bold tracking-tight text-slate-900 dark:text-slate-100">Edit Data Dokter</h1>
                    <p class="text-sm text-slate-500 mt-1">Perbarui informasi data dokter yang sudah terdaftar.</p>
                </div>
            </div>

            <form @submit.prevent="submit">
                <Card class="border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden">
                    <CardHeader class="border-b border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-900/50 pb-8 pt-6">
                        <div class="flex items-center gap-3 mb-1">
                            <div class="p-2 bg-indigo-100 dark:bg-indigo-900/40 rounded-lg text-indigo-600 dark:text-indigo-400">
                                <Stethoscope class="h-6 w-6" />
                            </div>
                            <div>
                                <CardTitle>Informasi Dokter</CardTitle>
                                <CardDescription>Data identitas dan profesi dokter penanggung jawab.</CardDescription>
                            </div>
                        </div>
                    </CardHeader>
                    
                    <CardContent class="grid gap-6 p-6 pt-8">
                        <!-- Nama Dokter -->
                        <div class="grid gap-2">
                            <Label for="name" class="flex items-center gap-2">
                                Nama Dokter <span class="text-red-500">*</span>
                            </Label>
                            <div class="relative">
                                <Stethoscope class="absolute left-3 top-3 h-4 w-4 text-slate-400" />
                                <Input 
                                    id="name" 
                                    v-model="form.name" 
                                    class="pl-9" 
                                    placeholder="Contoh: Budi Santoso, Sp.OG"
                                    required
                                />
                            </div>
                            <p v-if="form.errors.name" class="text-sm text-red-500">{{ form.errors.name }}</p>
                        </div>

                        <!-- Rumah Sakit -->
                        <div class="grid gap-2">
                            <Label for="hospital" class="flex items-center gap-2">
                                Rumah Sakit / Klinik <span class="text-red-500">*</span>
                            </Label>
                            <div class="relative">
                                <Building2 class="absolute left-3 top-3 h-4 w-4 text-slate-400" />
                                <Input 
                                    id="hospital" 
                                    v-model="form.hospital" 
                                    class="pl-9" 
                                    placeholder="Contoh: RS Unggul Karsa Medika"
                                    required
                                />
                            </div>
                            <p v-if="form.errors.hospital" class="text-sm text-red-500">{{ form.errors.hospital }}</p>
                        </div>

                        <!-- Nomor SIP -->
                        <div class="grid gap-2">
                            <Label for="license_no" class="flex items-center gap-2">
                                Nomor SIP (Surat Izin Praktik)
                            </Label>
                            <div class="relative">
                                <FileBadge class="absolute left-3 top-3 h-4 w-4 text-slate-400" />
                                <Input 
                                    id="license_no" 
                                    v-model="form.license_no" 
                                    class="pl-9" 
                                    placeholder="Contoh: 123/SIP/DINKES/2024"
                                />
                            </div>
                            <p v-if="form.errors.license_no" class="text-sm text-red-500">{{ form.errors.license_no }}</p>
                        </div>

                        <!-- Tanda Tangan Digital -->
                        <div class="grid gap-2">
                            <Label class="flex items-center gap-2">Tanda Tangan Digital</Label>
                            
                            <div v-if="props.doctor.signature_path" class="mb-2 p-3 border border-slate-200 rounded-lg bg-slate-50 flex items-center gap-4">
                                <div class="bg-white border p-1 rounded">
                                    <img :src="`/storage/${props.doctor.signature_path}`" alt="Tanda Tangan" class="h-12 object-contain">
                                </div>
                                <div class="text-xs text-slate-500">
                                    <p class="font-medium text-slate-700">Tanda Tangan Tersimpan</p>
                                    <p>Gambar ulang di bawah untuk mengganti.</p>
                                </div>
                            </div>

                            <SignaturePad v-model="form.signature" />
                            <p v-if="form.errors.signature" class="text-sm text-red-500">{{ form.errors.signature }}</p>
                        </div>
                    </CardContent>

                    <CardFooter class="bg-slate-50/50 dark:bg-slate-900/50 px-6 py-4 flex justify-end gap-3 border-t border-slate-100 dark:border-slate-800">
                        <Button type="button" variant="outline" as-child>
                            <Link href="/doctors">Batal</Link>
                        </Button>
                        <button 
                            type="submit"
                            :disabled="form.processing" 
                            class="inline-flex items-center justify-center bg-indigo-600 hover:bg-indigo-700 text-white min-w-[120px] h-9 px-4 py-2 rounded-md font-medium text-sm transition-colors disabled:opacity-50 disabled:pointer-events-none"
                            @click="submit"
                        >
                            <Save v-if="!form.processing" class="mr-2 h-4 w-4" />
                            <span v-else class="mr-2 h-4 w-4 animate-spin rounded-full border-2 border-white border-t-transparent"></span>
                            {{ form.processing ? 'Menyimpan...' : 'Simpan Perubahan' }}
                        </button>
                    </CardFooter>
                </Card>
            </form>
        </div>
    </AppLayout>
</template>
