<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import { Spinner } from '@/components/ui/spinner';
import {
    InputOTP,
    InputOTPGroup,
    InputOTPSlot,
} from '@/components/ui/input-otp';
import AuthLayout from '@/layouts/AuthLayout.vue';
import { store } from '@/routes/two-factor/login';
import { Form, Head } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import { ShieldCheck, Key, ArrowRight, RefreshCw } from 'lucide-vue-next';

interface AuthConfigContent {
    title: string;
    description: string;
    toggleText: string;
}

const authConfigContent = computed<AuthConfigContent>(() => {
    if (showRecoveryInput.value) {
        return {
            title: 'Kode Pemulihan',
            description:
                'Masukkan salah satu kode pemulihan darurat Anda untuk mengakses akun.',
            toggleText: 'gunakan kode autentikasi',
        };
    }

    return {
        title: 'Verifikasi 2 Langkah',
        description:
            'Masukkan kode autentikasi dari aplikasi authenticator Anda.',
        toggleText: 'gunakan kode pemulihan',
    };
});

const showRecoveryInput = ref<boolean>(false);

const toggleRecoveryMode = (clearErrors: () => void): void => {
    showRecoveryInput.value = !showRecoveryInput.value;
    clearErrors();
    code.value = '';
};

const code = ref<string>('');
</script>

<template>
    <AuthLayout
        :title="authConfigContent.title"
        :description="authConfigContent.description"
    >
        <Head title="Verifikasi 2 Langkah" />

        <!-- Security Icon -->
        <div class="flex justify-center mb-6">
            <div class="relative">
                <div class="h-20 w-20 rounded-2xl bg-gradient-to-br from-indigo-500/20 to-purple-500/20 backdrop-blur-xl border border-white/10 flex items-center justify-center">
                    <ShieldCheck v-if="!showRecoveryInput" class="h-10 w-10 text-indigo-400" />
                    <Key v-else class="h-10 w-10 text-amber-400" />
                </div>
                <!-- Animated ping for attention -->
                <div class="absolute -top-1 -right-1">
                    <span class="relative flex h-4 w-4">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-4 w-4 bg-emerald-500 border-2 border-slate-900"></span>
                    </span>
                </div>
            </div>
        </div>

        <div class="space-y-6">
            <!-- OTP Code Input -->
            <template v-if="!showRecoveryInput">
                <Form
                    v-bind="store.form()"
                    class="space-y-6"
                    reset-on-error
                    @error="code = ''"
                    #default="{ errors, processing, clearErrors }"
                >
                    <input type="hidden" name="code" :value="code" />
                    
                    <div class="flex flex-col items-center justify-center space-y-4 text-center">
                        <div class="flex w-full items-center justify-center">
                            <InputOTP
                                id="otp"
                                v-model="code"
                                :maxlength="6"
                                :disabled="processing"
                                autofocus
                                class="gap-2"
                            >
                                <InputOTPGroup class="gap-2">
                                    <InputOTPSlot
                                        v-for="index in 6"
                                        :key="index"
                                        :index="index - 1"
                                        class="h-12 w-12 rounded-xl border-white/20 bg-white/5 text-white text-lg font-bold focus:border-indigo-500 focus:ring-indigo-500/20"
                                    />
                                </InputOTPGroup>
                            </InputOTP>
                        </div>
                        <InputError :message="errors.code" class="text-rose-400 text-xs" />
                    </div>

                    <!-- Submit Button -->
                    <button
                        type="submit"
                        class="relative w-full h-12 rounded-xl font-semibold text-white overflow-hidden group disabled:opacity-50 disabled:cursor-not-allowed"
                        :disabled="processing"
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
                            <ArrowRight v-else class="w-5 h-5" />
                            Lanjutkan
                        </span>
                    </button>

                    <!-- Toggle Recovery Mode -->
                    <div class="text-center text-sm text-slate-400">
                        <span>atau Anda bisa </span>
                        <button
                            type="button"
                            class="text-indigo-400 hover:text-indigo-300 underline underline-offset-4 transition-colors duration-300"
                            @click="() => toggleRecoveryMode(clearErrors)"
                        >
                            {{ authConfigContent.toggleText }}
                        </button>
                    </div>
                </Form>
            </template>

            <!-- Recovery Code Input -->
            <template v-else>
                <Form
                    v-bind="store.form()"
                    class="space-y-6"
                    reset-on-error
                    #default="{ errors, processing, clearErrors }"
                >
                    <!-- Recovery Code Field -->
                    <div class="space-y-2">
                        <label for="recovery_code" class="flex items-center gap-2 text-sm font-medium text-slate-300">
                            <Key class="w-4 h-4 text-amber-400" />
                            Kode Pemulihan
                        </label>
                        <div class="relative group">
                            <input
                                id="recovery_code"
                                name="recovery_code"
                                type="text"
                                placeholder="Masukkan kode pemulihan"
                                :autofocus="showRecoveryInput"
                                required
                                class="w-full h-12 px-4 bg-white/5 border border-white/10 rounded-xl text-white placeholder:text-slate-500 focus:border-amber-500/50 focus:ring-2 focus:ring-amber-500/20 focus:bg-white/10 transition-all duration-300 font-mono"
                            />
                            <div class="absolute inset-0 rounded-xl bg-gradient-to-r from-amber-500/0 via-orange-500/0 to-amber-500/0 group-focus-within:from-amber-500/10 group-focus-within:via-orange-500/10 group-focus-within:to-amber-500/10 pointer-events-none transition-all duration-500"></div>
                        </div>
                        <InputError :message="errors.recovery_code" class="text-rose-400 text-xs" />
                    </div>

                    <!-- Submit Button -->
                    <button
                        type="submit"
                        class="relative w-full h-12 rounded-xl font-semibold text-white overflow-hidden group disabled:opacity-50 disabled:cursor-not-allowed"
                        :disabled="processing"
                    >
                        <!-- Gradient Background -->
                        <div class="absolute inset-0 bg-gradient-to-r from-amber-500 via-orange-500 to-amber-600 transition-all duration-500 group-hover:scale-105"></div>
                        
                        <!-- Animated Shine Effect -->
                        <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/20 to-transparent -translate-x-full group-hover:translate-x-full transition-transform duration-1000"></div>
                        
                        <!-- Shadow -->
                        <div class="absolute inset-0 shadow-lg shadow-amber-500/30 group-hover:shadow-amber-500/50 rounded-xl transition-all duration-300"></div>
                        
                        <!-- Content -->
                        <span class="relative z-10 flex items-center justify-center gap-2">
                            <Spinner v-if="processing" class="w-5 h-5" />
                            <ArrowRight v-else class="w-5 h-5" />
                            Pulihkan Akun
                        </span>
                    </button>

                    <!-- Toggle Back to OTP -->
                    <div class="text-center text-sm text-slate-400">
                        <span>atau Anda bisa </span>
                        <button
                            type="button"
                            class="text-indigo-400 hover:text-indigo-300 underline underline-offset-4 transition-colors duration-300 inline-flex items-center gap-1"
                            @click="() => toggleRecoveryMode(clearErrors)"
                        >
                            <RefreshCw class="w-3 h-3" />
                            {{ authConfigContent.toggleText }}
                        </button>
                    </div>
                </Form>
            </template>
        </div>
    </AuthLayout>
</template>
