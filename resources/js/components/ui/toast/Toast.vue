<script setup lang="ts">
import { ref, watch, onMounted } from 'vue';
import { usePage } from '@inertiajs/vue3';
import { CheckCircle, XCircle, AlertTriangle, Info, X } from 'lucide-vue-next';

interface Toast {
    id: number;
    type: 'success' | 'error' | 'warning' | 'info';
    title: string;
    message?: string;
    duration: number;
}

const toasts = ref<Toast[]>([]);
let toastId = 0;

// Watch for flash messages from Inertia
const page = usePage();

watch(() => (page.props as any).flash, (flash) => {
    if (flash?.success) {
        addToast('success', 'Berhasil!', flash.success);
    }
    if (flash?.error) {
        addToast('error', 'Gagal!', flash.error);
    }
    if (flash?.warning) {
        addToast('warning', 'Perhatian!', flash.warning);
    }
    if (flash?.info) {
        addToast('info', 'Informasi', flash.info);
    }
}, { immediate: true, deep: true });

const addToast = (type: Toast['type'], title: string, message?: string, duration = 5000) => {
    const id = ++toastId;
    toasts.value.push({ id, type, title, message, duration });

    if (duration > 0) {
        setTimeout(() => removeToast(id), duration);
    }
};

const removeToast = (id: number) => {
    const index = toasts.value.findIndex(t => t.id === id);
    if (index > -1) {
        toasts.value.splice(index, 1);
    }
};

const getIcon = (type: Toast['type']) => {
    switch (type) {
        case 'success': return CheckCircle;
        case 'error': return XCircle;
        case 'warning': return AlertTriangle;
        case 'info': return Info;
    }
};

const getStyles = (type: Toast['type']) => {
    switch (type) {
        case 'success':
            return {
                bg: 'bg-gradient-to-r from-emerald-500 to-green-600',
                icon: 'text-white',
                ring: 'ring-emerald-500/20',
            };
        case 'error':
            return {
                bg: 'bg-gradient-to-r from-red-500 to-rose-600',
                icon: 'text-white',
                ring: 'ring-red-500/20',
            };
        case 'warning':
            return {
                bg: 'bg-gradient-to-r from-amber-500 to-orange-500',
                icon: 'text-white',
                ring: 'ring-amber-500/20',
            };
        case 'info':
            return {
                bg: 'bg-gradient-to-r from-blue-500 to-indigo-600',
                icon: 'text-white',
                ring: 'ring-blue-500/20',
            };
    }
};

// Expose addToast for external usage
defineExpose({ addToast });
</script>

<template>
    <Teleport to="body">
        <div class="fixed top-4 right-4 z-[9999] flex flex-col gap-3 pointer-events-none max-w-md w-full">
            <TransitionGroup
                enter-active-class="transform transition-all duration-500 ease-out"
                enter-from-class="translate-x-full opacity-0 scale-95"
                enter-to-class="translate-x-0 opacity-100 scale-100"
                leave-active-class="transform transition-all duration-300 ease-in"
                leave-from-class="translate-x-0 opacity-100 scale-100"
                leave-to-class="translate-x-full opacity-0 scale-95"
                move-class="transition-all duration-300"
            >
                <div
                    v-for="toast in toasts"
                    :key="toast.id"
                    :class="[
                        'pointer-events-auto relative overflow-hidden rounded-xl shadow-2xl',
                        'ring-1 backdrop-blur-sm',
                        getStyles(toast.type).ring,
                    ]"
                >
                    <!-- Background with gradient -->
                    <div :class="[
                        'absolute inset-0 opacity-95',
                        getStyles(toast.type).bg,
                    ]" />
                    
                    <!-- Glass overlay -->
                    <div class="absolute inset-0 bg-white/10 backdrop-blur-[2px]" />
                    
                    <!-- Content -->
                    <div class="relative flex items-start gap-3 p-4">
                        <!-- Animated Icon -->
                        <div class="flex-shrink-0">
                            <div class="relative">
                                <div class="absolute inset-0 animate-ping opacity-20">
                                    <component 
                                        :is="getIcon(toast.type)" 
                                        class="h-6 w-6 text-white"
                                    />
                                </div>
                                <component 
                                    :is="getIcon(toast.type)" 
                                    :class="['h-6 w-6', getStyles(toast.type).icon]"
                                />
                            </div>
                        </div>
                        
                        <!-- Text Content -->
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold text-white">
                                {{ toast.title }}
                            </p>
                            <p v-if="toast.message" class="mt-1 text-sm text-white/90 line-clamp-2">
                                {{ toast.message }}
                            </p>
                        </div>
                        
                        <!-- Close Button -->
                        <button
                            @click="removeToast(toast.id)"
                            class="flex-shrink-0 p-1 rounded-lg text-white/70 hover:text-white hover:bg-white/20 transition-all duration-200"
                        >
                            <X class="h-4 w-4" />
                        </button>
                    </div>
                    
                    <!-- Progress Bar -->
                    <div class="relative h-1 bg-black/10">
                        <div 
                            class="absolute inset-y-0 left-0 bg-white/40 animate-progress"
                            :style="{ animationDuration: `${toast.duration}ms` }"
                        />
                    </div>
                </div>
            </TransitionGroup>
        </div>
    </Teleport>
</template>

<style scoped>
@keyframes progress {
    from { width: 100%; }
    to { width: 0%; }
}

.animate-progress {
    animation: progress linear forwards;
}
</style>
