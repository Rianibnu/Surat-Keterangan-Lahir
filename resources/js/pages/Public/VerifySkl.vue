<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { CheckCircle2, ShieldCheck, Printer } from 'lucide-vue-next';
import { Button } from '@/components/ui/button';
import QrcodeVue from 'qrcode.vue';

const props = defineProps<{
    record: any;
}>();

const originUrl = typeof window !== 'undefined' ? window.location.origin : '';

const formatDate = (dateString: string) => {
    if (!dateString) return '';
    return new Date(dateString).toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });
};

const getDayName = (dateString: string) => {
    if (!dateString) return '';
    return new Date(dateString).toLocaleDateString('id-ID', { weekday: 'long' });
};

const print = () => {
    window.print();
};
</script>

<template>
    <Head title="Verifikasi SKL Online" />
    
    <div class="min-h-screen bg-slate-50 dark:bg-slate-900 pb-12">
        <!-- Verification Banner -->
        <div class="bg-green-600 text-white p-4 shadow-md print:hidden">
            <div class="max-w-[210mm] mx-auto flex items-center justify-center gap-3">
                <div class="bg-white/20 p-2 rounded-full">
                    <CheckCircle2 class="h-6 w-6" />
                </div>
                <div>
                    <h2 class="font-bold text-lg">Dokumen Valid & Terverifikasi</h2>
                    <p class="text-green-100 text-sm">Surat Keterangan Lahir ini resmi terdaftar di database RS Unggul Karsa Medika.</p>
                </div>
            </div>
        </div>

        <div class="px-4 py-8 flex justify-center">
            <div class="w-full max-w-[210mm]">
                <!-- Action Bar -->
                <div class="flex justify-between items-center mb-6 print:hidden">
                    <div class="flex items-center gap-2 text-slate-500">
                        <ShieldCheck class="h-5 w-5 text-indigo-600" />
                        <span class="text-sm font-medium">Sistem Verifikasi Digital</span>
                    </div>
                    <Button @click="print" variant="outline" size="sm" class="gap-2">
                        <Printer class="h-4 w-4" />
                        Cetak Ulang
                    </Button>
                </div>

                <!-- Document Preview (Read Only) -->
                <div class="bg-white w-full mx-auto shadow-2xl rounded-lg overflow-hidden border border-gray-200">
                    <div class="skl-document p-[20mm]">
                        <!-- This uses the same layout as Show.vue print view -->
                        <div class="text-center mb-8">
                            <h1 class="text-lg font-bold uppercase tracking-wide underline decoration-1 underline-offset-4 mb-1">SURAT KETERANGAN LAHIR</h1>
                        <p class="italic text-base font-serif mb-4">BIRTH CERTIFICATE</p>
                        
                        <div class="text-center">
                            <span class="mr-2">Nomor : </span>
                            <span class="font-medium underline decoration-dotted">{{ record.document_number || '/SKL/RM-RSUKM/   /20...' }}</span>
                        </div>
                    </div>

                    <div class="space-y-1 text-sm leading-relaxed mb-4">
                        <p>Yang bertanda tangan dibawah ini | <span class="italic">The undersigned</span> : <strong>dr. {{ record.doctor?.name }}</strong></p>
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
                            Telah lahir seorang bayi di RS Unggul Karsa Medika | <span class="italic">Has been born a baby at Unggul Karsa Medika Hospital</span>
                        </div>
                    </div>

                    <!-- Main Data Table -->
                    <table class="w-full text-sm table-fixed">
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

                    <!-- Measurements -->
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
                    <table class="w-full text-sm table-fixed">
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
                    <table class="w-full text-sm table-fixed">
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

                    <!-- Signature -->
                    <div class="mt-12 flex justify-between items-end">
                        <!-- QR Code Section -->
                        <div class="mb-4 pl-4">
                             <div class="bg-white p-1 inline-block">
                                <QrcodeVue 
                                    v-if="record.uuid"
                                    :value="`${originUrl}/verify-skl/${record.uuid}`" 
                                    :size="90" 
                                    level="H" 
                                    render-as="svg"
                                />
                             </div>
                             <p v-if="record.uuid" class="text-[10px] mt-1 text-slate-500 font-sans">Scan untuk verifikasi</p>
                             <p v-if="record.uuid" class="text-[9px] mt-0 text-slate-400 font-mono tracking-tighter">{{ record.uuid }}</p>
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
                            
                            <p class="font-bold underline">dr. {{ record.doctor?.name }}</p>
                            <p class="text-sm">(Nama dan tanda tangan)</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="text-center mt-8 text-slate-400 text-xs">
                &copy; {{ new Date().getFullYear() }} RS Unggul Karsa Medika. All rights reserved.
            </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
.skl-document {
    font-family: 'Times New Roman', Times, serif;
    font-size: 11pt;
    line-height: 1.3;
    color: black;
}
.skl-document table {
    border-collapse: collapse;
}

@media print {
    @page {
        margin: 20mm;
        size: 210mm 297mm; /* Standard A4 size */
    }
    
    body {
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
    }

    .print\:hidden {
        display: none !important;
    }

    /* Reset shadow and borders for print */
    .shadow-xl, .border, .rounded-lg {
        box-shadow: none !important;
        border: none !important;
        border-radius: 0 !important;
    }

    /* Ensure content fits perfectly */
    .skl-document {
        padding: 0 !important; /* Let @page handle margins */
        width: 100%;
        max-width: 170mm; /* 210mm - 40mm margins */
        margin: 0 auto;
    }
    
    /* Hide header/footer of browser */
    .no-print {
        display: none;
    }
}
</style>
