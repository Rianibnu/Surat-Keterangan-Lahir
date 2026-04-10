<script setup lang="ts">
import { Baby } from 'lucide-vue-next';
import { home } from '@/routes';
import { Link } from '@inertiajs/vue3';

defineProps<{
    title?: string;
    description?: string;
}>();
</script>

<template>
    <div class="relative min-h-svh flex items-center justify-center overflow-hidden bg-gradient-to-br from-slate-900 via-slate-900 to-indigo-950">
        <!-- Background Decorations -->
        <div class="absolute inset-0 pointer-events-none">
            <!-- Gradient Orbs -->
            <div class="absolute top-1/4 -left-20 w-[500px] h-[500px] bg-indigo-600/25 rounded-full blur-[120px] animate-pulse-slow"></div>
            <div class="absolute bottom-1/4 -right-20 w-[400px] h-[400px] bg-purple-600/20 rounded-full blur-[100px] animate-pulse-slow" style="animation-delay: 2s;"></div>
            <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[600px] bg-pink-600/10 rounded-full blur-[150px] animate-pulse-slow" style="animation-delay: 1s;"></div>
            
            <!-- Grid Pattern Overlay -->
            <div class="absolute inset-0 bg-[linear-gradient(rgba(255,255,255,0.02)_1px,transparent_1px),linear-gradient(90deg,rgba(255,255,255,0.02)_1px,transparent_1px)] bg-[size:60px_60px] [mask-image:radial-gradient(ellipse_80%_50%_at_50%_50%,#000_70%,transparent_110%)]"></div>
        </div>

        <!-- Content Container -->
        <div class="relative z-10 w-full max-w-md p-6">
            <!-- Glassmorphism Card -->
            <div class="bg-white/5 backdrop-blur-2xl rounded-3xl border border-white/10 shadow-2xl shadow-black/30 p-8 md:p-10 animate-fade-in">
                <!-- Logo & Header -->
                <div class="flex flex-col items-center gap-6 mb-8">
                    <Link
                        :href="home()"
                        class="flex flex-col items-center gap-4 group"
                    >
                        <!-- Logo -->
                        <div class="relative">
                            <div class="h-16 w-16 rounded-2xl bg-gradient-to-br from-indigo-500 via-purple-500 to-pink-500 flex items-center justify-center shadow-lg shadow-indigo-500/30 group-hover:shadow-indigo-500/50 group-hover:scale-105 transition-all duration-300">
                                <Baby class="h-8 w-8 text-white" />
                            </div>
                            <!-- Glow Effect -->
                            <div class="absolute -inset-2 bg-gradient-to-br from-indigo-500 via-purple-500 to-pink-500 rounded-3xl opacity-0 group-hover:opacity-30 blur-xl transition-opacity duration-500"></div>
                        </div>
                        
                        <!-- Brand Name -->
                        <div class="text-center">
                            <span class="text-2xl font-bold tracking-tight text-white">
                                SKL <span class="bg-gradient-to-r from-indigo-400 to-purple-400 bg-clip-text text-transparent">Desk</span>
                            </span>
                        </div>
                    </Link>
                    
                    <!-- Title & Description -->
                    <div class="space-y-2 text-center">
                        <h1 class="text-2xl font-bold text-white tracking-tight">{{ title }}</h1>
                        <p class="text-sm text-slate-400 max-w-xs mx-auto leading-relaxed">
                            {{ description }}
                        </p>
                    </div>
                </div>

                <!-- Form Slot -->
                <div>
                    <slot />
                </div>
            </div>

            <!-- Footer -->
            <div class="mt-8 text-center animate-fade-in" style="animation-delay: 200ms;">
                <p class="text-xs text-slate-500">
                    © 2025 Rumah Sakit Maranatha. All rights reserved.
                </p>
            </div>
        </div>
    </div>
</template>

<style scoped>
@keyframes fade-in {
    from { 
        opacity: 0; 
        transform: translateY(20px); 
    }
    to { 
        opacity: 1; 
        transform: translateY(0); 
    }
}

@keyframes pulse-slow {
    0%, 100% { opacity: 0.25; }
    50% { opacity: 0.15; }
}

.animate-fade-in {
    animation: fade-in 0.6s ease-out forwards;
}

.animate-pulse-slow {
    animation: pulse-slow 4s ease-in-out infinite;
}
</style>
