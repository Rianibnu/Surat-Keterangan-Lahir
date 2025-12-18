<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import TextLink from '@/components/TextLink.vue';
import { Spinner } from '@/components/ui/spinner';
import AuthLayout from '@/layouts/AuthLayout.vue';
import { login } from '@/routes';
import { email } from '@/routes/password';
import { Form, Head } from '@inertiajs/vue3';
import { Mail, ArrowLeft, Send } from 'lucide-vue-next';

defineProps<{
    status?: string;
}>();
</script>

<template>
    <AuthLayout
        title="Lupa Password"
        description="Masukkan email Anda untuk menerima link reset password"
    >
        <Head title="Lupa Password" />

        <!-- Success Status Message -->
        <div
            v-if="status"
            class="mb-6 p-4 rounded-xl bg-emerald-500/10 border border-emerald-500/20 backdrop-blur-sm animate-fade-in"
        >
            <p class="text-sm font-medium text-emerald-400 text-center flex items-center justify-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                {{ status }}
            </p>
        </div>

        <div class="space-y-6">
            <Form v-bind="email.form()" v-slot="{ errors, processing }">
                <!-- Email Field -->
                <div class="space-y-2">
                    <label for="email" class="flex items-center gap-2 text-sm font-medium text-slate-300">
                        <Mail class="w-4 h-4 text-indigo-400" />
                        Email Address
                    </label>
                    <div class="relative group">
                        <input
                            id="email"
                            type="email"
                            name="email"
                            autocomplete="off"
                            autofocus
                            placeholder="nama@example.com"
                            class="w-full h-12 px-4 bg-white/5 border border-white/10 rounded-xl text-white placeholder:text-slate-500 focus:border-indigo-500/50 focus:ring-2 focus:ring-indigo-500/20 focus:bg-white/10 transition-all duration-300"
                        />
                        <div class="absolute inset-0 rounded-xl bg-gradient-to-r from-indigo-500/0 via-purple-500/0 to-pink-500/0 group-focus-within:from-indigo-500/10 group-focus-within:via-purple-500/10 group-focus-within:to-pink-500/10 pointer-events-none transition-all duration-500"></div>
                    </div>
                    <InputError :message="errors.email" class="text-rose-400 text-xs" />
                </div>

                <!-- Submit Button -->
                <button
                    type="submit"
                    class="relative mt-6 w-full h-12 rounded-xl font-semibold text-white overflow-hidden group disabled:opacity-50 disabled:cursor-not-allowed"
                    :disabled="processing"
                    data-test="email-password-reset-link-button"
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
                        Kirim Link Reset
                    </span>
                </button>
            </Form>

            <!-- Back to Login -->
            <div class="text-center">
                <TextLink 
                    :href="login()"
                    class="inline-flex items-center gap-2 text-sm text-slate-400 hover:text-indigo-400 transition-colors"
                >
                    <ArrowLeft class="w-4 h-4" />
                    Kembali ke halaman login
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
