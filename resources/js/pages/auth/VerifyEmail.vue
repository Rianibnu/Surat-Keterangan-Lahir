<script setup lang="ts">
import TextLink from '@/components/TextLink.vue';
import { Spinner } from '@/components/ui/spinner';
import AuthLayout from '@/layouts/AuthLayout.vue';
import { logout } from '@/routes';
import { Head, router } from '@inertiajs/vue3';
import { ref } from 'vue';
import { Mail, Send, LogOut, CheckCircle2 } from 'lucide-vue-next';

const props = defineProps<{
    status?: string;
}>();

const processing = ref(false);

const resendVerification = () => {
    processing.value = true;

    router.post('/email/verification-notification', {}, {
        onFinish: () => {
            processing.value = false;
        },
    });
};
</script>

<template>
    <AuthLayout
        title="Verifikasi Email"
        description="Silakan verifikasi alamat email Anda dengan mengklik link yang telah kami kirimkan."
    >
        <Head title="Verifikasi Email" />

        <!-- Success Status Message -->
        <div
            v-if="status === 'verification-link-sent'"
            class="mb-6 p-4 rounded-xl bg-emerald-500/10 border border-emerald-500/20 backdrop-blur-sm animate-fade-in"
        >
            <p class="text-sm font-medium text-emerald-400 text-center flex items-center justify-center gap-2">
                <CheckCircle2 class="w-4 h-4" />
                Link verifikasi baru telah dikirim ke email Anda.
            </p>
        </div>

        <!-- Email Illustration -->
        <div class="flex justify-center mb-6">
            <div class="relative">
                <div class="h-20 w-20 rounded-2xl bg-gradient-to-br from-indigo-500/20 to-purple-500/20 backdrop-blur-xl border border-white/10 flex items-center justify-center">
                    <Mail class="h-10 w-10 text-indigo-400" />
                </div>
                <!-- Animated ping for attention -->
                <div class="absolute -top-1 -right-1">
                    <span class="relative flex h-4 w-4">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-indigo-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-4 w-4 bg-indigo-500 border-2 border-slate-900"></span>
                    </span>
                </div>
            </div>
        </div>

        <!-- Info Text -->
        <div class="text-center mb-6">
            <p class="text-sm text-slate-400 leading-relaxed">
                Kami telah mengirimkan email verifikasi ke alamat email yang Anda daftarkan. 
                Silakan cek inbox atau folder spam Anda.
            </p>
        </div>

        <div class="space-y-4">
            <!-- Resend Button -->
            <button 
                @click="resendVerification" 
                :disabled="processing"
                type="button"
                class="relative w-full h-12 rounded-xl font-semibold text-white overflow-hidden group disabled:opacity-50 disabled:cursor-not-allowed"
            >
                <!-- Gradient Background -->
                <div class="absolute inset-0 bg-gradient-to-r from-indigo-500 via-purple-500 to-indigo-600 transition-all duration-500 group-hover:scale-105"></div>
                
                <!-- Animated Shine Effect -->
                <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/20 to-transparent -translate-x-full group-hover:translate-x-full transition-transform duration-1000"></div>
                
                <!-- Shadow -->
                <div class="absolute inset-0 shadow-lg shadow-indigo-500/30 group-hover:shadow-indigo-500/50 rounded-xl transition-all duration-300"></div>
                
                <!-- Content -->
                <span class="relative z-10 flex items-center justify-center gap-2">
                    <Spinner v-if="processing" class="w-5 h-5" />
                    <Send v-else class="w-5 h-5" />
                    Kirim Ulang Email Verifikasi
                </span>
            </button>

            <!-- Logout Link -->
            <div class="text-center">
                <TextLink
                    :href="logout()"
                    as="button"
                    class="inline-flex items-center gap-2 text-sm text-slate-400 hover:text-rose-400 transition-colors"
                >
                    <LogOut class="w-4 h-4" />
                    Keluar
                </TextLink>
            </div>
        </div>
    </AuthLayout>
</template>

<style scoped>
@keyframes fade-in {
    from { 
        opacity: 0; 
        transform: translateY(10px); 
    }
    to { 
        opacity: 1; 
        transform: translateY(0); 
    }
}

.animate-fade-in {
    animation: fade-in 0.4s ease-out forwards;
}
</style>
