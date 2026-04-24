<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import { ArrowLeft, Printer, FileText, Download } from 'lucide-vue-next';
import { Button } from '@/components/ui/button';
import QrcodeVue from 'qrcode.vue';
import { onMounted, ref } from 'vue';
import { usePermissions } from '@/composables/usePermissions';

const { can } = usePermissions();

const props = defineProps<{
    record: any;
}>();

const originUrl = ref('');

onMounted(() => {
    originUrl.value = window.location.origin;
});

const print = () => {
    window.print();
};

const generateSkl = () => {
    if (confirm('Apakah Anda yakin ingin menerbitkan SKL?')) {
        router.post(`/birth-records/${props.record.id}/generate-skl`, {}, {
            onSuccess: () => alert('SKL Berhasil Diterbitkan!'),
            onError: (err) => alert('Gagal: ' + JSON.stringify(err)),
            onFinish: () => router.reload()
        });
    }
};

const formatDate = (dateString: string) => {
    if (!dateString) return '';
    return new Date(dateString).toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });
};

const getDayName = (dateString: string) => {
    if (!dateString) return '';
    return new Date(dateString).toLocaleDateString('id-ID', { weekday: 'long' });
};

const goBack = () => {
    router.visit('/birth-records');
};
</script>

<template>
    <Head title="Cetak SKL" />
    
    <!-- Screen View (Non-Print) -->
    <div class="min-h-screen bg-linear-to-br from-slate-100 via-blue-50 to-indigo-100 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900 print:hidden">
        <!-- Floating Toolbar -->
        <div class="fixed top-0 left-0 right-0 z-50 bg-white/80 dark:bg-gray-900/80 backdrop-blur-xl border-b border-gray-200 dark:border-gray-700">
            <div class="max-w-[210mm] mx-auto px-4 py-3 flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <Button variant="ghost" size="sm" class="gap-2" @click="goBack">
                        <ArrowLeft class="h-4 w-4" />
                        Kembali
                    </Button>
                </div>
            <div class="flex items-center gap-2">
                <Link
                    v-if="!record.skl && can('skl.create')"
                    :href="`/birth-records/${record.id}/generate-skl`"
                    method="post"
                    as="button"
                    :onSuccess="() => router.reload()"
                    class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border bg-background h-10 px-4 py-2 gap-2 border-indigo-200 text-indigo-700 hover:bg-indigo-50"
                >
                    <FileText class="h-4 w-4" />
                    Terbitkan SKL
                </Link>

                <div class="flex items-center gap-2">
                    <!-- Download PDF Button -->
                    <a 
                        v-if="record.skl && can('skl.download-pdf')"
                        :href="`/birth-records/${record.id}/skl/download`"
                        class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 border bg-background h-10 px-4 py-2 gap-2 border-emerald-200 text-emerald-700 hover:bg-emerald-50"
                    >
                        <Download class="h-4 w-4" />
                        Download PDF
                    </a>
                    
                    <Button 
                        v-if="can('skl.print')"
                        @click="print" 
                        class="gap-2 bg-linear-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 shadow-lg shadow-indigo-500/25 transition-all duration-300 hover:shadow-xl"
                    >
                        <Printer class="h-4 w-4" />
                        Cetak SKL
                    </Button>
                </div>
            </div>
            </div>
        </div>

        <!-- Document Preview Container -->
        <div class="pt-20 pb-12 px-4">
            <!-- Flash Messages -->
            <div v-if="$page.props.flash?.success" class="max-w-[210mm] mx-auto mb-6 p-4 text-green-700 bg-green-100 border border-green-200 rounded-lg flex items-center gap-2 shadow-sm">
                <FileText class="h-4 w-4" />
                {{ $page.props.flash.success }}
            </div>
            <div v-if="$page.props.flash?.error" class="max-w-[210mm] mx-auto mb-6 p-4 text-red-700 bg-red-100 border border-red-200 rounded-lg shadow-sm">
                {{ $page.props.flash.error }}
            </div>

            <!-- A4 Document Preview -->
            <div class="bg-white max-w-[210mm] w-full mx-auto shadow-2xl rounded-lg overflow-hidden border border-gray-200">
                <div class="skl-document p-[20mm]">
                   <!-- Content same as print view -->
                    <div class="text-center mb-8">
                        <h1 class="text-lg font-bold uppercase tracking-wide underline decoration-1 underline-offset-4 mb-1">SURAT KETERANGAN LAHIR</h1>
                        <p class="italic text-base font-serif mb-4">BIRTH CERTIFICATE</p>
                        
                        <div class="text-center">
                            <span class="mr-2">Nomor : </span>
                            <span class="font-medium underline decoration-dotted">{{ record.skl?.document_number || '/SKL/RM-RSUKM/   /20...' }}</span>
                        </div>
                    </div>

                    <div class="space-y-1 text-sm leading-relaxed mb-4">
                        <p>Yang bertanda tangan dibawah ini | <span class="italic">The undersigned</span> : <strong>{{ record.doctor?.name }}</strong></p>
                        <p>Menerangkan dengan sesungguhnya bahwa | <span class="italic">Certifies that</span> :</p>
                    </div>
                    
                    <div class="pl-4 mb-4 text-sm">
                         <div class="flex gap-8">
                            <div class="flex gap-2">
                                <span>Pada | <span class="italic">On</span></span>
                                <span>: Hari | <span class="italic">Day</span></span>
                                <strong>: {{ getDayName(record.birth_date) }}</strong>
                            </div>
                            <div class="flex gap-2">
                                <span>Tanggal | <span class="italic">Date</span></span>
                                <strong>: {{ formatDate(record.birth_date) }}</strong>
                            </div>
                            <div class="flex gap-2">
                                <span>Jam | <span class="italic">Time</span></span>
                                <strong>: {{ record.birth_time }} WIB</strong>
                            </div>
                        </div>
                        <div class="mt-1">
                            Telah lahir seorang bayi di Rumah Sakit X | <span class="italic">Has been born a baby at Rumah Sakit X</span>
                        </div>
                    </div>

                    <!-- Main Data Table with Fixed Column Widths -->
                    <table class="w-full text-sm">
                        <colgroup>
                            <col style="width: 240px">
                            <col style="width: 20px">
                            <col>
                        </colgroup>
                        <tbody>
                            <tr>
                                <td class="py-1">Nama Bayi | <span class="italic">Baby's name</span></td>
                                <td class="align-top">:</td>
                                <td class="font-bold">{{ record.baby_name }}</td>
                            </tr>
                            <tr>
                                <td class="py-1">No. Rekam Medis | <span class="italic">Medical Records No.</span></td>
                                <td class="align-top">:</td>
                                <td>{{ record.medical_record_no }}</td>
                            </tr>
                            <tr>
                                <td class="py-1">Jenis Kelamin | <span class="italic">Gender</span></td>
                                <td class="align-top">:</td>
                                <td>{{ record.gender }}</td>
                            </tr>
                            <tr>
                                <td class="py-1">Anak Ke- | <span class="italic">Child no.-</span></td>
                                <td class="align-top">:</td>
                                <td>{{ record.child_order }}</td>
                            </tr>
                            <tr>
                                <td class="py-1">Cara Persalinan | <span class="italic">Method of delivery</span></td>
                                <td class="align-top">:</td>
                                <td>{{ record.delivery_method }}</td>
                            </tr>
                            <tr>
                                <td class="py-1">Gol. Darah | <span class="italic">Blood group</span></td>
                                <td class="align-top">:</td>
                                <td>{{ record.baby_blood_type || '-' }}</td>
                            </tr>
                        </tbody>
                    </table>

                    <!-- Measurements - Split table but aligned manually -->
                    <table class="w-full mt-1 text-sm table-fixed">
                        <colgroup>
                            <col style="width: 240px">
                            <col style="width: 20px">
                            <col style="width: 100px"> 
                            <col style="width: 170px"> <!-- Label Column for Right Side -->
                            <col style="width: 20px">
                            <col>
                        </colgroup>
                        <tbody>
                            <tr>
                                <td class="py-1">Berat | <span class="italic">Weight</span></td>
                                <td class="align-top">:</td>
                                <td style="white-space: nowrap">{{ record.weight }} gram</td>
                                <td class="pl-4">Lingkar kepala | <span class="italic">Head circ.</span></td>
                                <td class="align-top">:</td>
                                <td style="white-space: nowrap">{{ record.head_circ }} cm</td>
                            </tr>
                            <tr>
                                <td class="py-1">Panjang | <span class="italic">Length</span></td>
                                <td class="align-top">:</td>
                                <td style="white-space: nowrap">{{ record.length }} cm</td>
                                <td class="pl-4">Lingkar dada | <span class="italic">Chest circ.</span></td>
                                <td class="align-top">:</td>
                                <td style="white-space: nowrap">{{ record.chest_circ }} cm</td>
                            </tr>
                        </tbody>
                    </table>

                    <!-- Mother Section -->
                    <div class="mt-4 mb-2 font-bold text-center underline decoration-dotted">IBU | <span class="italic font-normal">MOTHER</span></div>
                    <table class="w-full text-sm">
                        <colgroup>
                            <col style="width: 240px">
                            <col style="width: 20px">
                            <col>
                        </colgroup>
                        <tbody>
                            <tr>
                                <td class="py-1">Nama Ibu | <span class="italic">Mother's name</span></td>
                                <td class="align-top">:</td>
                                <td class="font-bold">{{ record.mother_name }}</td>
                            </tr>
                            <tr>
                                <td class="py-1">No. Rekam Medis | <span class="italic">Medical Records No.</span></td>
                                <td class="align-top">:</td>
                                <td>{{ record.mother_medical_record_no || '-' }}</td>
                            </tr>
                            <tr>
                                <td class="py-1">No. KTP / Paspor | <span class="italic">ID No. / Passport No.</span></td>
                                <td class="align-top">:</td>
                                <td>{{ record.mother_ktp || '-' }}</td>
                            </tr>
                            <tr>
                                <td class="py-1 align-top">Alamat | <span class="italic">Address</span></td>
                                <td class="align-top">:</td>
                                <td>{{ record.mother_address }}</td>
                            </tr>
                            <tr>
                                <td class="py-1">Pekerjaan | <span class="italic">Occupation</span></td>
                                <td class="align-top">:</td>
                                <td>{{ record.mother_occupation || '-' }}</td>
                            </tr>
                            <tr>
                                <td class="py-1">Gol. Darah | <span class="italic">Blood group</span></td>
                                <td class="align-top">:</td>
                                <td>{{ record.mother_blood_type || '-' }}</td>
                            </tr>
                        </tbody>
                    </table>

                    <!-- Father Section -->
                    <div class="mt-4 mb-2 font-bold text-center underline decoration-dotted">AYAH | <span class="italic font-normal">FATHER</span></div>
                    <table class="w-full text-sm">
                        <colgroup>
                            <col style="width: 240px">
                            <col style="width: 20px">
                            <col>
                        </colgroup>
                        <tbody>
                            <tr>
                                <td class="py-1">Nama Ayah | <span class="italic">Father's name</span></td>
                                <td class="align-top">:</td>
                                <td class="font-bold">{{ record.father_name }}</td>
                            </tr>
                            <tr>
                                <td class="py-1">No. KTP / Paspor | <span class="italic">ID No. / Passport No.</span></td>
                                <td class="align-top">:</td>
                                <td>{{ record.father_ktp || '-' }}</td>
                            </tr>
                            <tr>
                                <td class="py-1 align-top">Alamat | <span class="italic">Address</span></td>
                                <td class="align-top">:</td>
                                <td>{{ record.father_address || '-' }}</td>
                            </tr>
                            <tr>
                                <td class="py-1">Pekerjaan | <span class="italic">Occupation</span></td>
                                <td class="align-top">:</td>
                                <td>{{ record.father_occupation || '-' }}</td>
                            </tr>
                            <tr>
                                <td class="py-1">Gol. Darah | <span class="italic">Blood group</span></td>
                                <td class="align-top">:</td>
                                <td>{{ record.father_blood_type || '-' }}</td>
                            </tr>
                        </tbody>
                    </table>

                    <!-- Footer with QR and Signature -->
                    <div class="mt-12 flex justify-between items-end">
                        <div class="mb-4 pl-4">
                             <div class="bg-white p-1 inline-block border border-slate-100">
                                <QrcodeVue 
                                    v-if="record.skl?.uuid"
                                    :value="`${originUrl}/verify-skl/${record.skl.uuid}`" 
                                    :size="90" 
                                    level="H" 
                                    render-as="svg"
                                />
                             </div>
                             <p v-if="record.skl?.uuid" class="text-[10px] mt-1 text-slate-500 font-sans">Scan untuk verifikasi</p>
                             <p v-if="record.skl?.uuid" class="text-[9px] mt-0 text-slate-400 font-mono tracking-tighter">{{ record.skl.uuid }}</p>
                        </div>

                        <div class="text-center w-[250px]">
                            <p class="mb-1">Kabupaten Bandung,</p>
                            <p class="font-bold">Dokter Penanggung Jawab Persalinan</p>
                            <p class="italic text-xs">Doctor in charge of delivery</p>
                            
                            <!-- Doctor's Digital Signature -->
                            <div class="h-16 my-2 flex items-center justify-center">
                                <img 
                                    v-if="record.doctor?.signature_path" 
                                    :src="`/storage/${record.doctor.signature_path}`" 
                                    alt="Tanda Tangan Dokter" 
                                    class="max-h-16 object-contain"
                                />
                            </div>
                            
                            <p class="font-bold underline">{{ record.doctor?.name }}</p>
                            <p class="text-sm">(Nama dan tanda tangan)</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Print View -->
    <div class="hidden print:block skl-print">
        <div class="text-center mb-8">
            <h1 class="text-lg font-bold uppercase tracking-wide underline decoration-1 underline-offset-4 mb-1">SURAT KETERANGAN LAHIR</h1>
            <p class="italic text-base font-serif mb-4">BIRTH CERTIFICATE</p>
            
            <div class="text-center">
                <span class="mr-2">Nomor : </span>
                <span class="font-medium underline decoration-dotted">{{ record.skl?.document_number || '/SKL/RM-RSUKM/   /20...' }}</span>
            </div>
        </div>

        <div class="space-y-1 text-sm leading-relaxed mb-4">
            <p>Yang bertanda tangan dibawah ini | <span class="italic">The undersigned</span> : <strong>{{ record.doctor?.name }}</strong></p>
            <p>Menerangkan dengan sesungguhnya bahwa | <span class="italic">Certifies that</span> :</p>
        </div>
        
        <div class="pl-4 mb-4 text-sm">
                <div class="flex gap-8">
                <div class="flex gap-2">
                    <span>Pada | <span class="italic">On</span></span>
                    <span>: Hari | <span class="italic">Day</span></span>
                    <strong>: {{ getDayName(record.birth_date) }}</strong>
                </div>
                <div class="flex gap-2">
                    <span>Tanggal | <span class="italic">Date</span></span>
                    <strong>: {{ formatDate(record.birth_date) }}</strong>
                </div>
                <div class="flex gap-2">
                    <span>Jam | <span class="italic">Time</span></span>
                    <strong>: {{ record.birth_time }} WIB</strong>
                </div>
            </div>
            <div class="mt-1">
                Telah lahir seorang bayi di Rumah Sakit X | <span class="italic">Has been born a baby at Rumah Sakit X</span>
            </div>
        </div>

        <!-- Main Data Table with Fixed Column Widths -->
        <table class="w-full text-sm">
            <colgroup>
                <col style="width: 240px">
                <col style="width: 20px">
                <col>
            </colgroup>
            <tbody>
                <tr>
                    <td class="py-1">Nama Bayi | <span class="italic">Baby's name</span></td>
                    <td class="align-top">:</td>
                    <td class="font-bold">{{ record.baby_name }}</td>
                </tr>
                <tr>
                    <td class="py-1">No. Rekam Medis | <span class="italic">Medical Records No.</span></td>
                    <td class="align-top">:</td>
                    <td>{{ record.medical_record_no }}</td>
                </tr>
                <tr>
                    <td class="py-1">Jenis Kelamin | <span class="italic">Gender</span></td>
                    <td class="align-top">:</td>
                    <td>{{ record.gender }}</td>
                </tr>
                <tr>
                    <td class="py-1">Anak Ke- | <span class="italic">Child no.-</span></td>
                    <td class="align-top">:</td>
                    <td>{{ record.child_order }}</td>
                </tr>
                <tr>
                    <td class="py-1">Cara Persalinan | <span class="italic">Method of delivery</span></td>
                    <td class="align-top">:</td>
                    <td>{{ record.delivery_method }}</td>
                </tr>
                <tr>
                    <td class="py-1">Gol. Darah | <span class="italic">Blood group</span></td>
                    <td class="align-top">:</td>
                    <td>{{ record.baby_blood_type || '-' }}</td>
                </tr>
            </tbody>
        </table>

        <!-- Measurements - Split table for print view -->
        <table class="w-full mt-1 text-sm table-fixed">
            <colgroup>
                <col style="width: 240px">
                <col style="width: 20px">
                <col style="width: 100px"> 
                <col style="width: 170px"> <!-- Label Column for Right Side -->
                <col style="width: 20px">
                <col>
            </colgroup>
            <tbody>
                <tr>
                    <td class="py-1">Berat | <span class="italic">Weight</span></td>
                    <td class="align-top">:</td>
                    <td style="white-space: nowrap">{{ record.weight }} gram</td>
                    <td class="pl-4">Lingkar kepala | <span class="italic">Head circ.</span></td>
                    <td class="align-top">:</td>
                    <td style="white-space: nowrap">{{ record.head_circ }} cm</td>
                </tr>
                <tr>
                    <td class="py-1">Panjang | <span class="italic">Length</span></td>
                    <td class="align-top">:</td>
                    <td style="white-space: nowrap">{{ record.length }} cm</td>
                    <td class="pl-4">Lingkar dada | <span class="italic">Chest circ.</span></td>
                    <td class="align-top">:</td>
                    <td style="white-space: nowrap">{{ record.chest_circ }} cm</td>
                </tr>
            </tbody>
        </table>

        <!-- Mother Section -->
        <div class="mt-4 mb-2 font-bold text-center underline decoration-dotted">IBU | <span class="italic font-normal">MOTHER</span></div>
        <table class="w-full text-sm">
            <colgroup>
                <col style="width: 240px">
                <col style="width: 20px">
                <col>
            </colgroup>
            <tbody>
                <tr>
                    <td class="py-1">Nama Ibu | <span class="italic">Mother's name</span></td>
                    <td class="align-top">:</td>
                    <td class="font-bold">{{ record.mother_name }}</td>
                </tr>
                <tr>
                    <td class="py-1">No. Rekam Medis | <span class="italic">Medical Records No.</span></td>
                    <td class="align-top">:</td>
                    <td>{{ record.mother_medical_record_no || '-' }}</td>
                </tr>
                <tr>
                    <td class="py-1">No. KTP / Paspor | <span class="italic">ID No. / Passport No.</span></td>
                    <td class="align-top">:</td>
                    <td>{{ record.mother_ktp || '-' }}</td>
                </tr>
                <tr>
                    <td class="py-1 align-top">Alamat | <span class="italic">Address</span></td>
                    <td class="align-top">:</td>
                    <td>{{ record.mother_address }}</td>
                </tr>
                <tr>
                    <td class="py-1">Pekerjaan | <span class="italic">Occupation</span></td>
                    <td class="align-top">:</td>
                    <td>{{ record.mother_occupation || '-' }}</td>
                </tr>
                <tr>
                    <td class="py-1">Gol. Darah | <span class="italic">Blood group</span></td>
                    <td class="align-top">:</td>
                    <td>{{ record.mother_blood_type || '-' }}</td>
                </tr>
            </tbody>
        </table>

        <!-- Father Section -->
        <div class="mt-4 mb-2 font-bold text-center underline decoration-dotted">AYAH | <span class="italic font-normal">FATHER</span></div>
        <table class="w-full text-sm">
            <colgroup>
                <col style="width: 240px">
                <col style="width: 20px">
                <col>
            </colgroup>
            <tbody>
                <tr>
                    <td class="py-1">Nama Ayah | <span class="italic">Father's name</span></td>
                    <td class="align-top">:</td>
                    <td class="font-bold">{{ record.father_name }}</td>
                </tr>
                <tr>
                    <td class="py-1">No. KTP / Paspor | <span class="italic">ID No. / Passport No.</span></td>
                    <td class="align-top">:</td>
                    <td>{{ record.father_ktp || '-' }}</td>
                </tr>
                <tr>
                    <td class="py-1 align-top">Alamat | <span class="italic">Address</span></td>
                    <td class="align-top">:</td>
                    <td>{{ record.father_address || '-' }}</td>
                </tr>
                <tr>
                    <td class="py-1">Pekerjaan | <span class="italic">Occupation</span></td>
                    <td class="align-top">:</td>
                    <td>{{ record.father_occupation || '-' }}</td>
                </tr>
                <tr>
                    <td class="py-1">Gol. Darah | <span class="italic">Blood group</span></td>
                    <td class="align-top">:</td>
                    <td>{{ record.father_blood_type || '-' }}</td>
                </tr>
            </tbody>
        </table>

        <!-- Footer with QR and Signature -->
        <div class="mt-12 flex justify-between items-end">
            <!-- QR Code Section -->
            <div class="mb-4 pl-4">
                 <div class="bg-white p-1 inline-block">
                    <QrcodeVue 
                        v-if="record.skl?.uuid"
                        :value="`${originUrl}/verify-skl/${record.skl.uuid}`" 
                        :size="90" 
                        level="H" 
                        render-as="svg"
                    />
                 </div>
                 <p v-if="record.skl?.uuid" class="text-[10px] mt-1 text-slate-500 font-sans">Scan untuk verifikasi</p>
                 <p v-if="record.skl?.uuid" class="text-[9px] mt-0 text-slate-400 font-mono tracking-tighter">{{ record.skl.uuid }}</p>
            </div>

            <!-- Signature Section -->
            <div class="text-center w-[250px]">
                <p class="mb-1">Kabupaten Bandung,</p>
                <p class="font-bold">Dokter Penanggung Jawab Persalinan</p>
                <p class="italic text-xs">Doctor in charge of delivery</p>
                
                <!-- Doctor's Digital Signature -->
                <div class="h-16 my-2 flex items-center justify-center">
                    <img 
                        v-if="record.doctor?.signature_path" 
                        :src="`/storage/${record.doctor.signature_path}`" 
                        alt="Tanda Tangan Dokter" 
                        class="max-h-16 object-contain"
                    />
                </div>
                
                <p class="font-bold underline">{{ record.doctor?.name }}</p>
                <p class="text-sm">(Nama dan tanda tangan)</p>
            </div>
        </div>
    </div>
</template>

<style>
@media print {
    @page {
        margin: 20mm;
        size: 210mm 297mm; /* Standard A4 size */
    }
    
    body {
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
        font-family: 'Times New Roman', Times, serif !important;
    }
    
    .skl-print {
        width: 100%;
        max-width: 170mm; /* Content width fitting within A4 - margins */
        margin: 0 auto;
        font-family: 'Times New Roman', Times, serif;
        font-size: 11pt; /* Slightly adjusted for better fit */
        line-height: 1.3;
        color: black;
    }
    
    .skl-print table {
        border-collapse: collapse;
    }
    
    .skl-print td {
        vertical-align: top;
        padding: 2px 0;
    }
}

.skl-document {
    font-family: 'Times New Roman', Times, serif;
    font-size: 12pt;
    line-height: 1.4;
    color: black;
}

.skl-document table {
    border-collapse: collapse;
}

.skl-document td {
    vertical-align: top;
    padding: 2px 0;
}
</style>
