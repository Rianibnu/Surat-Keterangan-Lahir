<script setup lang="ts">
import { ref, onMounted } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { 
    ShieldCheck, 
    Clock, 
    FileCheck, 
    Baby, 
    Stethoscope, 
    ArrowRight,
    QrCode,
    CheckCircle2,
    Sparkles,
    Facebook,
    Twitter,
    Instagram,
    Linkedin,
    MapPin,
    Phone,
    Mail,
    Search,
    ChevronDown,
    FileText,
    Users,
    Activity,
    Heart
} from 'lucide-vue-next';

defineProps<{
    canLogin?: boolean;
    canRegister?: boolean;
    laravelVersion?: string;
    phpVersion?: string;
}>();

const searchQuery = ref('');
const isNavScrolled = ref(false);

// Handle navbar scroll effect
onMounted(() => {
    const handleScroll = () => {
        isNavScrolled.value = window.scrollY > 50;
    };
    window.addEventListener('scroll', handleScroll);
});

const handleSearch = () => {
    let query = searchQuery.value.trim();
    if (query) {
        // Extract UUID from full URL if pasted
        if (query.includes('/verify-skl/')) {
            const parts = query.split('/verify-skl/');
            if (parts[1]) {
                query = parts[1].split('?')[0];
            }
        }

        // Validate UUID format (8-4-4-4-12 hex chars)
        const uuidRegex = /^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/i;
        if (!uuidRegex.test(query)) {
            alert('Format UUID tidak valid. Masukkan UUID yang benar atau paste link verifikasi SKL.');
            return;
        }

        router.visit(`/verify-skl/${query}`);
    }
};

const scrollToSection = (sectionId: string) => {
    document.getElementById(sectionId)?.scrollIntoView({ behavior: 'smooth' });
};
</script>

