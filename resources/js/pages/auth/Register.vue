<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import TextLink from '@/components/TextLink.vue';
import { Spinner } from '@/components/ui/spinner';
import AuthBase from '@/layouts/AuthLayout.vue';
import { login } from '@/routes';
import { store } from '@/routes/register';
import { Form, Head } from '@inertiajs/vue3';
import { UserPlus, User, Mail, Lock, ShieldCheck, LogIn } from 'lucide-vue-next';
</script>

<template>
    <AuthBase
        title="Buat Akun Baru"
        description="Lengkapi data diri Anda untuk membuat akun staff"
    >
        <Head title="Register" />

        <Form
            v-bind="store.form()"
            :reset-on-success="['password', 'password_confirmation']"
            v-slot="{ errors, processing }"
            class="flex flex-col gap-5"
        >
            <!-- Name Field -->
            <div class="space-y-2">
                <label for="name" class="flex items-center gap-2 text-sm font-medium text-slate-300">
                    <User class="w-4 h-4 text-indigo-400" />
                    Nama Lengkap
                </label>
                <div class="relative group">
                    <input
                        id="name"
                        type="text"
                        required
                        autofocus
                        :tabindex="1"
                        autocomplete="name"
                        name="name"
                        placeholder="Masukkan nama lengkap"
                        class="w-full h-12 px-4 bg-white/5 border border-white/10 rounded-xl text-white placeholder:text-slate-500 focus:border-indigo-500/50 focus:ring-2 focus:ring-indigo-500/20 focus:bg-white/10 transition-all duration-300"
                    />
                    <div class="absolute inset-0 rounded-xl bg-gradient-to-r from-indigo-500/0 via-purple-500/0 to-pink-500/0 group-focus-within:from-indigo-500/10 group-focus-within:via-purple-500/10 group-focus-within:to-pink-500/10 pointer-events-none transition-all duration-500"></div>
                </div>
                <InputError :message="errors.name" class="text-rose-400 text-xs" />
            </div>

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
                        required
                        :tabindex="2"
                        autocomplete="email"
                        name="email"
                        placeholder="nama@example.com"
                        class="w-full h-12 px-4 bg-white/5 border border-white/10 rounded-xl text-white placeholder:text-slate-500 focus:border-indigo-500/50 focus:ring-2 focus:ring-indigo-500/20 focus:bg-white/10 transition-all duration-300"
                    />
                    <div class="absolute inset-0 rounded-xl bg-gradient-to-r from-indigo-500/0 via-purple-500/0 to-pink-500/0 group-focus-within:from-indigo-500/10 group-focus-within:via-purple-500/10 group-focus-within:to-pink-500/10 pointer-events-none transition-all duration-500"></div>
                </div>
                <InputError :message="errors.email" class="text-rose-400 text-xs" />
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
                        required
                        :tabindex="3"
                        autocomplete="new-password"
                        name="password"
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
                        required
                        :tabindex="4"
                        autocomplete="new-password"
                        name="password_confirmation"
                        placeholder="Ketik ulang password"
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
                tabindex="5"
                :disabled="processing"
                data-test="register-user-button"
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
                    <UserPlus v-else class="w-5 h-5" />
                    Buat Akun
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

            <!-- Login Link -->
            <div class="text-center">
                <p class="text-sm text-slate-400">
                    Sudah punya akun?
                    <TextLink
                        :href="login()"
                        class="font-semibold text-indigo-400 hover:text-indigo-300 inline-flex items-center gap-1 transition-colors"
                        :tabindex="6"
                    >
                        <LogIn class="w-4 h-4" />
                        Masuk di sini
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
