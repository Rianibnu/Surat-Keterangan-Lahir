<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import { Spinner } from '@/components/ui/spinner';
import AuthLayout from '@/layouts/AuthLayout.vue';
import { store } from '@/routes/password/confirm';
import { Form, Head } from '@inertiajs/vue3';
import { Lock, ShieldCheck } from 'lucide-vue-next';
</script>

<template>
    <AuthLayout
        title="Konfirmasi Password"
        description="Ini adalah area aman. Silakan konfirmasi password Anda untuk melanjutkan."
    >
        <Head title="Konfirmasi Password" />

        <Form
            v-bind="store.form()"
            reset-on-success
            v-slot="{ errors, processing }"
            class="flex flex-col gap-5"
        >
            <!-- Security Notice -->
            <div class="p-4 rounded-xl bg-amber-500/10 border border-amber-500/20 backdrop-blur-sm">
                <p class="text-sm text-amber-400 text-center flex items-center justify-center gap-2">
                    <ShieldCheck class="w-4 h-4" />
                    Area ini memerlukan verifikasi ulang
                </p>
            </div>

            <!-- Password Field -->
            <div class="space-y-2">
                <label for="password" class="flex items-center gap-2 text-sm font-medium text-slate-300">
                    <Lock class="w-4 h-4 text-indigo-400" />
                    Password
                </label>
                <div class="relative group">
                    <input
                        id="password"
                        type="password"
                        name="password"
                        required
                        autocomplete="current-password"
                        autofocus
                        placeholder="Masukkan password Anda"
                        class="w-full h-12 px-4 bg-white/5 border border-white/10 rounded-xl text-white placeholder:text-slate-500 focus:border-indigo-500/50 focus:ring-2 focus:ring-indigo-500/20 focus:bg-white/10 transition-all duration-300"
                    />
                    <div class="absolute inset-0 rounded-xl bg-gradient-to-r from-indigo-500/0 via-purple-500/0 to-pink-500/0 group-focus-within:from-indigo-500/10 group-focus-within:via-purple-500/10 group-focus-within:to-pink-500/10 pointer-events-none transition-all duration-500"></div>
                </div>
                <InputError :message="errors.password" class="text-rose-400 text-xs" />
            </div>

            <!-- Submit Button -->
            <button
                type="submit"
                class="relative mt-4 w-full h-12 rounded-xl font-semibold text-white overflow-hidden group disabled:opacity-50 disabled:cursor-not-allowed"
                :disabled="processing"
                data-test="confirm-password-button"
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
                    <ShieldCheck v-else class="w-5 h-5" />
                    Konfirmasi
                </span>
            </button>
        </Form>
    </AuthLayout>
</template>