<template>
    <Head title="SKL Desk - Sistem Kelahiran Digital RS Unggul Karsa Medika" />

    <div class="min-h-screen bg-white dark:bg-slate-950 text-slate-900 dark:text-white antialiased">
        <!-- Navbar: Clean & Minimal -->
        <nav 
            :class="[
                'fixed top-0 left-0 right-0 z-50 transition-all duration-500 ease-out',
                isNavScrolled 
                    ? 'py-3 bg-white/90 dark:bg-slate-900/90 backdrop-blur-xl shadow-lg shadow-slate-900/5 dark:shadow-slate-950/50' 
                    : 'py-5 bg-transparent'
            ]"
        >
            <div class="container mx-auto px-4 md:px-8">
                <div class="flex items-center justify-between">
                    <!-- Logo -->
                    <Link href="/" class="flex items-center gap-3 group">
                        <div class="relative">
                            <div class="h-11 w-11 rounded-2xl bg-gradient-to-br from-indigo-500 via-purple-500 to-pink-500 flex items-center justify-center shadow-lg shadow-indigo-500/25 group-hover:shadow-indigo-500/40 group-hover:scale-105 transition-all duration-300">
                                <Baby class="h-6 w-6 text-white" />
                            </div>
                            <div class="absolute -inset-1 bg-gradient-to-br from-indigo-500 via-purple-500 to-pink-500 rounded-2xl opacity-0 group-hover:opacity-30 blur-lg transition-opacity duration-300"></div>
                        </div>
                        <div class="hidden sm:block">
                            <span class="text-xl font-bold tracking-tight" :class="isNavScrolled ? 'text-slate-900 dark:text-white' : 'text-white'">
                                SKL <span class="bg-gradient-to-r from-indigo-500 to-purple-500 bg-clip-text text-transparent">Desk</span>
                            </span>
                        </div>
                    </Link>
                    
                    <!-- Center Navigation -->
                    <div class="hidden md:flex items-center gap-1 px-2 py-2 rounded-full bg-white/10 dark:bg-white/5 backdrop-blur-lg border border-white/10">
                        <a 
                            href="#home" 
                            @click.prevent="scrollToSection('home')"
                            class="px-5 py-2 text-sm font-medium rounded-full transition-all duration-300"
                            :class="isNavScrolled ? 'text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-white/10' : 'text-white/80 hover:text-white hover:bg-white/10'"
                        >
                            Beranda
                        </a>
                        <a 
                            href="#features" 
                            @click.prevent="scrollToSection('features')"
                            class="px-5 py-2 text-sm font-medium rounded-full transition-all duration-300"
                            :class="isNavScrolled ? 'text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-white/10' : 'text-white/80 hover:text-white hover:bg-white/10'"
                        >
                            Fitur
                        </a>
                        <a 
                            href="#process" 
                            @click.prevent="scrollToSection('process')"
                            class="px-5 py-2 text-sm font-medium rounded-full transition-all duration-300"
                            :class="isNavScrolled ? 'text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-white/10' : 'text-white/80 hover:text-white hover:bg-white/10'"
                        >
                            Alur
                        </a>
                    </div>

                    <!-- Right Actions -->
                    <div class="flex items-center gap-3">
                        <template v-if="$page.props.auth?.user">
                            <Link href="/dashboard">
                                <button class="relative group rounded-full px-6 py-2.5 font-semibold text-sm transition-all duration-300 hover:scale-105 bg-gradient-to-r from-indigo-500 to-purple-600 text-white shadow-lg shadow-indigo-500/30 hover:shadow-indigo-500/50">
                                    <span class="relative z-10">Dashboard</span>
                                </button>
                            </Link>
                        </template>
                        <template v-else>
                            <Link href="/login">
                                <button 
                                    class="relative group rounded-full px-6 py-2.5 font-medium text-sm transition-all duration-300 hover:scale-105 overflow-hidden"
                                    :class="isNavScrolled 
                                        ? 'bg-slate-900 dark:bg-white/10 text-white border border-slate-700 dark:border-white/20 hover:bg-slate-800 dark:hover:bg-white/20' 
                                        : 'bg-white/10 backdrop-blur-md text-white border border-white/20 hover:bg-white/20 hover:border-white/40'"
                                >
                                    <!-- Gradient border glow on hover -->
                                    <span class="absolute inset-0 rounded-full bg-gradient-to-r from-indigo-500/0 via-purple-500/0 to-pink-500/0 group-hover:from-indigo-500/20 group-hover:via-purple-500/20 group-hover:to-pink-500/20 transition-all duration-500"></span>
                                    <span class="relative z-10 flex items-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                                        </svg>
                                        Masuk Staff
                                    </span>
                                </button>
                            </Link>
                        </template>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Hero Section: Dark Premium -->
        <section id="home" class="relative min-h-[100vh] flex items-center pt-24 pb-40 overflow-hidden bg-gradient-to-b from-slate-900 via-slate-900 to-indigo-950">
            <!-- Background Elements -->
            <div class="absolute inset-0">
                <!-- Gradient Orbs -->
                <div class="absolute top-1/4 left-1/4 w-[600px] h-[600px] bg-indigo-600/30 rounded-full blur-[120px] animate-pulse-slow"></div>
                <div class="absolute bottom-1/4 right-1/4 w-[500px] h-[500px] bg-purple-600/20 rounded-full blur-[100px] animate-pulse-slow" style="animation-delay: 2s;"></div>
                <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[800px] h-[800px] bg-pink-600/10 rounded-full blur-[150px] animate-pulse-slow" style="animation-delay: 1s;"></div>
                
                <!-- Grid Pattern -->
                <div class="absolute inset-0 bg-[linear-gradient(rgba(255,255,255,0.02)_1px,transparent_1px),linear-gradient(90deg,rgba(255,255,255,0.02)_1px,transparent_1px)] bg-[size:60px_60px] [mask-image:radial-gradient(ellipse_80%_50%_at_50%_0%,#000_70%,transparent_110%)]"></div>
            </div>

            <div class="container mx-auto px-4 md:px-8 relative z-10">
                <div class="flex flex-col lg:flex-row items-center gap-16 lg:gap-20">
                    <!-- Left: Content -->
                    <div class="lg:w-1/2 space-y-8 text-center lg:text-left">
                        <!-- Badge -->
                        <div class="inline-flex items-center gap-3 px-4 py-2 rounded-full bg-white/5 border border-white/10 backdrop-blur-sm animate-fade-in">
                            <span class="relative flex h-2.5 w-2.5">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-emerald-500"></span>
                            </span>
                            <span class="text-sm font-medium text-slate-300">SKL Desk v2.0</span>
                        </div>

                        <!-- Headline -->
                        <div class="space-y-4 animate-fade-in-up">
                            <h1 class="text-5xl sm:text-6xl lg:text-7xl font-bold tracking-tight leading-[1.1]">
                                <span class="text-white">Dokumen</span>
                                <br />
                                <span class="bg-gradient-to-r from-indigo-400 via-purple-400 to-pink-400 bg-clip-text text-transparent">Lebih Hidup.</span>
                            </h1>
                            <p class="text-lg sm:text-xl text-slate-400 max-w-lg mx-auto lg:mx-0 leading-relaxed">
                                Revolusi digital layanan kelahiran dengan sistem validasi real-time dan keamanan data tingkat enterprise.
                            </p>
                        </div>

                        <!-- CTA Section -->
                        <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start animate-fade-in-up" style="animation-delay: 200ms;">
                            <!-- Primary CTA -->
                            <Button 
                                @click="scrollToSection('features')" 
                                size="lg" 
                                class="h-14 px-8 rounded-full bg-white text-slate-900 hover:bg-slate-100 font-bold text-base shadow-2xl shadow-white/20 hover:shadow-white/30 transition-all duration-300 hover:scale-105 border-0"
                            >
                                <Sparkles class="w-5 h-5 mr-2" />
                                Jelajahi Fitur
                            </Button>

                            <!-- Search Box -->
                            <div class="relative group w-full sm:w-auto">
                                <div class="flex items-center h-14 bg-white/5 backdrop-blur-xl border border-white/10 rounded-full px-2 focus-within:border-indigo-500/50 focus-within:bg-white/10 transition-all duration-300">
                                    <div class="pl-4 text-slate-500">
                                        <Search class="w-5 h-5" />
                                    </div>
                                    <input 
                                        v-model="searchQuery" 
                                        @keyup.enter="handleSearch"
                                        type="text" 
                                        class="flex-1 bg-transparent border-0 ring-0 focus:ring-0 text-white placeholder:text-slate-500 px-3 text-sm w-full sm:w-48" 
                                        placeholder="Verifikasi UUID..." 
                                    />
                                    <button 
                                        @click="handleSearch" 
                                        class="h-10 w-10 flex items-center justify-center rounded-full bg-indigo-600 hover:bg-indigo-500 text-white transition-all duration-300 hover:scale-110"
                                    >
                                        <ArrowRight class="w-4 h-4" />
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Trust Badges -->
                        <div class="flex items-center gap-8 justify-center lg:justify-start pt-4 animate-fade-in-up" style="animation-delay: 400ms;">
                            <div class="flex items-center gap-2 text-slate-500 text-sm font-medium">
                                <ShieldCheck class="w-5 h-5 text-emerald-500" />
                                <span>SSL Secured</span>
                            </div>
                            <div class="flex items-center gap-2 text-slate-500 text-sm font-medium">
                                <CheckCircle2 class="w-5 h-5 text-emerald-500" />
                                <span>ISO 27001</span>
                            </div>
                        </div>
                    </div>

                    <!-- Right: Visual -->
                    <div class="lg:w-1/2 relative">
                        <!-- Floating Elements -->
                        <div class="relative animate-float">
                            <!-- Main Card -->
                            <div class="relative bg-gradient-to-br from-white/10 to-white/5 backdrop-blur-2xl rounded-3xl p-8 border border-white/10 shadow-2xl">
                                <!-- Header -->
                                <div class="flex items-center gap-4 mb-6">
                                    <div class="h-14 w-14 rounded-2xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center shadow-lg shadow-indigo-500/30">
                                        <FileText class="h-7 w-7 text-white" />
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-bold text-white">Surat Keterangan Lahir</h3>
                                        <p class="text-sm text-slate-400">Digital Certificate</p>
                                    </div>
                                    <div class="ml-auto">
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-500/20 text-emerald-400 text-xs font-medium">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                            Valid
                                        </span>
                                    </div>
                                </div>
                                
                                <!-- Content Preview -->
                                <div class="space-y-4 mb-6">
                                    <div class="h-3 bg-white/10 rounded-full w-3/4"></div>
                                    <div class="h-3 bg-white/10 rounded-full w-1/2"></div>
                                    <div class="h-3 bg-white/10 rounded-full w-2/3"></div>
                                </div>
                                
                                <!-- QR & Signature -->
                                <div class="flex items-end justify-between pt-4 border-t border-white/10">
                                    <div class="h-20 w-20 rounded-xl bg-white/10 flex items-center justify-center">
                                        <QrCode class="h-12 w-12 text-white/60" />
                                    </div>
                                    <div class="text-right">
                                        <p class="text-xs text-slate-500 mb-1">Dokter Penanggung Jawab</p>
                                        <p class="text-sm font-medium text-white">dr. Specialist, Sp.OG</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Floating Card 1 - Top Right: Efficiency Stats -->
                            <div class="absolute -top-4 -right-4 md:-top-6 md:-right-8 z-20">
                                <div class="relative group">
                                    <!-- Glow effect -->
                                    <div class="absolute -inset-1 bg-gradient-to-r from-emerald-500/50 to-teal-500/50 rounded-2xl blur-lg opacity-0 group-hover:opacity-70 transition-opacity duration-500"></div>
                                    <!-- Card -->
                                    <div class="relative bg-gradient-to-br from-white/15 to-white/5 backdrop-blur-xl border border-white/20 p-4 rounded-2xl shadow-2xl animate-float-card">
                                        <div class="flex items-center gap-3">
                                            <div class="h-11 w-11 rounded-xl bg-gradient-to-br from-emerald-500/30 to-emerald-600/20 flex items-center justify-center ring-1 ring-emerald-500/30">
                                                <Activity class="w-5 h-5 text-emerald-400" />
                                            </div>
                                            <div>
                                                <div class="flex items-center gap-1.5">
                                                    <p class="text-base font-bold text-white">+24.5%</p>
                                                    <span class="text-emerald-400 text-xs">↑</span>
                                                </div>
                                                <p class="text-xs text-slate-400 font-medium">Efisiensi Layanan</p>
                                            </div>
                                        </div>
                                        <!-- Mini progress bar -->
                                        <div class="mt-3 h-1 w-full bg-white/10 rounded-full overflow-hidden">
                                            <div class="h-full w-3/4 bg-gradient-to-r from-emerald-500 to-teal-400 rounded-full"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Floating Card 2 - Bottom Right: AI System Status -->
                            <div class="absolute bottom-4 -right-4 md:bottom-8 md:-right-8 z-20" style="animation-delay: 1s;">
                                <div class="relative group">
                                    <!-- Glow effect -->
                                    <div class="absolute -inset-1 bg-gradient-to-r from-indigo-500/50 to-purple-500/50 rounded-2xl blur-lg opacity-0 group-hover:opacity-70 transition-opacity duration-500"></div>
                                    <!-- Card -->
                                    <div class="relative bg-gradient-to-br from-white/15 to-white/5 backdrop-blur-xl border border-white/20 p-4 rounded-2xl shadow-2xl animate-float-card">
                                        <div class="flex items-center gap-3">
                                            <div class="relative">
                                                <div class="h-11 w-11 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white font-bold text-sm shadow-lg shadow-indigo-500/30">
                                                    AI
                                                </div>
                                                <!-- Online indicator with pulse -->
                                                <div class="absolute -top-0.5 -right-0.5">
                                                    <span class="relative flex h-3.5 w-3.5">
                                                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                                        <span class="relative inline-flex rounded-full h-3.5 w-3.5 bg-emerald-500 border-2 border-slate-900"></span>
                                                    </span>
                                                </div>
                                            </div>
                                            <div>
                                                <p class="text-sm font-bold text-white">Smart System</p>
                                                <p class="text-xs text-emerald-400 font-medium">● Online 24/7</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Glow Effect -->
                        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[400px] h-[400px] bg-indigo-600/20 rounded-full blur-[100px] -z-10"></div>
                    </div>
                </div>
            </div>

            <!-- Scroll Indicator - Positioned outside content container -->
            <div class="absolute bottom-12 left-1/2 -translate-x-1/2 z-30">
                <button @click="scrollToSection('features')" class="flex flex-col items-center gap-1.5 text-slate-400 hover:text-white transition-colors group">
                    <span class="text-[10px] font-semibold uppercase tracking-[0.2em] opacity-60 group-hover:opacity-100 transition-opacity">Scroll</span>
                    <div class="w-6 h-10 rounded-full border-2 border-slate-600 group-hover:border-slate-400 flex items-start justify-center pt-2 transition-colors">
                        <div class="w-1 h-2.5 bg-slate-500 group-hover:bg-white rounded-full animate-scroll-indicator"></div>
                    </div>
                </button>
            </div>
        </section>

        <!-- Stats Section - Solid Dark Premium -->
        <section id="stats" class="relative z-20 bg-gradient-to-b from-indigo-950 to-slate-900 py-16 px-4 md:px-8">
            <!-- Background Gradient Orbs -->
            <div class="absolute top-0 left-1/4 w-[400px] h-[200px] bg-indigo-600/20 rounded-full blur-[100px] pointer-events-none"></div>
            <div class="absolute bottom-0 right-1/4 w-[300px] h-[200px] bg-purple-600/15 rounded-full blur-[80px] pointer-events-none"></div>
            
            <div class="container mx-auto max-w-6xl relative">
                <!-- Main Card with Solid Dark Background -->
                <div class="relative bg-slate-900/80 backdrop-blur-xl rounded-3xl border border-slate-700/50 shadow-2xl shadow-black/40 p-8 md:p-12 overflow-hidden">
                    <!-- Animated Gradient Border Top -->
                    <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 animate-gradient-shift"></div>
                    
                    <!-- Inner Glow -->
                    <div class="absolute inset-0 bg-gradient-to-br from-indigo-500/5 via-transparent to-purple-500/5 pointer-events-none"></div>
                    
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-8 md:gap-12 relative z-10">
                        <!-- Stat 1 - Layanan 24/7 -->
                        <div class="text-center group cursor-default">
                            <div class="relative mb-5 inline-block">
                                <!-- Glow Effect on Hover -->
                                <div class="absolute inset-0 bg-indigo-500/0 group-hover:bg-indigo-500/40 rounded-2xl blur-xl transition-all duration-500 scale-150"></div>
                                <!-- Icon Container -->
                                <div class="relative inline-flex p-5 rounded-2xl bg-indigo-500/20 border border-indigo-500/30 text-indigo-400 group-hover:bg-indigo-500 group-hover:border-indigo-400 group-hover:text-white group-hover:scale-110 group-hover:shadow-xl group-hover:shadow-indigo-500/40 transition-all duration-500">
                                    <Clock class="w-8 h-8" />
                                </div>
                            </div>
                            <h3 class="text-3xl md:text-4xl font-black text-white mb-2 drop-shadow-lg group-hover:text-indigo-400 transition-colors duration-300">24/7</h3>
                            <p class="text-slate-400 text-xs font-bold uppercase tracking-[0.2em]">Layanan</p>
                        </div>
                        
                        <!-- Stat 2 - 100% Aman -->
                        <div class="text-center group cursor-default">
                            <div class="relative mb-5 inline-block">
                                <div class="absolute inset-0 bg-purple-500/0 group-hover:bg-purple-500/40 rounded-2xl blur-xl transition-all duration-500 scale-150"></div>
                                <div class="relative inline-flex p-5 rounded-2xl bg-purple-500/20 border border-purple-500/30 text-purple-400 group-hover:bg-purple-500 group-hover:border-purple-400 group-hover:text-white group-hover:scale-110 group-hover:shadow-xl group-hover:shadow-purple-500/40 transition-all duration-500">
                                    <ShieldCheck class="w-8 h-8" />
                                </div>
                            </div>
                            <h3 class="text-3xl md:text-4xl font-black text-white mb-2 drop-shadow-lg group-hover:text-purple-400 transition-colors duration-300">100%</h3>
                            <p class="text-slate-400 text-xs font-bold uppercase tracking-[0.2em]">Aman</p>
                        </div>
                        
                        <!-- Stat 3 - QR Code Valid -->
                        <div class="text-center group cursor-default">
                            <div class="relative mb-5 inline-block">
                                <div class="absolute inset-0 bg-pink-500/0 group-hover:bg-pink-500/40 rounded-2xl blur-xl transition-all duration-500 scale-150"></div>
                                <div class="relative inline-flex p-5 rounded-2xl bg-pink-500/20 border border-pink-500/30 text-pink-400 group-hover:bg-pink-500 group-hover:border-pink-400 group-hover:text-white group-hover:scale-110 group-hover:shadow-xl group-hover:shadow-pink-500/40 transition-all duration-500">
                                    <QrCode class="w-8 h-8" />
                                </div>
                            </div>
                            <h3 class="text-3xl md:text-4xl font-black text-white mb-2 drop-shadow-lg group-hover:text-pink-400 transition-colors duration-300">Valid</h3>
                            <p class="text-slate-400 text-xs font-bold uppercase tracking-[0.2em]">QR Code</p>
                        </div>
                        
                        <!-- Stat 4 - Tim Medis Pro -->
                        <div class="text-center group cursor-default">
                            <div class="relative mb-5 inline-block">
                                <div class="absolute inset-0 bg-emerald-500/0 group-hover:bg-emerald-500/40 rounded-2xl blur-xl transition-all duration-500 scale-150"></div>
                                <div class="relative inline-flex p-5 rounded-2xl bg-emerald-500/20 border border-emerald-500/30 text-emerald-400 group-hover:bg-emerald-500 group-hover:border-emerald-400 group-hover:text-white group-hover:scale-110 group-hover:shadow-xl group-hover:shadow-emerald-500/40 transition-all duration-500">
                                    <Stethoscope class="w-8 h-8" />
                                </div>
                            </div>
                            <h3 class="text-3xl md:text-4xl font-black text-white mb-2 drop-shadow-lg group-hover:text-emerald-400 transition-colors duration-300">Pro</h3>
                            <p class="text-slate-400 text-xs font-bold uppercase tracking-[0.2em]">Tim Medis</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Features Section -->
        <section id="features" class="py-24 md:py-32 bg-slate-50 dark:bg-slate-950 relative overflow-hidden">
            <!-- Background Pattern -->
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_1px_1px,rgba(99,102,241,0.03)_1px,transparent_0)] [background-size:32px_32px]"></div>

            <div class="container mx-auto px-4 md:px-8 relative z-10">
                <!-- Section Header -->
                <div class="text-center mb-16 md:mb-20 max-w-3xl mx-auto">
                    <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-indigo-100 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-300 text-sm font-semibold mb-6">
                        <Sparkles class="w-4 h-4" />
                        Mengapa Memilih Kami
                    </span>
                    <h2 class="text-4xl md:text-5xl font-bold tracking-tight text-slate-900 dark:text-white mb-6 leading-tight">
                        Layanan Unggulan 
                        <span class="bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent">Digital</span>
                    </h2>
                    <p class="text-xl text-slate-600 dark:text-slate-400 leading-relaxed">
                        Transformasi digital untuk kenyamanan keluarga Anda. Satu sistem terintegrasi untuk masa depan.
                    </p>
                </div>

                <!-- Feature Cards -->
                <div class="grid md:grid-cols-3 gap-6 lg:gap-8">
                    <!-- Feature 1 -->
                    <div class="group relative bg-white dark:bg-slate-900 rounded-3xl p-8 md:p-10 border border-slate-100 dark:border-slate-800 hover:border-indigo-200 dark:hover:border-indigo-900/50 transition-all duration-500 hover:-translate-y-2 hover:shadow-2xl hover:shadow-indigo-500/10">
                        <div class="absolute top-0 right-0 w-32 h-32 bg-indigo-50 dark:bg-indigo-900/10 rounded-full -translate-y-1/2 translate-x-1/2 group-hover:scale-150 transition-transform duration-500"></div>
                        
                        <div class="relative">
                            <div class="mb-6 inline-flex h-14 w-14 items-center justify-center rounded-2xl bg-gradient-to-br from-indigo-500 to-blue-600 text-white shadow-lg shadow-indigo-500/30 group-hover:scale-110 transition-transform duration-300">
                                <FileCheck class="h-7 w-7" />
                            </div>
                            <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-3 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors">SKL Digital Resmi</h3>
                            <p class="text-slate-600 dark:text-slate-400 leading-relaxed">
                                Penerbitan dokumen instan yang sah secara hukum, siap unduh kapan saja dengan format standar nasional.
                            </p>
                        </div>
                    </div>

                    <!-- Feature 2 -->
                    <div class="group relative bg-white dark:bg-slate-900 rounded-3xl p-8 md:p-10 border border-slate-100 dark:border-slate-800 hover:border-purple-200 dark:hover:border-purple-900/50 transition-all duration-500 hover:-translate-y-2 hover:shadow-2xl hover:shadow-purple-500/10">
                        <div class="absolute top-0 right-0 w-32 h-32 bg-purple-50 dark:bg-purple-900/10 rounded-full -translate-y-1/2 translate-x-1/2 group-hover:scale-150 transition-transform duration-500"></div>
                        
                        <div class="relative">
                            <div class="mb-6 inline-flex h-14 w-14 items-center justify-center rounded-2xl bg-gradient-to-br from-purple-500 to-pink-600 text-white shadow-lg shadow-purple-500/30 group-hover:scale-110 transition-transform duration-300">
                                <QrCode class="h-7 w-7" />
                            </div>
                            <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-3 group-hover:text-purple-600 dark:group-hover:text-purple-400 transition-colors">Verifikasi QR Code</h3>
                            <p class="text-slate-600 dark:text-slate-400 leading-relaxed">
                                Cukup scan untuk validasi keaslian dokumen. Teknologi enkripsi terkini untuk keamanan data maksimal.
                            </p>
                        </div>
                    </div>

                    <!-- Feature 3 -->
                    <div class="group relative bg-white dark:bg-slate-900 rounded-3xl p-8 md:p-10 border border-slate-100 dark:border-slate-800 hover:border-pink-200 dark:hover:border-pink-900/50 transition-all duration-500 hover:-translate-y-2 hover:shadow-2xl hover:shadow-pink-500/10">
                        <div class="absolute top-0 right-0 w-32 h-32 bg-pink-50 dark:bg-pink-900/10 rounded-full -translate-y-1/2 translate-x-1/2 group-hover:scale-150 transition-transform duration-500"></div>
                        
                        <div class="relative">
                            <div class="mb-6 inline-flex h-14 w-14 items-center justify-center rounded-2xl bg-gradient-to-br from-pink-500 to-rose-600 text-white shadow-lg shadow-pink-500/30 group-hover:scale-110 transition-transform duration-300">
                                <Stethoscope class="h-7 w-7" />
                            </div>
                            <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-3 group-hover:text-pink-600 dark:group-hover:text-pink-400 transition-colors">Validasi Dokter</h3>
                            <p class="text-slate-600 dark:text-slate-400 leading-relaxed">
                                Terhubung langsung dengan data medis dokter penanggung jawab. Akurat, terpercaya, dan transparan.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Process Section -->
        <section id="process" class="py-24 md:py-32 bg-gradient-to-br from-slate-900 via-indigo-950 to-slate-900 text-white relative overflow-hidden">
            <!-- Background Decorations -->
            <div class="absolute top-0 right-0 w-[600px] h-[600px] bg-indigo-600/20 rounded-full blur-[150px]"></div>
            <div class="absolute bottom-0 left-0 w-[500px] h-[500px] bg-purple-600/15 rounded-full blur-[120px]"></div>

            <div class="container mx-auto px-4 md:px-8 relative z-10">
                <div class="flex flex-col lg:flex-row gap-16 lg:gap-20 items-center">
                    <!-- Left Content -->
                    <div class="lg:w-1/2">
                        <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white/10 border border-white/10 text-indigo-300 text-sm font-medium mb-6">
                            <Heart class="w-4 h-4" />
                            Edu-Corner
                        </span>
                        <h2 class="text-4xl md:text-5xl font-bold mb-8 leading-tight">
                            Langkah Awal Masa Depan 
                            <span class="text-indigo-400">Si Kecil.</span>
                        </h2>
                        
                        <div class="space-y-6">
                            <!-- Step 1 -->
                            <div class="flex gap-5 group">
                                <div class="flex-shrink-0 h-12 w-12 rounded-2xl bg-white/10 border border-white/10 flex items-center justify-center font-bold text-lg group-hover:bg-indigo-600 group-hover:border-indigo-500 transition-all duration-300">
                                    1
                                </div>
                                <div>
                                    <h4 class="text-lg font-bold mb-1 group-hover:text-indigo-300 transition-colors">Legalitas Identitas</h4>
                                    <p class="text-slate-400 leading-relaxed">Fondasi utama untuk NIK, Akta Kelahiran, dan Kartu Keluarga. Hak sipil pertama yang wajib dipenuhi.</p>
                                </div>
                            </div>
                            
                            <!-- Step 2 -->
                            <div class="flex gap-5 group">
                                <div class="flex-shrink-0 h-12 w-12 rounded-2xl bg-white/10 border border-white/10 flex items-center justify-center font-bold text-lg group-hover:bg-indigo-600 group-hover:border-indigo-500 transition-all duration-300">
                                    2
                                </div>
                                <div>
                                    <h4 class="text-lg font-bold mb-1 group-hover:text-indigo-300 transition-colors">Jaminan Kesehatan</h4>
                                    <p class="text-slate-400 leading-relaxed">Syarat mutlak pendaftaran BPJS Kesehatan bayi baru lahir untuk perlindungan kesehatan maksimal.</p>
                                </div>
                            </div>
                            
                            <!-- Step 3 -->
                            <div class="flex gap-5 group">
                                <div class="flex-shrink-0 h-12 w-12 rounded-2xl bg-white/10 border border-white/10 flex items-center justify-center font-bold text-lg group-hover:bg-indigo-600 group-hover:border-indigo-500 transition-all duration-300">
                                    3
                                </div>
                                <div>
                                    <h4 class="text-lg font-bold mb-1 group-hover:text-indigo-300 transition-colors">Validitas Negara</h4>
                                    <p class="text-slate-400 leading-relaxed">Mendukung program nasional dengan data kependudukan yang akurat dan real-time.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right: Timeline Card -->
                    <div class="lg:w-1/2 w-full">
                        <div class="bg-white/5 backdrop-blur-xl rounded-3xl p-8 md:p-10 border border-white/10 relative">
                            <h3 class="text-xl font-bold mb-8 flex items-center gap-3">
                                <div class="p-2.5 bg-emerald-500/20 rounded-xl">
                                    <CheckCircle2 class="h-5 w-5 text-emerald-400" />
                                </div>
                                Alur Pengurusan Mudah
                            </h3>
                            
                            <div class="relative border-l-2 border-indigo-500/30 ml-5 pl-8 space-y-8">
                                <div class="relative group">
                                    <div class="absolute -left-[33px] top-1 h-4 w-4 rounded-full bg-indigo-500 ring-4 ring-slate-900 group-hover:scale-125 transition-transform"></div>
                                    <p class="font-semibold text-white">Persalinan di RS</p>
                                    <p class="text-sm text-slate-400 mt-1">Data awal diinput oleh bidan/perawat.</p>
                                </div>
                                <div class="relative group">
                                    <div class="absolute -left-[33px] top-1 h-4 w-4 rounded-full bg-indigo-500 ring-4 ring-slate-900 group-hover:scale-125 transition-transform"></div>
                                    <p class="font-semibold text-white">Validasi Dokter</p>
                                    <p class="text-sm text-slate-400 mt-1">Konfirmasi data medis & tanda tangan digital.</p>
                                </div>
                                <div class="relative group">
                                    <div class="absolute -left-[33px] top-1 h-4 w-4 rounded-full bg-indigo-500 ring-4 ring-slate-900 group-hover:scale-125 transition-transform"></div>
                                    <p class="font-semibold text-white">Penerbitan SKL</p>
                                    <p class="text-sm text-slate-400 mt-1">Dokumen digital terbit dengan QR Code unik.</p>
                                </div>
                                <div class="relative group">
                                    <div class="absolute -left-[33px] top-1 h-4 w-4 rounded-full bg-emerald-500 ring-4 ring-slate-900 group-hover:scale-125 transition-transform"></div>
                                    <p class="font-semibold text-white">Serah Terima</p>
                                    <p class="text-sm text-slate-400 mt-1">Keluarga menerima dokumen fisik & akses digital.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA Section -->
        <section class="py-24 relative overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-r from-indigo-600 via-purple-600 to-indigo-600 bg-[length:200%_100%] animate-gradient"></div>
            <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSI2MCIgaGVpZ2h0PSI2MCI+PGNpcmNsZSBjeD0iMzAiIGN5PSIzMCIgcj0iMSIgZmlsbD0icmdiYSgyNTUsMjU1LDI1NSwwLjEpIi8+PC9zdmc+')] opacity-50"></div>

            <div class="container mx-auto px-4 md:px-8 relative z-10 text-center">
                <h2 class="text-3xl md:text-5xl font-bold text-white mb-6 tracking-tight">
                    Siap Melangkah Bersama Kami?
                </h2>
                <p class="text-indigo-100 mb-10 max-w-2xl mx-auto text-lg leading-relaxed">
                    Kami siap membantu setiap langkah Anda dalam menyambut kehadiran anggota keluarga baru.
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <Button variant="outline" size="lg" class="h-14 px-8 rounded-full border-white/30 bg-white/10 text-white backdrop-blur-sm hover:bg-white hover:text-indigo-700 transition-all duration-300 font-semibold">
                        <Phone class="w-4 h-4 mr-2" />
                        Hubungi Admin
                    </Button>
                    <Button @click="scrollToSection('home')" size="lg" class="h-14 px-8 rounded-full bg-white text-indigo-700 hover:bg-indigo-50 shadow-xl transition-all duration-300 font-bold border-0 hover:scale-105">
                        Kembali ke Atas
                        <ArrowRight class="w-4 h-4 ml-2" />
                    </Button>
                </div>
            </div>
        </section>

        <!-- Footer -->
        <footer class="bg-slate-950 pt-20 pb-10 border-t border-slate-900">
            <div class="container mx-auto px-4 md:px-8">
                <div class="grid md:grid-cols-12 gap-12 mb-16">
                    <!-- Brand -->
                    <div class="md:col-span-5 space-y-6">
                        <div class="flex items-center gap-3">
                            <div class="h-11 w-11 rounded-2xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center shadow-lg shadow-indigo-500/20">
                                <Baby class="h-6 w-6 text-white" />
                            </div>
                            <div>
                                <span class="text-xl font-bold text-white tracking-tight block">Rumah Sakit Maranatha</span>
                                <span class="text-sm text-slate-500">SKL Desk — Sistem Kelahiran Digital</span>
                            </div>
                        </div>
                        <p class="text-slate-400 leading-relaxed max-w-sm">
                            Platform dokumentasi kelahiran modern berstandar medis. Mengintegrasikan kemudahan teknologi dengan keamanan data pasien RS Maranatha.
                        </p>
                        <div class="flex gap-3">
                            <a href="#" class="h-10 w-10 rounded-xl bg-slate-900 flex items-center justify-center text-slate-400 hover:bg-indigo-600 hover:text-white transition-all duration-300">
                                <Facebook class="w-5 h-5" />
                            </a>
                            <a href="#" class="h-10 w-10 rounded-xl bg-slate-900 flex items-center justify-center text-slate-400 hover:bg-sky-500 hover:text-white transition-all duration-300">
                                <Twitter class="w-5 h-5" />
                            </a>
                            <a href="#" class="h-10 w-10 rounded-xl bg-slate-900 flex items-center justify-center text-slate-400 hover:bg-pink-600 hover:text-white transition-all duration-300">
                                <Instagram class="w-5 h-5" />
                            </a>
                            <a href="#" class="h-10 w-10 rounded-xl bg-slate-900 flex items-center justify-center text-slate-400 hover:bg-blue-700 hover:text-white transition-all duration-300">
                                <Linkedin class="w-5 h-5" />
                            </a>
                        </div>
                    </div>

                    <!-- Navigation -->
                    <div class="md:col-span-3">
                        <h4 class="text-white font-bold text-lg mb-6">Navigasi</h4>
                        <ul class="space-y-4">
                            <li><a href="#home" class="text-slate-400 hover:text-indigo-400 transition-colors">Beranda</a></li>
                            <li><a href="#features" class="text-slate-400 hover:text-indigo-400 transition-colors">Fitur Unggulan</a></li>
                            <li><a href="#process" class="text-slate-400 hover:text-indigo-400 transition-colors">Alur Pengurusan</a></li>
                            <li><Link href="/login" class="text-slate-400 hover:text-indigo-400 transition-colors">Login Staff</Link></li>
                        </ul>
                    </div>

                    <!-- Contact -->
                    <div class="md:col-span-4">
                        <h4 class="text-white font-bold text-lg mb-6">Hubungi Kami</h4>
                        <ul class="space-y-4 text-slate-400">
                            <li class="flex items-start gap-3">
                                <MapPin class="w-5 h-5 text-indigo-500 mt-0.5 shrink-0" />
                                <div>
                                    <span class="block">Taman Kopo Indah III Blok H-1</span>
                                    <span class="block text-slate-500">Mekar Rahayu, Margaasih</span>
                                    <span class="block text-slate-500">Kab. Bandung</span>
                                </div>
                            </li>
                            <li class="flex items-center gap-3">
                                <Phone class="w-5 h-5 text-indigo-500 shrink-0" />
                                <span>(022) 86011220</span>
                            </li>
                            <li class="flex items-center gap-3">
                                <Mail class="w-5 h-5 text-indigo-500 shrink-0" />
                                <span>info@rs-maranatha.co.id</span>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="pt-8 border-t border-slate-900 flex flex-col md:flex-row justify-between items-center gap-4 text-sm text-slate-500">
                    <p>&copy; 2025 Rumah Sakit Maranatha. All rights reserved.</p>
                    <div class="flex items-center gap-6">
                        <a href="#" class="hover:text-white transition-colors">Privacy Policy</a>
                        <a href="#" class="hover:text-white transition-colors">Terms of Service</a>
                        <span class="opacity-50">Powered by Laravel & Vue.js</span>
                    </div>
                </div>
            </div>
        </footer>
    </div>
