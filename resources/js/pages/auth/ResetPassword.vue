<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import { Spinner } from '@/components/ui/spinner';
import AuthLayout from '@/layouts/AuthLayout.vue';
import { update } from '@/routes/password';
import { Form, Head } from '@inertiajs/vue3';
import { ref } from 'vue';
import { Mail, Lock, ShieldCheck, KeyRound } from 'lucide-vue-next';

const props = defineProps<{
    token: string;
    email: string;
}>();

const inputEmail = ref(props.email);
</script>

<template>
    <AuthLayout
        title="Reset Password"
        description="Masukkan password baru Anda di bawah ini"
    >
        <Head title="Reset Password" />

        <Form
            v-bind="update.form()"
            :transform="(data) => ({ ...data, token, email })"
            :reset-on-success="['password', 'password_confirmation']"
            v-slot="{ errors, processing }"
            class="flex flex-col gap-5"
        >
            <!-- Email Field (Read Only) -->
            <div class="space-y-2">
                <label for="email" class="flex items-center gap-2 text-sm font-medium text-slate-300">
                    <Mail class="w-4 h-4 text-indigo-400" />
                    Email Address
                </label>
                <div class="relative">
                    <input
                        id="email"
                        type="email"
                        name="email"
                        autocomplete="email"
                        v-model="inputEmail"
                        readonly
                        class="w-full h-12 px-4 bg-white/5 border border-white/10 rounded-xl text-slate-400 cursor-not-allowed opacity-70"
                    />
                </div>
                <InputError :message="errors.email" class="text-rose-400 text-xs" />
            </div>

            <!-- Password Field -->
            <div class="space-y-2">
                <label for="password" class="flex items-center gap-2 text-sm font-medium text-slate-300">
                    <Lock class="w-4 h-4 text-indigo-400" />
                    Password Baru
                </label>
                <div class="relative group">
                    <input
                        id="password"
                        type="password"
                        name="password"
                        autocomplete="new-password"
                        autofocus
                        placeholder="Minimum 8 karakter"
                        class="w-full h-12 px-4 bg-white/5 border border-white/10 rounded-xl text-white placeholder:text-slate-500 focus:border-indigo-500/50 focus:ring-2 focus:ring-indigo-500/20 focus:bg-white/10 transition-all duration-300"
                    />
                    <div class="absolute inset-0 rounded-xl bg-gradient-to-r from-indigo-500/0 via-purple-500/0 to-pink-500/0 group-focus-within:from-indigo-500/10 group-focus-within:via-purple-500/10 group-focus-within:to-pink-500/10 pointer-events-none transition-all duration-500"></div>
                </div>
                <InputError :message="errors.password" class="text-rose-400 text-xs" />
            </div>

            <!-- Confirm Password Field -->
            <div class="space-y-2">
                <label for="password_confirmation" class="flex items-center gap-2 text-sm font-medium text-slate-300">
                    <ShieldCheck class="w-4 h-4 text-indigo-400" />
                    Konfirmasi Password
                </label>
                <div class="relative group">
                    <input
                        id="password_confirmation"
                        type="password"
                        name="password_confirmation"
                        autocomplete="new-password"
                        placeholder="Ketik ulang password baru"
                        class="w-full h-12 px-4 bg-white/5 border border-white/10 rounded-xl text-white placeholder:text-slate-500 focus:border-indigo-500/50 focus:ring-2 focus:ring-indigo-500/20 focus:bg-white/10 transition-all duration-300"
                    />
                    <div class="absolute inset-0 rounded-xl bg-gradient-to-r from-indigo-500/0 via-purple-500/0 to-pink-500/0 group-focus-within:from-indigo-500/10 group-focus-within:via-purple-500/10 group-focus-within:to-pink-500/10 pointer-events-none transition-all duration-500"></div>
                </div>
                <InputError :message="errors.password_confirmation" class="text-rose-400 text-xs" />
            </div>

            <!-- Submit Button -->
            <button
                type="submit"
                class="relative mt-4 w-full h-12 rounded-xl font-semibold text-white overflow-hidden group disabled:opacity-50 disabled:cursor-not-allowed"
                :disabled="processing"
                data-test="reset-password-button"
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
                    <KeyRound v-else class="w-5 h-5" />
                    Reset Password
                </span>
            </button>
        </Form>
    </AuthLayout>
</template>
