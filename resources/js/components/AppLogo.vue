<script setup lang="ts">
import { Baby } from 'lucide-vue-next';
</script>

<template>
    <div
        class="flex aspect-square size-8 items-center justify-center rounded-lg bg-gradient-to-br from-indigo-500 via-purple-500 to-pink-500 text-white shadow-sm"
    >
        <Baby class="size-5 text-white" />
    </div>
    <div class="ml-2 grid flex-1 text-left text-sm">
        <span class="mb-0.5 truncate leading-tight font-bold text-slate-700 dark:text-white"
            >VueDesk<span class="text-indigo-600 dark:text-indigo-400">Birth</span></span
        >
    </div>
</template>
