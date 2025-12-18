<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import TextLink from '@/components/TextLink.vue';
import { Checkbox } from '@/components/ui/checkbox';
import { Spinner } from '@/components/ui/spinner';
import AuthBase from '@/layouts/AuthLayout.vue';
import { register } from '@/routes';
import { store } from '@/routes/login';
import { request } from '@/routes/password';
import { Form, Head } from '@inertiajs/vue3';
import { LogIn, Mail, Lock, UserPlus } from 'lucide-vue-next';

defineProps<{
    status?: string;
    canResetPassword: boolean;
    canRegister: boolean;
}>();
</script>

<template>
    <AuthBase
        title="Selamat Datang Kembali"
        description="Masukkan email dan password untuk mengakses dashboard"
    >
        <Head title="Login" />

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

        <Form
            v-bind="store.form()"
            :reset-on-success="['password']"
            v-slot="{ errors, processing }"
            class="flex flex-col gap-5"
        >
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
                        required
                        autofocus
                        :tabindex="1"
                        autocomplete="email"
                        placeholder="nama@example.com"
                        class="w-full h-12 px-4 bg-white/5 border border-white/10 rounded-xl text-white placeholder:text-slate-500 focus:border-indigo-500/50 focus:ring-2 focus:ring-indigo-500/20 focus:bg-white/10 transition-all duration-300"
                    />
                    <div class="absolute inset-0 rounded-xl bg-gradient-to-r from-indigo-500/0 via-purple-500/0 to-pink-500/0 group-focus-within:from-indigo-500/10 group-focus-within:via-purple-500/10 group-focus-within:to-pink-500/10 pointer-events-none transition-all duration-500"></div>
                </div>
                <InputError :message="errors.email" class="text-rose-400 text-xs" />
            </div>

            <!-- Password Field -->
            <div class="space-y-2">
                <div class="flex items-center justify-between">
                    <label for="password" class="flex items-center gap-2 text-sm font-medium text-slate-300">
                        <Lock class="w-4 h-4 text-indigo-400" />
                        Password
                    </label>
                    <TextLink
                        v-if="canResetPassword"
                        :href="request()"
                        class="text-xs font-medium text-indigo-400 hover:text-indigo-300 transition-colors"
                        :tabindex="5"
                    >
                        Lupa password?
                    </TextLink>
                </div>
                <div class="relative group">
                    <input
                        id="password"
                        type="password"
                        name="password"
                        required
                        :tabindex="2"
                        autocomplete="current-password"
                        placeholder="••••••••"
                        class="w-full h-12 px-4 bg-white/5 border border-white/10 rounded-xl text-white placeholder:text-slate-500 focus:border-indigo-500/50 focus:ring-2 focus:ring-indigo-500/20 focus:bg-white/10 transition-all duration-300"
                    />
                    <div class="absolute inset-0 rounded-xl bg-gradient-to-r from-indigo-500/0 via-purple-500/0 to-pink-500/0 group-focus-within:from-indigo-500/10 group-focus-within:via-purple-500/10 group-focus-within:to-pink-500/10 pointer-events-none transition-all duration-500"></div>
                </div>
                <InputError :message="errors.password" class="text-rose-400 text-xs" />
            </div>

            <!-- Remember Me -->
            <div class="flex items-center justify-between">
                <label for="remember" class="flex items-center gap-3 cursor-pointer group">
                    <div class="relative">
                        <Checkbox 
                            id="remember" 
                            name="remember" 
                            :tabindex="3" 
                            class="h-5 w-5 rounded-md border-white/20 bg-white/5 text-indigo-500 focus:ring-indigo-500/30 focus:ring-offset-0 transition-all"
                        />
                    </div>
                    <span class="text-sm text-slate-400 group-hover:text-slate-300 transition-colors">Ingat saya</span>
                </label>
            </div>

            <!-- Submit Button -->
            <button
                type="submit"
                class="relative mt-4 w-full h-12 rounded-xl font-semibold text-white overflow-hidden group disabled:opacity-50 disabled:cursor-not-allowed"
                :tabindex="4"
                :disabled="processing"
                data-test="login-button"
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
                    <LogIn v-else class="w-5 h-5" />
                    Masuk
                </span>
            </button>

            <!-- Divider -->
            <div class="relative my-2">
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full border-t border-white/10"></div>
                </div>
                <div class="relative flex justify-center text-xs">
                    <span class="bg-transparent px-4 text-slate-500">atau</span>
                </div>
            </div>

            <!-- Register Link -->
            <div
                class="text-center"
                v-if="canRegister"
            >
                <p class="text-sm text-slate-400">
                    Belum punya akun?
                    <TextLink 
                        :href="register()" 
                        :tabindex="5"
                        class="font-semibold text-indigo-400 hover:text-indigo-300 inline-flex items-center gap-1 transition-colors"
                    >
                        <UserPlus class="w-4 h-4" />
                        Daftar sekarang
                    </TextLink>
                </p>
            </div>
        </Form>
    </AuthBase>
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