</template>

<style scoped>
/* Smooth Scrolling */
html {
    scroll-behavior: smooth;
}

/* Animations */
@keyframes fade-in {
    from { opacity: 0; }
    to { opacity: 1; }
}

@keyframes fade-in-up {
    from { 
        opacity: 0; 
        transform: translateY(20px); 
    }
    to { 
        opacity: 1; 
        transform: translateY(0); 
    }
}

@keyframes float {
    0%, 100% { transform: translateY(0px); }
    50% { transform: translateY(-15px); }
}

@keyframes float-card {
    0%, 100% { transform: translateY(0px) rotate(0deg); }
    50% { transform: translateY(-10px) rotate(2deg); }
}

@keyframes pulse-slow {
    0%, 100% { opacity: 0.3; }
    50% { opacity: 0.15; }
}

@keyframes gradient {
    0% { background-position: 0% 50%; }
    50% { background-position: 100% 50%; }
    100% { background-position: 0% 50%; }
}

.animate-fade-in {
    animation: fade-in 0.8s ease-out forwards;
}

.animate-fade-in-up {
    animation: fade-in-up 0.8s ease-out forwards;
}

.animate-float {
    animation: float 6s ease-in-out infinite;
}

.animate-float-card {
    animation: float-card 5s ease-in-out infinite;
}

.animate-pulse-slow {
    animation: pulse-slow 4s ease-in-out infinite;
}

.animate-gradient {
    animation: gradient 8s ease infinite;
}

@keyframes gradient-shift {
    0% { background-position: 0% 50%; }
    50% { background-position: 100% 50%; }
    100% { background-position: 0% 50%; }
}

.animate-gradient-shift {
    background-size: 200% 200%;
    animation: gradient-shift 3s ease infinite;
}

@keyframes scroll-indicator {
    0%, 100% { 
        opacity: 1;
        transform: translateY(0);
    }
    50% { 
        opacity: 0.5;
        transform: translateY(8px);
    }
}

.animate-scroll-indicator {
    animation: scroll-indicator 1.5s ease-in-out infinite;
}
</style>
