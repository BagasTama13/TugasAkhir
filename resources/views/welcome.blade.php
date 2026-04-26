<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>BPTrans - Bahan Bangunan Berkualitas Terbaik</title>
        <!-- Google Fonts: Inter + Plus Jakarta Sans -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Plus+Jakarta+Sans:wght@500;600;700;800&display=swap" rel="stylesheet">
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <style>
            body { font-family: 'Inter', sans-serif; scroll-behavior: smooth; }
            h1,h2,h3,h4,h5,h6,nav { font-family: 'Plus Jakarta Sans', sans-serif; }
            .footer-brand { font-family: 'Plus Jakarta Sans', sans-serif; }

            @keyframes fadeInUp {
                from { opacity: 0; transform: translateY(30px); }
                to { opacity: 1; transform: translateY(0); }
            }
            .animate-fade-in-up {
                animation: fadeInUp 0.8s ease-out forwards;
            }

            @keyframes float {
                0%, 100% { transform: translateY(0) rotate(0deg); }
                50% { transform: translateY(-20px) rotate(2deg); }
            }
            .animate-float {
                animation: float 6s ease-in-out infinite;
            }

            @keyframes bounceSlow {
                0%, 100% { transform: translateY(0); }
                50% { transform: translateY(-10px); }
            }
            .animate-bounce-slow {
                animation: bounceSlow 3s ease-in-out infinite;
            }

            .glass-card {
                background: rgba(255, 255, 255, 0.7);
                backdrop-filter: blur(10px);
                border: 1px solid rgba(255, 255, 255, 0.2);
            }
        </style>
    </head>
    <body class="text-gray-900 antialiased">
        <!-- Navigation -->
        <nav class="fixed top-0 left-0 right-0 z-50 transition-all duration-300 backdrop-blur-md bg-white/70 border-b border-white/20">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center h-20 justify-between">
                    <!-- Logo -->
                    <div class="flex items-center flex-shrink-0 group">
                        <div class="relative">
                            <div class="absolute -inset-1 bg-gradient-to-r from-blue-600 to-blue-400 rounded-full blur opacity-25 group-hover:opacity-50 transition duration-1000 group-hover:duration-200"></div>
                            <img src="{{ asset('images/colt.png') }}" alt="BPTrans Logo" class="relative h-12 w-12 object-contain mr-3 transform group-hover:scale-110 transition duration-300">
                        </div>
                        <div>
                            <h1 class="text-gray-900 text-2xl font-black tracking-tight leading-none">BP<span class="text-blue-600">Trans</span></h1>
                            <p class="text-blue-600 text-[10px] font-bold tracking-[0.2em] uppercase">Material Solutions</p>
                        </div>
                    </div>

                    <!-- Navigation Menu -->
                    <div class="hidden md:flex gap-10 items-center">
                        <a href="#home" class="text-gray-700 hover:text-blue-600 font-bold text-sm uppercase tracking-wider transition duration-200 relative group">
                            Home
                            <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-blue-600 transition-all duration-300 group-hover:w-full"></span>
                        </a>
                        <a href="#tentang" class="text-gray-700 hover:text-blue-600 font-bold text-sm uppercase tracking-wider transition duration-200 relative group">
                            Tentang
                            <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-blue-600 transition-all duration-300 group-hover:w-full"></span>
                        </a>
                        <a href="#produk" class="text-gray-700 hover:text-blue-600 font-bold text-sm uppercase tracking-wider transition duration-200 relative group">
                            Produk
                            <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-blue-600 transition-all duration-300 group-hover:w-full"></span>
                        </a>
                        <a href="#footer" class="text-gray-700 hover:text-blue-600 font-bold text-sm uppercase tracking-wider transition duration-200 relative group">
                            Kontak
                            <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-blue-600 transition-all duration-300 group-hover:w-full"></span>
                        </a>
                    </div>

                    <!-- CTA Button -->
                    <div class="flex items-center">
                        <a href="{{ route('login.user') }}" class="group relative inline-flex items-center gap-2 px-6 py-2.5 bg-blue-600 text-white font-bold rounded-full transition-all duration-300 hover:bg-blue-700 hover:shadow-lg hover:shadow-blue-200 hover:-translate-y-0.5 overflow-hidden">
                            <div class="absolute inset-0 bg-gradient-to-r from-blue-600 to-blue-500 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                            <svg class="relative w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                            <span class="relative">Login User</span>
                        </a>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Hero Section -->
        <section id="home" class="relative min-h-screen flex items-center pt-20 overflow-hidden bg-white">
            <!-- Decorative Background Elements -->
            <div class="absolute top-0 right-0 -translate-y-1/2 translate-x-1/4 w-[600px] h-[600px] bg-blue-50 rounded-full blur-3xl opacity-50"></div>
            <div class="absolute bottom-0 left-0 translate-y-1/2 -translate-x-1/4 w-[500px] h-[500px] bg-blue-100 rounded-full blur-3xl opacity-30"></div>
            
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
                    <!-- Left Content -->
                    <div class="animate-fade-in-up">
                        <div class="inline-flex items-center px-3 py-1 rounded-full bg-blue-50 border border-blue-100 text-blue-600 text-xs font-bold uppercase tracking-widest mb-6">
                            <span class="flex h-2 w-2 rounded-full bg-blue-600 mr-2 animate-pulse"></span>
                            Partner Konstruksi Terpercaya
                        </div>
                        <h2 class="text-5xl md:text-7xl font-extrabold text-gray-900 mb-8 leading-[1.1]">
                            Bangun Impian <br>
                            <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-blue-800">Tanpa Batas</span>
                        </h2>
                        <p class="text-lg md:text-xl text-gray-600 mb-10 leading-relaxed max-w-xl">
                            Solusi bahan bangunan terlengkap dengan segala jenis dan kualitas untuk mewujudkan hunian yang kokoh dan estetis. Pengiriman cepat, harga kompetitif, dan pelayanan prima.
                        </p>
                        
                        <div class="flex flex-wrap gap-5">
                            <a href="#produk" class="px-8 py-4 bg-blue-600 text-white font-bold rounded-2xl shadow-2xl shadow-blue-200 hover:bg-blue-700 hover:-translate-y-1 transition duration-300 flex items-center gap-2 group">
                                Jelajahi Produk
                                <svg class="w-5 h-5 group-hover:translate-x-1 transition duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                            </a>
                            <a href="#footer" class="px-8 py-4 bg-white text-gray-900 font-bold rounded-2xl border-2 border-gray-100 hover:border-blue-600 hover:text-blue-600 transition duration-300">
                                Konsultasi Gratis
                            </a>
                        </div>

                        <!-- Social Proof -->
                        <div class="mt-12 flex items-center gap-6">
                            <div class="flex -space-x-3">
                                <div class="w-10 h-10 rounded-full border-2 border-white bg-blue-100 flex items-center justify-center text-[10px] font-bold text-blue-600">BT</div>
                                <div class="w-10 h-10 rounded-full border-2 border-white bg-blue-200 flex items-center justify-center text-[10px] font-bold text-blue-700">BP</div>
                                <div class="w-10 h-10 rounded-full border-2 border-white bg-blue-300 flex items-center justify-center text-[10px] font-bold text-blue-800">TW</div>
                            </div>
                            <div class="text-sm text-gray-500">
                                <span class="font-bold text-gray-900">10,000+</span> Pelanggan Puas di Jepara
                            </div>
                        </div>
                    </div>

                    <!-- Right Visual -->
                    <div class="relative lg:block">
                        <div class="relative z-10 animate-float">
                            <div class="absolute inset-0 bg-blue-600/10 rounded-[2rem] blur-2xl -rotate-6 scale-95"></div>
                            <div class="relative bg-gradient-to-br from-white to-blue-50 p-8 rounded-[2rem] border border-white shadow-2xl overflow-hidden group">
                                <img src="{{ asset('images/colt.png') }}" alt="BPTrans Visual" class="w-full h-auto object-contain transform group-hover:scale-105 transition duration-700">
                                
                                <!-- Floating Badge -->
                                <div class="absolute top-6 right-6 bg-white/90 backdrop-blur shadow-lg p-4 rounded-2xl border border-white/50 animate-bounce-slow">
                                    <div class="text-blue-600 font-black text-xl leading-none">20+</div>
                                    <div class="text-gray-500 text-[10px] font-bold uppercase tracking-tighter">Tahun Pengalaman</div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Background Accent -->
                        <svg class="absolute -top-10 -right-10 w-64 h-64 text-blue-50 opacity-50" fill="currentColor" viewBox="0 0 100 100">
                            <circle cx="50" cy="50" r="40" />
                        </svg>
                    </div>
                </div>
            </div>
        </section>

        <!-- About Section (Company Info) -->
        <section id="tentang" class="bg-slate-50 py-24 relative overflow-hidden">
            <!-- Background Decoration -->
            <div class="absolute top-0 left-0 w-full h-full opacity-[0.03] pointer-events-none">
                <svg width="100%" height="100%" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <pattern id="grid" width="40" height="40" patternUnits="userSpaceOnUse">
                        <path d="M 40 0 L 0 0 0 40" stroke="currentColor" stroke-width="1"/>
                    </pattern>
                    <rect width="100%" height="100%" fill="url(#grid)" />
                </svg>
            </div>

            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
                <div class="text-center mb-16">
                    <span class="text-blue-600 font-bold uppercase tracking-[0.3em] text-xs">Mengenal Kami</span>
                    <h2 class="text-4xl font-black text-gray-900 mt-4 mb-6">Membangun Fondasi <br><span class="text-blue-600 text-3xl font-extrabold">Bersama BPTrans</span></h2>
                    <div class="w-20 h-1.5 bg-blue-600 mx-auto rounded-full"></div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <!-- About -->
                    <div class="group bg-white p-8 rounded-[2.5rem] shadow-xl shadow-gray-200/50 hover:shadow-2xl hover:shadow-blue-200/50 transition-all duration-500 border border-gray-100 hover:-translate-y-2">
                        <div class="w-16 h-16 bg-blue-50 rounded-2xl flex items-center justify-center text-blue-600 mb-8 group-hover:scale-110 group-hover:bg-blue-600 group-hover:text-white transition-all duration-500 shadow-inner">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                        </div>
                        <h3 class="text-2xl font-black text-gray-900 mb-4">Tentang BPTrans</h3>
                        <p class="text-gray-500 leading-relaxed text-sm">
                            Distributor bahan bangunan terkemuka di Jepara dengan pengalaman <span class="text-blue-600 font-bold">20+ tahun</span>. Kami berdedikasi tinggi dalam melayani kebutuhan konstruksi lokal dengan komitmen segala jenis dan kualitas dengan harga yang tetap terjangkau.
                        </p>
                    </div>

                    <!-- Vision -->
                    <div class="group bg-white p-8 rounded-[2.5rem] shadow-xl shadow-gray-200/50 hover:shadow-2xl hover:shadow-blue-200/50 transition-all duration-500 border border-gray-100 hover:-translate-y-2">
                        <div class="w-16 h-16 bg-indigo-50 rounded-2xl flex items-center justify-center text-indigo-600 mb-8 group-hover:scale-110 group-hover:bg-indigo-600 group-hover:text-white transition-all duration-500 shadow-inner">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                        </div>
                        <h3 class="text-2xl font-black text-gray-900 mb-4">Visi Kami</h3>
                        <p class="text-gray-500 leading-relaxed text-sm">
                            Menjadi partner strategis dan distributor bahan bangunan <span class="text-indigo-600 font-bold">pilihan utama</span> di Jepara. Kami terus berinovasi untuk menyediakan produk berstandar tinggi guna mendukung pertumbuhan infrastruktur yang berkelanjutan.
                        </p>
                    </div>

                    <!-- Mission -->
                    <div class="group bg-white p-8 rounded-[2.5rem] shadow-xl shadow-gray-200/50 hover:shadow-2xl hover:shadow-blue-200/50 transition-all duration-500 border border-gray-100 hover:-translate-y-2">
                        <div class="w-16 h-16 bg-emerald-50 rounded-2xl flex items-center justify-center text-emerald-600 mb-8 group-hover:scale-110 group-hover:bg-emerald-600 group-hover:text-white transition-all duration-500 shadow-inner">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path></svg>
                        </div>
                        <h3 class="text-2xl font-black text-gray-900 mb-4">Misi Kami</h3>
                        <div class="space-y-3">
                            <div class="flex items-center gap-3 text-sm text-gray-500">
                                <span class="flex-shrink-0 w-5 h-5 rounded-full bg-emerald-100 flex items-center justify-center text-emerald-600 font-bold">✓</span>
                                <span>Segala jenis dan kualitas produk terjamin</span>
                            </div>
                            <div class="flex items-center gap-3 text-sm text-gray-500">
                                <span class="flex-shrink-0 w-5 h-5 rounded-full bg-emerald-100 flex items-center justify-center text-emerald-600 font-bold">✓</span>
                                <span>Harga kompetitif & transparan</span>
                            </div>
                            <div class="flex items-center gap-3 text-sm text-gray-500">
                                <span class="flex-shrink-0 w-5 h-5 rounded-full bg-emerald-100 flex items-center justify-center text-emerald-600 font-bold">✓</span>
                                <span>Layanan pelanggan responsif 24/7</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Products Section -->
        <section id="produk" class="py-20 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-16">
                    <h3 class="text-4xl font-bold text-gray-800 mb-4">Produk & Layanan Kami</h3>
                    <p class="text-xl text-gray-600">Pilihan lengkap bahan bangunan berkualitas untuk proyek Anda</p>
                </div>

                @if ($products && $products->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">
                        @foreach ($products as $product)
                            <div class="group bg-white rounded-[2rem] border border-gray-100 shadow-xl shadow-gray-200/50 hover:shadow-2xl hover:shadow-blue-200/50 transition-all duration-500 overflow-hidden flex flex-col">
                                <!-- Product Image -->
                                <div class="relative h-64 bg-gray-50 overflow-hidden">
                                    @if ($product->gambar)
                                        <img src="{{ asset('storage/' . $product->gambar) }}" alt="{{ $product->nama }}" class="w-full h-full object-cover group-hover:scale-110 transition duration-700">
                                    @else
                                        <div class="w-full h-full flex flex-col items-center justify-center text-gray-300">
                                            <svg class="w-20 h-20 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                            <span class="text-xs font-bold uppercase tracking-widest">No Image</span>
                                        </div>
                                    @endif
                                    
                                    <!-- Price Tag -->
                                    <div class="absolute bottom-4 right-4 bg-white/90 backdrop-blur-md px-4 py-2 rounded-xl shadow-lg border border-white/50">
                                        <span class="text-blue-600 font-black text-lg">Rp {{ number_format($product->harga, 0, ',', '.') }}</span>
                                        <span class="text-gray-400 text-[10px] font-bold uppercase">/ {{ $product->satuan ?? 'unit' }}</span>
                                    </div>
                                </div>

                                <!-- Content -->
                                <div class="p-8 flex-1 flex flex-col">
                                    <h4 class="text-2xl font-black text-gray-900 mb-3 group-hover:text-blue-600 transition-colors duration-300">{{ $product->nama }}</h4>
                                    
                                    <p class="text-gray-500 text-sm leading-relaxed mb-8 flex-1">
                                        {{ $product->deskripsi ?? 'Deskripsi tidak tersedia untuk produk ini.' }}
                                    </p>

                                    <!-- Order Button -->
                                    <a href="{{ route('login.user') }}" class="inline-flex items-center justify-center w-full py-4 bg-gray-900 text-white font-bold rounded-2xl hover:bg-blue-600 transform hover:-translate-y-1 transition-all duration-300 gap-2 group/btn shadow-lg shadow-gray-200">
                                        Pesan Sekarang
                                        <svg class="w-5 h-5 group-hover/btn:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12">
                        <p class="text-gray-500 text-lg">Produk belum tersedia. Silakan kembali lagi nanti.</p>
                    </div>
                @endif
            </div>
        </section>

        <!-- Footer -->
        <footer id="footer" class="bg-gray-900 text-gray-300" style="font-family: 'Inter', sans-serif;">
            <!-- Top gradient accent -->
            <div class="h-1 bg-gradient-to-r from-blue-500 via-blue-400 to-blue-600"></div>

            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-12 mb-12">

                    <!-- Kolom 1: Brand -->
                    <div>
                        <div class="flex items-center mb-5">
                            <img src="{{ asset('images/colt.png') }}" alt="BPTrans Logo" class="h-12 w-12 object-contain mr-3 drop-shadow-lg">
                            <div>
                                <h5 class="text-white font-extrabold leading-tight tracking-tight"
                                    style="font-family: 'Plus Jakarta Sans', sans-serif; font-size: 1.35rem;">BPTrans</h5>
                                <p class="text-blue-400 font-medium tracking-widest uppercase"
                                   style="font-size: 0.65rem; letter-spacing: 0.1em;">Bahan Bangunan Terpercaya</p>
                            </div>
                        </div>
                        <p class="text-gray-400 leading-relaxed" style="font-size: 0.875rem; line-height: 1.75;">
                            Distributor bahan bangunan berkualitas di Jepara dan sekitarnya. Berpengalaman 20+ tahun melayani kebutuhan konstruksi lokal dengan dedikasi tinggi dan harga kompetitif.
                        </p>
                        <div class="flex gap-3 mt-6">
                            <a href="https://wa.me/+628587765358" target="_blank"
                               class="w-10 h-10 rounded-full bg-gray-700 hover:bg-green-600 flex items-center justify-center transition duration-200"
                               title="WhatsApp" style="font-size: 1rem;">
                                📱
                            </a>
                            <a href="mailto:infobptrans@gmail.com"
                               class="w-10 h-10 rounded-full bg-gray-700 hover:bg-blue-600 flex items-center justify-center transition duration-200"
                               title="Email" style="font-size: 1rem;">
                                ✉️
                            </a>
                            <a href="https://maps.app.goo.gl/RgmK5rZsd5Ce3RVz7" target="_blank"
                               class="w-10 h-10 rounded-full bg-gray-700 hover:bg-red-600 flex items-center justify-center transition duration-200"
                               title="Lokasi" style="font-size: 1rem;">
                                📍
                            </a>
                        </div>
                    </div>

                    <!-- Kolom 2: Navigasi -->
                    <div>
                        <h5 class="text-white font-bold mb-5 pb-3 border-b border-gray-700"
                            style="font-family: 'Plus Jakarta Sans', sans-serif; font-size: 0.9rem; letter-spacing: 0.05em; text-transform: uppercase;">
                            Navigasi
                        </h5>
                        <ul class="space-y-3">
                            <li>
                                <a href="#home" class="text-gray-400 hover:text-blue-400 transition duration-200 flex items-center gap-2"
                                   style="font-size: 0.9rem; font-weight: 400;">
                                    <span class="text-blue-500 font-bold text-lg leading-none">›</span> Beranda
                                </a>
                            </li>
                            <li>
                                <a href="#tentang" class="text-gray-400 hover:text-blue-400 transition duration-200 flex items-center gap-2"
                                   style="font-size: 0.9rem; font-weight: 400;">
                                    <span class="text-blue-500 font-bold text-lg leading-none">›</span> Tentang Kami
                                </a>
                            </li>
                            <li>
                                <a href="#produk" class="text-gray-400 hover:text-blue-400 transition duration-200 flex items-center gap-2"
                                   style="font-size: 0.9rem; font-weight: 400;">
                                    <span class="text-blue-500 font-bold text-lg leading-none">›</span> Produk & Layanan
                                </a>
                            </li>
                            <li>
                                <a href="#kontak" class="text-gray-400 hover:text-blue-400 transition duration-200 flex items-center gap-2"
                                   style="font-size: 0.9rem; font-weight: 400;">
                                    <span class="text-blue-500 font-bold text-lg leading-none">›</span> Kontak
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('login.user') }}" class="text-gray-400 hover:text-blue-400 transition duration-200 flex items-center gap-2"
                                   style="font-size: 0.9rem; font-weight: 400;">
                                    <span class="text-blue-500 font-bold text-lg leading-none">›</span> Login Member
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('login.admin') }}" class="text-gray-500 hover:text-red-400 transition duration-200 flex items-center gap-2 mt-4"
                                   style="font-size: 0.8rem; font-weight: 400;">
                                    <span class="text-gray-600 font-bold text-lg leading-none">›</span> Login Admin
                                </a>
                            </li>
                        </ul>
                    </div>

                    <!-- Kolom 3: Kontak -->
                    <div>
                        <h5 class="text-white font-bold mb-5 pb-3 border-b border-gray-700"
                            style="font-family: 'Plus Jakarta Sans', sans-serif; font-size: 0.9rem; letter-spacing: 0.05em; text-transform: uppercase;">
                            Kontak Kami
                        </h5>
                        <ul class="space-y-5">
                            <!-- Telepon -->
                            <li>
                                <a href="tel:+628587765358"
                                   class="flex items-start gap-3 hover:text-blue-400 transition duration-200 group">
                                    <span class="flex-shrink-0 leading-none" style="font-size: 1rem; margin-top: 2px;">📞</span>
                                    <div>
                                        <p class="text-gray-500 mb-1" style="font-size: 0.7rem; font-weight: 600; letter-spacing: 0.07em; text-transform: uppercase;">Telepon / WhatsApp</p>
                                        <span class="group-hover:text-blue-400 transition text-gray-200" style="font-size: 0.9rem; font-weight: 500; line-height: 1.4;">+62 858-7765-3585</span>
                                    </div>
                                </a>
                            </li>
                            <!-- Email -->
                            <li>
                                <a href="mailto:infobptrans@gmail.com"
                                   class="flex items-start gap-3 hover:text-blue-400 transition duration-200 group">
                                    <span class="flex-shrink-0 leading-none" style="font-size: 1rem; margin-top: 2px;">✉️</span>
                                    <div>
                                        <p class="text-gray-500 mb-1" style="font-size: 0.7rem; font-weight: 600; letter-spacing: 0.07em; text-transform: uppercase;">Email</p>
                                        <span class="group-hover:text-blue-400 transition text-gray-200" style="font-size: 0.9rem; font-weight: 500; line-height: 1.4;">infobptrans@gmail.com</span>
                                    </div>
                                </a>
                            </li>
                            <!-- Alamat -->
                            <li>
                                <a href="https://maps.app.goo.gl/RgmK5rZsd5Ce3RVz7" target="_blank"
                                   class="flex items-start gap-3 hover:text-blue-400 transition duration-200 group">
                                    <span class="flex-shrink-0 leading-none" style="font-size: 1rem; margin-top: 2px;">📍</span>
                                    <div>
                                        <p class="text-gray-500 mb-1" style="font-size: 0.7rem; font-weight: 600; letter-spacing: 0.07em; text-transform: uppercase;">Alamat</p>
                                        <span class="group-hover:text-blue-400 transition text-gray-200" style="font-size: 0.9rem; font-weight: 500; line-height: 1.7;">Dusun 2, Gemiring Kidul, Kec. Nalumsari, Kab. Jepara, Jawa Tengah 59466</span>
                                    </div>
                                </a>
                            </li>
                        </ul>
                    </div>

                </div>

                <!-- Bottom bar -->
                <div class="border-t border-gray-700 pt-6 flex flex-col md:flex-row items-center justify-between gap-3">
                    <p class="text-gray-500 text-center md:text-left" style="font-size: 0.82rem;">
                        &copy; {{ date('Y') }} <span class="text-gray-300 font-semibold">BPTrans</span>. Semua hak dilindungi.
                    </p>
                    <p class="text-gray-600" style="font-size: 0.75rem; letter-spacing: 0.03em;">
                        Distributor Bahan Bangunan Terpercaya di Jepara
                    </p>
                </div>
            </div>
        </footer>
        <script>
            window.addEventListener('scroll', function() {
                const nav = document.querySelector('nav');
                if (window.scrollY > 50) {
                    nav.classList.add('bg-white/90', 'shadow-md');
                    nav.classList.remove('bg-white/70');
                } else {
                    nav.classList.add('bg-white/70');
                    nav.classList.remove('bg-white/90', 'shadow-md');
                }
            });
        </script>
    </body>
</html>
                    