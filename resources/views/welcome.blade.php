<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="BPTrans Jepara — Distributor material konstruksi premium & terpercaya. Batu bata, genteng, kayu, dan grajen dengan kualitas Grade A. Layanan logistik terpadu ke seluruh wilayah Jepara dan sekitarnya.">
    <title>BPTrans | Solusi Material Konstruksi Premium & Terpercaya</title>
    <meta name="description" content="BPTrans Jepara — supplier material konstruksi premium: batu bata, genteng, kayu, dan grajen. Pengiriman logistik tepat waktu se-Jepara dan sekitarnya.">

    {{-- DNS Prefetch untuk percepatan koneksi --}}
    <link rel="dns-prefetch" href="//fonts.googleapis.com">
    <link rel="dns-prefetch" href="//fonts.gstatic.com">

    {{-- Preconnect wajib untuk Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    {{--
        KRITIS: Preload font Plus Jakarta Sans Bold (digunakan oleh LCP element h1)
        Memastikan font tersedia sebelum browser merender h1,
        menghilangkan FOUT yang menyebabkan LCP lambat.
    --}}
    <link rel="preload" as="style" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Plus+Jakarta+Sans:wght@700;800&display=swap" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Plus+Jakarta+Sans:wght@700;800&display=swap" media="print" onload="this.media='all'" />
    <noscript><link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Plus+Jakarta+Sans:wght@700;800&display=swap" /></noscript>

    <!-- Preload LCP Image -->
    <link rel="preload" href="{{ asset('images/colt.webp') }}" as="image" fetchpriority="high">

    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/css/welcome.css', 'resources/js/welcome.js'])
</head>
<body class="welcome-body bg-white text-slate-900 antialiased overflow-x-hidden text-[16px]">

    <!-- Premium Navigation -->
    <nav x-data="{ atTop: true, mobileMenuOpen: false }" 
         @scroll.window="atTop = (window.pageYOffset > 26 ? false : true)"
         :class="atTop ? 'bg-transparent py-[1.618rem]' : 'glass py-[1rem] shadow-xl shadow-slate-200/50'"
         class="fixed top-0 left-0 right-0 z-50 transition-all duration-500">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between">
                <!-- Logo -->
                <a href="#" class="flex items-center gap-[1rem] group">
                    <div class="h-[42px] w-[42px] bg-white rounded-xl flex items-center justify-center overflow-hidden shadow-lg group-hover:scale-110 transition-transform">
                        <img src="{{ asset('images/colt.webp') }}" alt="Logo" class="w-full h-full object-contain p-1">
                    </div>
                    <span :class="atTop ? 'text-slate-900' : 'text-slate-900'" class="text-[20px] font-display font-black tracking-tight uppercase italic">BPTrans</span>
                </a>

                <!-- Nav Links -->
                <div class="hidden md:flex items-center gap-[2.618rem]">
                    <a href="#home" class="text-[16px] font-bold uppercase tracking-widest text-slate-600 hover:text-indigo-600 transition-colors">{{ __('Beranda') }}</a>
                    <a href="#produk" class="text-[16px] font-bold uppercase tracking-widest text-slate-600 hover:text-indigo-600 transition-colors">{{ __('Produk') }}</a>
                    <a href="#tentang" class="text-[16px] font-bold uppercase tracking-widest text-slate-600 hover:text-indigo-600 transition-colors">{{ __('Tentang') }}</a>
                    <a href="#kontak" class="text-[16px] font-bold uppercase tracking-widest text-slate-600 hover:text-indigo-600 transition-colors">{{ __('Kontak') }}</a>
                </div>

                <!-- CTA & Lang Switcher -->
                <div class="flex items-center gap-[1.618rem]">
                    <!-- Language Switcher -->
                    <div class="flex items-center bg-slate-100/50 backdrop-blur-md rounded-full p-1 border border-slate-200/60 shadow-sm">
                        <a href="{{ route('lang.switch', 'id') }}" class="px-3 py-1 rounded-full text-[12px] font-bold transition-all {{ session('locale', config('app.locale')) === 'id' ? 'bg-white text-indigo-600 shadow-sm' : 'text-slate-500 hover:text-slate-900' }}">ID</a>
                        <a href="{{ route('lang.switch', 'en') }}" class="px-3 py-1 rounded-full text-[12px] font-bold transition-all {{ session('locale', config('app.locale')) === 'en' ? 'bg-white text-indigo-600 shadow-sm' : 'text-slate-500 hover:text-slate-900' }}">EN</a>
                    </div>
                    
                    <a href="{{ route('login.user') }}" class="hidden sm:flex items-center gap-[1rem] px-[1.618rem] py-[1rem] bg-slate-900 text-white rounded-full text-[14px] font-black uppercase tracking-widest hover:bg-indigo-600 hover:shadow-lg hover:shadow-indigo-200 transition-all active:scale-95">
                        <svg class="w-[20px] h-[20px]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        {{ __('Portal Member') }}
                    </a>
                    {{-- Mobile Hamburger Button --}}
                    <button @click="mobileMenuOpen = !mobileMenuOpen" class="sm:hidden flex items-center justify-center h-[42px] w-[42px] bg-slate-100 text-slate-600 rounded-xl hover:bg-slate-200 transition-all">
                        <svg class="w-[24px] h-[24px]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path x-show="!mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                            <path x-show="mobileMenuOpen" x-cloak stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        {{-- Mobile Menu Drawer --}}
        <div x-show="mobileMenuOpen" x-cloak 
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 -translate-y-4"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 -translate-y-4"
             class="sm:hidden absolute top-full left-0 right-0 bg-white/95 backdrop-blur-md border-b border-slate-200 shadow-xl py-4 px-4 flex flex-col gap-4 z-40">
            <a @click="mobileMenuOpen = false" href="#home" class="block text-[16px] font-bold uppercase tracking-widest text-slate-700 hover:text-indigo-600 py-2 border-b border-slate-100">{{ __('Beranda') }}</a>
            <a @click="mobileMenuOpen = false" href="#produk" class="block text-[16px] font-bold uppercase tracking-widest text-slate-700 hover:text-indigo-600 py-2 border-b border-slate-100">{{ __('Produk') }}</a>
            <a @click="mobileMenuOpen = false" href="#tentang" class="block text-[16px] font-bold uppercase tracking-widest text-slate-700 hover:text-indigo-600 py-2 border-b border-slate-100">{{ __('Tentang') }}</a>
            <a @click="mobileMenuOpen = false" href="#kontak" class="block text-[16px] font-bold uppercase tracking-widest text-slate-700 hover:text-indigo-600 py-2 border-b border-slate-100">{{ __('Kontak') }}</a>
            <a href="{{ route('login.user') }}" class="mt-2 flex items-center justify-center gap-[1rem] px-[1.618rem] py-[1rem] bg-indigo-600 text-white rounded-xl text-[14px] font-black uppercase tracking-widest hover:bg-indigo-700 transition-all">
                <svg class="w-[20px] h-[20px]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                {{ __('Portal Member') }}
            </a>
        </div>
    </nav>

    <!-- Professional Hero Section -->
    <section id="home" class="relative min-h-screen flex items-center pt-[6.854rem] overflow-hidden">
        <!-- Animated Background Elements -->
        <div class="absolute top-[-10%] right-[-5%] w-96 h-96 bg-indigo-400 rounded-full mix-blend-multiply filter blur-[128px] opacity-40 animate-blob"></div>
        <div class="absolute top-[20%] right-[10%] w-72 h-72 bg-cyan-300 rounded-full mix-blend-multiply filter blur-[128px] opacity-40 animate-blob animation-delay-2000"></div>
        <div class="absolute bottom-[-10%] left-[-10%] w-96 h-96 bg-purple-400 rounded-full mix-blend-multiply filter blur-[128px] opacity-30 animate-blob animation-delay-4000"></div>
        
        <div class="absolute inset-0 bg-[radial-gradient(#e5e7eb_1px,transparent_1px)] [background-size:42px_42px] opacity-30"></div>
        <div class="absolute top-0 right-0 w-[38.2%] h-full bg-gradient-to-l from-indigo-50/50 to-transparent -z-10"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 w-full">
            <div class="flex flex-col lg:flex-row gap-[2.618rem] items-center justify-between">
                <!-- Content (Golden Ratio: 61.8%) -->
                <div class="w-full lg:w-[61.8%] text-center lg:text-left space-y-[2.618rem]" data-aos="fade-right">
                    <div class="inline-flex items-center gap-[1rem] px-[1.618rem] py-[1rem] bg-white/80 backdrop-blur-md rounded-2xl shadow-sm border border-slate-200/60 hover:shadow-md transition-shadow">
                        <span class="flex h-[10px] w-[10px] rounded-full bg-indigo-600 animate-pulse"></span>
                        <span class="text-[12px] font-black text-slate-600 uppercase tracking-[0.2em]">{!! __('Mitra Strategis Pengadaan Material') !!}</span>
                    </div>
                    
                    <h1 class="text-[36px] md:text-[60px] lg:text-[72px] font-display font-black text-slate-900 leading-[1.1]">
                        {!! __('Suplai Material Premium, <br>
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 to-cyan-500 animate-gradient-xy">Solusi Logistik Terpadu.</span>') !!}
                    </h1>
                    
                    <p class="text-[20px] text-slate-500 max-w-2xl mx-auto lg:mx-0 leading-relaxed font-medium">
                        {{ __('Menghadirkan ekosistem rantai pasok material konstruksi berspesifikasi tinggi. Kami mendefinisikan ulang standar keunggulan mutu melalui distribusi logistik yang efisien, terstruktur, dan presisi.') }}
                    </p>

                    <div class="flex flex-col sm:flex-row justify-center lg:justify-start gap-[1rem] sm:gap-[1.618rem] pt-[1.618rem]">
                        <a href="#produk" class="group relative px-[2.618rem] py-[1.618rem] bg-indigo-600 text-white rounded-2xl font-bold shadow-xl shadow-indigo-200 hover:shadow-indigo-300 transition-all transform hover:-translate-y-1 overflow-hidden">
                            <span class="relative z-10 flex items-center justify-center gap-[1rem] text-[16px]">
                                {{ __('Mulai Belanja Material') }}
                                <svg class="w-[26px] h-[26px] group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                            </span>
                            <div class="absolute inset-0 bg-gradient-to-r from-indigo-600 to-indigo-500 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                        </a>
                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $no_whatsapp ?? '085877653585') }}" class="px-[2.618rem] py-[1.618rem] bg-white/80 backdrop-blur-md text-slate-900 border border-slate-200 rounded-2xl font-bold hover:bg-slate-50 hover:shadow-md hover:border-slate-300 transition-all flex items-center justify-center gap-[1rem] text-[16px]">
                            <svg class="w-[26px] h-[26px] text-emerald-500" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                            Konsultasi WhatsApp
                        </a>
                    </div>

                    <!-- Trust Bar -->
                    <div class="flex flex-wrap justify-center lg:justify-start items-center gap-[2.618rem] pt-[2.618rem] border-t border-slate-200 mt-[4.236rem]" data-aos="fade-up" data-aos-delay="200">
                        <div class="text-center lg:text-left">
                            <p class="text-[36px] font-black text-slate-900 leading-none">10k+</p>
                            <p class="text-[12px] font-bold text-slate-500 uppercase tracking-widest mt-2">Customers</p>
                        </div>
                        <div class="text-center lg:text-left">
                            <p class="text-[36px] font-black text-slate-900 leading-none">20+</p>
                            <p class="text-[12px] font-bold text-slate-500 uppercase tracking-widest mt-2">Experience</p>
                        </div>
                        <div class="text-center lg:text-left">
                            <p class="text-[36px] font-black text-slate-900 leading-none">Jepara</p>
                            <p class="text-[12px] font-bold text-slate-500 uppercase tracking-widest mt-2">Region Hub</p>
                        </div>
                    </div>
                </div>

                <!-- Visual (Golden Ratio: 38.2%) -->
                <div class="w-full lg:w-[38.2%] relative px-[1.618rem]" data-aos="fade-left">
                    <!-- Decorative Circles -->
                    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[120%] aspect-square bg-gradient-to-tr from-indigo-100 to-cyan-50 rounded-full -z-10 blur-3xl opacity-60"></div>
                    
                    <div class="relative transform hover:scale-[1.02] transition-transform duration-700 ease-out">
                        <img src="{{ asset('images/colt.webp') }}" alt="BPTrans — Armada Logistik Material" fetchpriority="high" width="600" height="500" class="w-full h-auto object-contain drop-shadow-[0_30px_60px_rgba(79,70,229,0.2)] animate-float">
                        
                        <!-- Mini Badges -->
                        <div class="absolute top-[10%] right-[-10%] glass p-[1.618rem] rounded-2xl shadow-xl hidden md:block animate-bounce" style="animation-duration: 3s;">
                            <div class="flex items-center gap-[1rem]">
                                <div class="h-[42px] w-[42px] bg-gradient-to-br from-emerald-400 to-emerald-600 text-white rounded-xl flex items-center justify-center shadow-lg shadow-emerald-200">
                                    <svg class="w-[20px] h-[20px]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                </div>
                                <div>
                                    <span class="block text-[14px] font-black text-slate-900 uppercase">Trusted Quality</span>
                                    <span class="block text-[12px] font-bold text-slate-500">Grade A Materials</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="absolute bottom-[10%] left-[-10%] glass p-[1.618rem] rounded-2xl shadow-xl hidden md:block animate-bounce" style="animation-duration: 4s; animation-delay: 1s;">
                            <div class="flex items-center gap-[1rem]">
                                <div class="h-[42px] w-[42px] bg-gradient-to-br from-amber-400 to-orange-500 text-white rounded-xl flex items-center justify-center shadow-lg shadow-amber-200">
                                    <svg class="w-[20px] h-[20px]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                </div>
                                <div>
                                    <span class="block text-[14px] font-black text-slate-900 uppercase">Fast Delivery</span>
                                    <span class="block text-[12px] font-bold text-slate-500">On-time Logistics</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Services Section (Tentang Kami) -->
    <section id="tentang" class="py-[6.854rem] relative overflow-hidden bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="text-center max-w-4xl mx-auto mb-[4.236rem] space-y-[1.618rem]" data-aos="fade-up">
                <p class="text-indigo-600 font-black text-[14px] uppercase tracking-[0.3em]">{{ __('Mengapa Memilih Kami') }}</p>
                <h2 class="text-[36px] md:text-[48px] font-display font-black text-slate-900 leading-tight">{{ __('Solusi Terbaik Konstruksi Anda') }}</h2>
                <div class="w-[68px] h-[6px] bg-gradient-to-r from-indigo-600 to-cyan-500 mx-auto rounded-full mt-[1.618rem]"></div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-[2.618rem]">
                <!-- Feature 1 -->
                <div class="group relative bg-white p-[2.618rem] rounded-[2.618rem] border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.04)] hover:shadow-[0_8px_30px_rgba(79,70,229,0.1)] transition-all duration-500 hover:-translate-y-2 overflow-hidden" data-aos="zoom-in" data-aos-delay="100">
                    <div class="absolute inset-0 bg-gradient-to-br from-indigo-50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                    <div class="relative z-10">
                        <div class="h-[68px] w-[68px] bg-indigo-50 text-indigo-600 rounded-2xl flex items-center justify-center mb-[2.618rem] group-hover:scale-110 group-hover:bg-indigo-600 group-hover:text-white transition-all duration-500 shadow-sm">
                            <svg class="w-[32px] h-[32px]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                        </div>
                        <h3 class="text-[22px] font-black text-slate-900 mb-[1rem] group-hover:text-indigo-600 transition-colors">{{ __('Material Terlengkap') }}</h3>
                        <p class="text-[16px] text-slate-500 leading-relaxed font-medium">{{ __('Menyediakan portofolio material konstruksi yang komprehensif, mulai dari agregat esensial hingga bahan baku bangunan, dengan standar kualitas Grade A yang konsisten.') }}</p>
                    </div>
                </div>

                <!-- Feature 2 -->
                <div class="group relative bg-white p-[2.618rem] rounded-[2.618rem] border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.04)] hover:shadow-[0_8px_30px_rgba(16,185,129,0.1)] transition-all duration-500 hover:-translate-y-2 overflow-hidden" data-aos="zoom-in" data-aos-delay="200">
                    <div class="absolute inset-0 bg-gradient-to-br from-emerald-50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                    <div class="relative z-10">
                        <div class="h-[68px] w-[68px] bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center mb-[2.618rem] group-hover:scale-110 group-hover:bg-emerald-600 group-hover:text-white transition-all duration-500 shadow-sm">
                            <svg class="w-[32px] h-[32px]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <h3 class="text-[22px] font-black text-slate-900 mb-[1rem] group-hover:text-emerald-600 transition-colors">{{ __('Harga Kompetitif') }}</h3>
                        <p class="text-[16px] text-slate-500 leading-relaxed font-medium">{{ __('Memberikan penawaran harga tangan pertama yang objektif dan sangat kompetitif, dirancang khusus untuk memaksimalkan efisiensi alokasi anggaran proyek Anda.') }}</p>
                    </div>
                </div>

                <!-- Feature 3 -->
                <div class="group relative bg-white p-[2.618rem] rounded-[2.618rem] border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.04)] hover:shadow-[0_8px_30px_rgba(245,158,11,0.1)] transition-all duration-500 hover:-translate-y-2 overflow-hidden" data-aos="zoom-in" data-aos-delay="300">
                    <div class="absolute inset-0 bg-gradient-to-br from-amber-50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                    <div class="relative z-10">
                        <div class="h-[68px] w-[68px] bg-amber-50 text-amber-600 rounded-2xl flex items-center justify-center mb-[2.618rem] group-hover:scale-110 group-hover:bg-amber-600 group-hover:text-white transition-all duration-500 shadow-sm">
                            <svg class="w-[32px] h-[32px]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        </div>
                        <h3 class="text-[22px] font-black text-slate-900 mb-[1rem] group-hover:text-amber-600 transition-colors">{{ __('Jangkauan Luas') }}</h3>
                        <p class="text-[16px] text-slate-500 leading-relaxed font-medium">{{ __('Didukung oleh manajemen rantai pasok dan armada logistik yang andal, memastikan pengiriman material Anda dilakukan secara presisi, tepat waktu, dan terjamin keamanannya ke seluruh wilayah Jepara dan sekitarnya.') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Product Grid Section -->
    <section id="produk" class="py-[6.854rem] bg-slate-50 relative">
        <div class="absolute top-0 inset-x-0 h-px bg-gradient-to-r from-transparent via-slate-200 to-transparent"></div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row md:items-end justify-between mb-[4.236rem] gap-[2.618rem]" data-aos="fade-up">
                <div class="max-w-2xl space-y-[1rem]">
                    <p class="text-indigo-600 font-black text-[14px] uppercase tracking-[0.3em]">{{ __('Katalog Material') }}</p>
                    <h2 class="text-[36px] md:text-[48px] font-display font-black text-slate-900 leading-tight">{{ __('Produk Unggulan Kami') }}</h2>
                </div>
                <a href="{{ route('login.user') }}" class="group inline-flex items-center gap-[1rem] text-[16px] font-black text-indigo-600 uppercase tracking-widest hover:text-indigo-700 transition-colors">
                    <span>{{ __('Lihat Semua') }}</span>
                    <div class="h-[42px] w-[42px] rounded-full bg-indigo-100 flex items-center justify-center group-hover:bg-indigo-600 group-hover:text-white transition-all">
                        <svg class="w-[20px] h-[20px] group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7l5 5m0 0l-5 5m5-5H6"></path></svg>
                    </div>
                </a>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @forelse($products->take(8) as $index => $product)
                    <div class="group bg-white rounded-2xl border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.04)] hover:shadow-[0_20px_40px_rgba(79,70,229,0.15)] transition-all duration-500 overflow-hidden flex flex-col transform hover:-translate-y-2 relative" data-aos="fade-up" data-aos-delay="{{ $index * 100 }}">
                        <div class="absolute inset-0 bg-gradient-to-br from-indigo-500 to-cyan-400 opacity-0 group-hover:opacity-10 transition-opacity duration-500"></div>
                        
                        <div class="relative h-[12rem] overflow-hidden bg-slate-100">
                            @if($product->gambar)
                                <img src="{{ asset('storage/' . $product->gambar) }}" alt="{{ $product->nama }}" loading="lazy" width="300" height="192" class="w-full h-full object-cover group-hover:scale-110 transition duration-700 ease-in-out">
                            @else
                                <div class="w-full h-full flex flex-col items-center justify-center text-slate-300">
                                    <svg class="w-[48px] h-[48px] mb-[1rem]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    <span class="text-[12px] font-black uppercase tracking-widest">No Image</span>
                                </div>
                            @endif
                            <div class="absolute bottom-[1rem] right-[1rem] glass px-[1rem] py-[0.5rem] rounded-[1rem] shadow-lg border border-white/60 transform group-hover:scale-105 transition-transform duration-300">
                                <span class="text-indigo-600 font-black text-[20px]">Rp {{ number_format($product->harga, 0, ',', '.') }}</span>
                                <span class="text-slate-500 text-[12px] font-bold">/ {{ $product->satuan ?? 'rit' }}</span>
                            </div>
                        </div>
                        <div class="p-6 flex-1 flex flex-col relative z-10 bg-white">
                            <h3 class="text-[22px] font-black text-slate-900 mb-[1rem] group-hover:text-indigo-600 transition-colors">{{ $product->nama }}</h3>
                            <p class="text-[14px] text-slate-500 leading-relaxed line-clamp-2 mb-[1.618rem] flex-1">{{ $product->deskripsi ?? 'Material konstruksi berkualitas tinggi untuk kebutuhan pembangunan Anda.' }}</p>
                            <a href="{{ route('login.user') }}" class="w-full py-3 bg-slate-50 text-slate-900 border border-slate-200 group-hover:border-transparent rounded-xl font-bold text-[14px] text-center group-hover:bg-gradient-to-r group-hover:from-indigo-600 group-hover:to-cyan-500 group-hover:text-white transition-all duration-300 active:scale-95 shadow-sm group-hover:shadow-lg group-hover:shadow-indigo-200">
                                Pesan Sekarang
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full py-[4.236rem] text-center" data-aos="fade-in">
                        <p class="text-[16px] text-slate-400 italic">Belum ada produk untuk ditampilkan.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer id="kontak" class="bg-slate-900 text-slate-400 pt-[6.854rem] pb-[4.236rem] overflow-hidden relative">
        <!-- Abstract Dark Elements -->
        <div class="absolute top-0 left-0 w-full h-px bg-gradient-to-r from-transparent via-slate-700 to-transparent"></div>
        <div class="absolute -top-[20%] -left-[10%] w-96 h-96 bg-indigo-600 rounded-full mix-blend-screen filter blur-[128px] opacity-10"></div>
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <!-- Layout Footer Golden Ratio: 61.8% Brand, 38.2% Links -->
            <div class="flex flex-col lg:flex-row gap-[4.236rem] mb-[6.854rem]">
                <!-- Brand (61.8%) -->
                <div class="w-full lg:w-[61.8%] space-y-[2.618rem]" data-aos="fade-up" data-aos-delay="100">
                    <a href="#" class="flex items-center gap-[1rem]">
                        <div class="h-[68px] w-[68px] bg-white rounded-xl flex items-center justify-center overflow-hidden p-[0.5rem] shadow-lg shadow-indigo-500/20">
                            <img src="{{ asset('images/colt.webp') }}" alt="Logo" class="w-full h-full object-contain">
                        </div>
                        <span class="text-[32px] font-display font-black text-white tracking-tight uppercase italic">BPTrans</span>
                    </a>
                    <p class="text-[16px] leading-relaxed max-w-lg text-slate-300">Distributor bahan bangunan terpercaya di Jepara dengan pengalaman lebih dari 20 tahun melayani kebutuhan konstruksi lokal secara profesional.</p>
                    <div class="flex gap-[1.618rem]">
                        <a href="https://www.instagram.com/bptrans_/" target="_blank" rel="noopener noreferrer" class="h-[68px] w-[68px] rounded-2xl glass-dark flex items-center justify-center text-slate-300 hover:text-white hover:bg-pink-600 hover:border-pink-500 transition-all duration-300 shadow-sm hover:shadow-pink-500/50 hover:-translate-y-1"><svg class="w-[26px] h-[26px]" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204 013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg></a>
                    </div>
                </div>

                <!-- Links & Contact (38.2%) -->
                <div class="w-full lg:w-[38.2%] flex flex-col sm:flex-row gap-[2.618rem]" data-aos="fade-up" data-aos-delay="200">
                    <div class="flex-1">
                        <h4 class="text-white font-bold uppercase tracking-widest text-[14px] mb-[1.618rem]">Navigasi</h4>
                        <ul class="space-y-[1rem]">
                            <li><a href="#home" class="text-[16px] hover:text-white transition-colors">Beranda</a></li>
                            <li><a href="#produk" class="text-[16px] hover:text-white transition-colors">Katalog Produk</a></li>
                            <li><a href="#tentang" class="text-[16px] hover:text-white transition-colors">Tentang Kami</a></li>
                            <li><a href="{{ route('login.admin') }}" class="text-[16px] text-slate-600 hover:text-indigo-400 transition-colors">Admin Login</a></li>
                        </ul>
                    </div>
                    
                    <div class="flex-1">
                        <h4 class="text-white font-bold uppercase tracking-widest text-[14px] mb-[1.618rem]">Kontak</h4>
                        <ul class="space-y-[1rem]">
                            <li class="flex items-start gap-[1rem]">
                                <span class="text-indigo-500 mt-1">
                                    <svg class="w-[20px] h-[20px]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                </span>
                                <a href="https://maps.app.goo.gl/QzW4S77VC4XWCe5N9" target="_blank" rel="noopener noreferrer" class="text-[16px] leading-relaxed hover:text-white transition-colors">Gemiring Kidul, Kec. Nalumsari, Kab. Jepara, Jawa Tengah</a>
                            </li>
                            <li class="flex items-center gap-[1rem]">
                                <span class="text-indigo-500">
                                    <svg class="w-[20px] h-[20px]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                                </span>
                                <a href="https://wa.me/6285877653585" target="_blank" rel="noopener noreferrer" class="text-[16px] hover:text-white transition-colors">+62 858-7765-3585</a>
                            </li>
                            <li class="flex items-center gap-[1rem]">
                                <span class="text-indigo-500">
                                    <svg class="w-[20px] h-[20px]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                </span>
                                <a href="mailto:bptransportation123@gmail.com" class="text-[16px] hover:text-white transition-colors">bptransportation123@gmail.com</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="pt-[2.618rem] border-t border-slate-800 flex flex-col md:flex-row items-center justify-between gap-[1.618rem]">
                <p class="text-[14px]">&copy; {{ date('Y') }} BPTrans Logistik & Material. Developed by <a href="https://github.com/BagasTama13" target="_blank" rel="noopener noreferrer" class="text-indigo-500 hover:text-indigo-400 transition-colors">BagasTama13</a>.</p>
                <p class="text-[12px] font-bold uppercase tracking-[0.2em] text-slate-600">Suplai Material Premium, Solusi Logistik Terpadu</p>
            </div>
        </div>
    </footer>

    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    {{--
        Ganti AOS dengan IntersectionObserver ringan yang TIDAK menyembunyikan elemen.
        AOS menyebabkan CLS karena ia menyembunyikan elemen (opacity:0, transform)
        sebelum viewport mencapainya — ini menyebabkan layout shift saat elemen muncul.
        Pendekatan ini hanya menambahkan class CSS tanpa mengubah layout sama sekali.
    --}}
    <script>
        (function() {
            // Tambahkan style animasi ringan tanpa mengubah layout
            var style = document.createElement('style');
            style.textContent = `
                .fade-in-up { animation: fadeInUp 0.7s ease-out both; }
                .fade-in-right { animation: fadeInRight 0.7s ease-out both; }
                .fade-in-left { animation: fadeInLeft 0.7s ease-out both; }
                .zoom-in-anim { animation: zoomIn 0.6s ease-out both; }
                @keyframes fadeInUp {
                    from { opacity: 0; transform: translateY(24px); }
                    to   { opacity: 1; transform: translateY(0); }
                }
                @keyframes fadeInRight {
                    from { opacity: 0; transform: translateX(-24px); }
                    to   { opacity: 1; transform: translateX(0); }
                }
                @keyframes fadeInLeft {
                    from { opacity: 0; transform: translateX(24px); }
                    to   { opacity: 1; transform: translateX(0); }
                }
                @keyframes zoomIn {
                    from { opacity: 0; transform: scale(0.95); }
                    to   { opacity: 1; transform: scale(1); }
                }
            `;
            document.head.appendChild(style);

            // Map data-aos ke CSS class
            var aosMap = {
                'fade-up':    'fade-in-up',
                'fade-right': 'fade-in-right',
                'fade-left':  'fade-in-left',
                'zoom-in':    'zoom-in-anim',
                'fade-down':  'fade-in-up',
            };

            document.addEventListener('DOMContentLoaded', function() {
                var observer = new IntersectionObserver(function(entries) {
                    entries.forEach(function(entry) {
                        if (entry.isIntersecting) {
                            var el = entry.target;
                            var aosType = el.getAttribute('data-aos') || 'fade-up';
                            var delay = parseInt(el.getAttribute('data-aos-delay') || '0');
                            var cssClass = aosMap[aosType] || 'fade-in-up';
                            setTimeout(function() {
                                el.classList.add(cssClass);
                            }, delay);
                            observer.unobserve(el);
                        }
                    });
                }, { threshold: 0.1, rootMargin: '0px 0px -50px 0px' });

                // Observe semua elemen dengan data-aos
                document.querySelectorAll('[data-aos]').forEach(function(el) {
                    observer.observe(el);
                });

                // Elemen di viewport saat load langsung dianimasikan
                document.querySelectorAll('[data-aos]').forEach(function(el) {
                    var rect = el.getBoundingClientRect();
                    if (rect.top < window.innerHeight) {
                        var aosType = el.getAttribute('data-aos') || 'fade-up';
                        var cssClass = aosMap[aosType] || 'fade-in-up';
                        el.classList.add(cssClass);
                        observer.unobserve(el);
                    }
                });
            });
        })();
    </script>
</body>
</html>