<script setup>
import { ref, onMounted, onBeforeUnmount } from 'vue';

const props = defineProps({
    modelValue: {
        type: [String, Object], // Accept String or Null (Object in JS runtime usually covers null/object)
        required: false,
        default: null
    }
});

const emit = defineEmits(['update:modelValue']);

const canvas = ref(null);
const isDrawing = ref(false);
const ctx = ref(null);

// Initialize Canvas
onMounted(() => {
    if (canvas.value) {
        ctx.value = canvas.value.getContext('2d');
        resizeCanvas();
        window.addEventListener('resize', resizeCanvas);
    }
});

onBeforeUnmount(() => {
    window.removeEventListener('resize', resizeCanvas);
});

// Resize logic to match container width
const resizeCanvas = () => {
    if (!canvas.value) return;
    
    // Store content before resize
    // const data = canvas.value.toDataURL();
    
    // Set actual dimension based on display styling
    const rect = canvas.value.getBoundingClientRect();
    canvas.value.width = rect.width;
    canvas.value.height = 200; // Fixed height for consistency
    
    // Setup context style
    ctx.value.lineWidth = 2;
    ctx.value.lineCap = 'round';
    ctx.value.lineJoin = 'round';
    ctx.value.strokeStyle = '#000000';
    
    // Restore content? Might be tricky if aspect changed. For now, clear on resize or just let it be.
    // If we want to keep signature on resize, we need to redraw it.
    // Simplifying: User draws after layout is stable.
};

const getPos = (e) => {
    const rect = canvas.value.getBoundingClientRect();
    const clientX = e.touches ? e.touches[0].clientX : e.clientX;
    const clientY = e.touches ? e.touches[0].clientY : e.clientY;
    
    return {
        x: clientX - rect.left,
        y: clientY - rect.top
    };
};

const startDrawing = (e) => {
    isDrawing.value = true;
    const { x, y } = getPos(e);
    ctx.value.beginPath();
    ctx.value.moveTo(x, y);
};

const draw = (e) => {
    if (!isDrawing.value) return;
    const { x, y } = getPos(e);
    ctx.value.lineTo(x, y);
    ctx.value.stroke();
};

const stopDrawing = () => {
    if (isDrawing.value) {
        emit('update:modelValue', canvas.value.toDataURL('image/png'));
        isDrawing.value = false;
    }
};

const clear = () => {
    if (ctx.value && canvas.value) {
        ctx.value.clearRect(0, 0, canvas.value.width, canvas.value.height);
        emit('update:modelValue', null);
    }
};
</script>

<template>
    <div class="w-full">
        <label class="block text-sm font-medium text-slate-700 mb-2">Tanda Tangan Digital</label>
        <div class="relative w-full border border-slate-300 rounded-xl overflow-hidden shadow-sm bg-slate-50">
             <canvas 
                ref="canvas" 
                class="block w-full touch-none cursor-crosshair bg-white"
                style="height: 200px;"
                @mousedown="startDrawing"
                @mousemove="draw"
                @mouseup="stopDrawing"
                @mouseleave="stopDrawing"
                @touchstart.prevent="startDrawing"
                @touchmove.prevent="draw"
                @touchend.prevent="stopDrawing"
            ></canvas>
             <div class="absolute top-2 right-2 opacity-50 pointer-events-none">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-slate-400"><path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/></svg> 
             </div>
        </div>
        <div class="mt-2 flex justify-between items-center text-xs text-slate-500">
            <span>Gunakan Pen Tablet atau Mouse untuk tanda tangan.</span>
            <button type="button" @click="clear" class="text-red-600 hover:text-red-700 hover:underline flex items-center gap-1">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/></svg>
                Bersihkan
            </button>
        </div>
    </div>
</template>
