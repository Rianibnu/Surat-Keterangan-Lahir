<script setup lang="ts">
import { ref, watch } from 'vue';
import { AlertTriangle, Trash2, X, CheckCircle } from 'lucide-vue-next';

interface Props {
    modelValue: boolean;
    title?: string;
    message?: string;
    confirmText?: string;
    cancelText?: string;
    variant?: 'danger' | 'warning' | 'info';
    icon?: 'trash' | 'warning' | 'check';
}

const props = withDefaults(defineProps<Props>(), {
    title: 'Konfirmasi',
    message: 'Apakah Anda yakin ingin melanjutkan?',
    confirmText: 'Ya, Lanjutkan',
    cancelText: 'Batal',
    variant: 'danger',
    icon: 'warning',
});

const emit = defineEmits<{
    'update:modelValue': [value: boolean];
    'confirm': [];
    'cancel': [];
}>();

const isOpen = ref(props.modelValue);

watch(() => props.modelValue, (val) => {
    isOpen.value = val;
});

const close = () => {
    emit('update:modelValue', false);
};

const handleConfirm = () => {
    emit('confirm');
    close();
};

const handleCancel = () => {
    emit('cancel');
    close();
};

const getIconComponent = () => {
    switch (props.icon) {
        case 'trash': return Trash2;
        case 'check': return CheckCircle;
        default: return AlertTriangle;
    }
};

const getVariantStyles = () => {
    switch (props.variant) {
        case 'danger':
            return {
                iconBg: 'bg-gradient-to-br from-red-500 to-rose-600',
                iconRing: 'ring-red-500/20',
                button: 'bg-gradient-to-r from-red-500 to-rose-600 hover:from-red-600 hover:to-rose-700 focus:ring-red-500/50',
            };
        case 'warning':
            return {
                iconBg: 'bg-gradient-to-br from-amber-500 to-orange-500',
                iconRing: 'ring-amber-500/20',
                button: 'bg-gradient-to-r from-amber-500 to-orange-500 hover:from-amber-600 hover:to-orange-600 focus:ring-amber-500/50',
            };
        case 'info':
            return {
                iconBg: 'bg-gradient-to-br from-blue-500 to-indigo-600',
                iconRing: 'ring-blue-500/20',
                button: 'bg-gradient-to-r from-blue-500 to-indigo-600 hover:from-blue-600 hover:to-indigo-700 focus:ring-blue-500/50',
            };
    }
};
</script>

<template>
    <Teleport to="body">
        <Transition
            enter-active-class="duration-300 ease-out"
            enter-from-class="opacity-0"
            enter-to-class="opacity-100"
            leave-active-class="duration-200 ease-in"
            leave-from-class="opacity-100"
            leave-to-class="opacity-0"
        >
            <div
                v-if="modelValue"
                class="fixed inset-0 z-[9999] overflow-y-auto"
                @click.self="handleCancel"
            >
                <!-- Backdrop -->
                <div class="fixed inset-0 bg-black/60 backdrop-blur-sm" />
                
                <!-- Dialog Container -->
                <div class="flex min-h-full items-center justify-center p-4">
                    <Transition
                        enter-active-class="duration-300 ease-out"
                        enter-from-class="opacity-0 scale-95 translate-y-4"
                        enter-to-class="opacity-100 scale-100 translate-y-0"
                        leave-active-class="duration-200 ease-in"
                        leave-from-class="opacity-100 scale-100 translate-y-0"
                        leave-to-class="opacity-0 scale-95 translate-y-4"
                    >
                        <div
                            v-if="modelValue"
                            class="relative w-full max-w-md transform overflow-hidden rounded-2xl bg-white dark:bg-slate-900 shadow-2xl ring-1 ring-slate-200 dark:ring-slate-800"
                        >
                            <!-- Close button -->
                            <button
                                @click="handleCancel"
                                class="absolute top-4 right-4 p-1.5 rounded-lg text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors"
                            >
                                <X class="h-5 w-5" />
                            </button>
                            
                            <!-- Content -->
                            <div class="p-6 text-center">
                                <!-- Icon -->
                                <div class="mx-auto flex items-center justify-center mb-5">
                                    <div :class="[
                                        'p-4 rounded-2xl ring-8',
                                        getVariantStyles().iconBg,
                                        getVariantStyles().iconRing,
                                    ]">
                                        <component 
                                            :is="getIconComponent()" 
                                            class="h-8 w-8 text-white"
                                        />
                                    </div>
                                </div>
                                
                                <!-- Title -->
                                <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-2">
                                    {{ title }}
                                </h3>
                                
                                <!-- Message -->
                                <p class="text-slate-600 dark:text-slate-400 text-sm leading-relaxed mb-6">
                                    {{ message }}
                                </p>
                                
                                <!-- Actions -->
                                <div class="flex gap-3">
                                    <button
                                        @click="handleCancel"
                                        class="flex-1 px-4 py-2.5 text-sm font-medium rounded-xl border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-300 bg-white dark:bg-slate-800 hover:bg-slate-50 dark:hover:bg-slate-700 focus:outline-none focus:ring-2 focus:ring-slate-500/20 transition-all duration-200"
                                    >
                                        {{ cancelText }}
                                    </button>
                                    <button
                                        @click="handleConfirm"
                                        :class="[
                                            'flex-1 px-4 py-2.5 text-sm font-medium rounded-xl text-white shadow-lg focus:outline-none focus:ring-2 transition-all duration-200',
                                            getVariantStyles().button,
                                        ]"
                                    >
                                        {{ confirmText }}
                                    </button>
                                </div>
                            </div>
                            
                            <!-- Decorative gradient line -->
                            <div :class="[
                                'h-1',
                                getVariantStyles().iconBg,
                            ]" />
                        </div>
                    </Transition>
                </div>
            </div>
        </Transition>
    </Teleport>
</template>
