<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>BPTrans | Solusi Material Konstruksi Premium & Terpercaya</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Plus+Jakarta+Sans:wght@700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body { font-family: 'Inter', sans-serif; }
        .font-display { font-family: 'Plus Jakarta Sans', sans-serif; }
        
        .glass {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .text-gradient {
            background: linear-gradient(135deg, #4F46E5 0%, #06B6D4 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .bg-grid {
            background-size: 40px 40px;
            background-image: radial-gradient(circle, #E2E8F0 1px, transparent 1px);
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }
        .animate-float { animation: float 6s ease-in-out infinite; }

        @keyframes blob {
            0%, 100% { transform: translate(0px, 0px) scale(1); }
            33% { transform: translate(30px, -50px) scale(1.1); }
            66% { transform: translate(-20px, 20px) scale(0.9); }
        }
        .animate-blob { animation: blob 7s infinite; }
        .animation-delay-2000 { animation-delay: 2s; }
        .animation-delay-4000 { animation-delay: 4s; }
    </style>
</head>
<body class="bg-white text-slate-900 antialiased overflow-x-hidden">

    <!-- Premium Navigation -->
    <nav x-data="{ atTop: true }" 
         @scroll.window="atTop = (window.pageYOffset > 20 ? false : true)"
         :class="atTop ? 'bg-transparent py-6' : 'glass py-4 shadow-xl shadow-slate-200/50'"
         class="fixed top-0 left-0 right-0 z-50 transition-all duration-500">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between">
                <!-- Logo -->
                <a href="#" class="flex items-center gap-3 group">
                    <div class="h-10 w-10 bg-white rounded-xl flex items-center justify-center overflow-hidden shadow-lg group-hover:scale-110 transition-transform">
                        <img src="{{ asset('images/colt.png') }}" alt="Logo" class="w-full h-full object-contain p-1">
                    </div>
                    <span :class="atTop ? 'text-slate-900' : 'text-slate-900'" class="text-xl font-display font-black tracking-tight uppercase italic">BPTrans</span>
                </a>

                <!-- Nav Links -->
                <div class="hidden md:flex items-center gap-10">
                    <a href="#home" class="text-sm font-bold uppercase tracking-widest text-slate-600 hover:text-indigo-600 transition-colors">Beranda</a>
                    <a href="#produk" class="text-sm font-bold uppercase tracking-widest text-slate-600 hover:text-indigo-600 transition-colors">Produk</a>
                    <a href="#tentang" class="text-sm font-bold uppercase tracking-widest text-slate-600 hover:text-indigo-600 transition-colors">Tentang</a>
                    <a href="#kontak" class="text-sm font-bold uppercase tracking-widest text-slate-600 hover:text-indigo-600 transition-colors">Kontak</a>
                </div>

                <!-- CTA -->
                <div class="flex items-center gap-4">
                    <a href="{{ route('login.user') }}" class="hidden sm:flex items-center gap-2 px-6 py-2.5 bg-slate-900 text-white rounded-full text-xs font-black uppercase tracking-widest hover:bg-indigo-600 hover:shadow-lg hover:shadow-indigo-200 transition-all active:scale-95">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        Portal Member
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Professional Hero Section -->
    <section id="home" class="relative min-h-screen flex items-center pt-20 overflow-hidden">
        <!-- Subtle Background Pattern -->
        <div class="absolute inset-0 bg-[radial-gradient(#e5e7eb_1px,transparent_1px)] [background-size:20px_20px] opacity-30"></div>
        <div class="absolute top-0 right-0 w-1/2 h-full bg-gradient-to-l from-indigo-50/50 to-transparent -z-10"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-20 items-center">
                <!-- Content -->
                <div class="order-2 lg:order-1 text-center lg:text-left space-y-8">
                    <div class="inline-flex items-center gap-3 px-4 py-2 bg-white rounded-2xl shadow-sm border border-slate-100">
                        <span class="flex h-2 w-2 rounded-full bg-indigo-600"></span>
                        <span class="text-[10px] font-black text-slate-500 uppercase tracking-[0.2em]">Partner Konstruksi Terpercaya</span>
                    </div>
                    
                    <h1 class="text-4xl md:text-6xl lg:text-7xl font-display font-black text-slate-900 leading-tight">
                        Membangun Fondasi, <br>
                        <span class="text-indigo-600">Mengantar Solusi.</span>
                    </h1>
                    
                    <p class="text-lg text-slate-500 max-w-xl mx-auto lg:mx-0 leading-relaxed">
                        Solusi pengadaan material konstruksi Grade A untuk proyek pembangunan Anda. Kami menjamin kualitas material terbaik dengan sistem logistik yang presisi dan cepat.
                    </p>

                    <div class="flex flex-col sm:flex-row justify-center lg:justify-start gap-4 pt-4">
                        <a href="#produk" class="px-10 py-5 bg-indigo-600 text-white rounded-2xl font-bold shadow-xl shadow-indigo-100 hover:bg-indigo-700 transition-all transform hover:-translate-y-1">
                            Mulai Belanja Material
                        </a>
                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $no_whatsapp ?? '085877653585') }}" class="px-10 py-5 bg-white text-slate-900 border border-slate-200 rounded-2xl font-bold hover:bg-slate-50 transition-all">
                            Konsultasi WhatsApp
                        </a>
                    </div>

                    <!-- Trust Bar -->
                    <div class="flex flex-wrap justify-center lg:justify-start items-center gap-8 pt-10 border-t border-slate-100 mt-12">
                        <div class="text-center lg:text-left">
                            <p class="text-xl font-black text-slate-900 leading-none">10k+</p>
                            <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mt-1">Customers</p>
                        </div>
                        <div class="text-center lg:text-left">
                            <p class="text-xl font-black text-slate-900 leading-none">20+</p>
                            <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mt-1">Experience</p>
                        </div>
                        <div class="text-center lg:text-left">
                            <p class="text-xl font-black text-slate-900 leading-none">Jepara</p>
                            <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mt-1">Region Hub</p>
                        </div>
                    </div>
                </div>

                <!-- Visual (Ultra Clean) -->
                <div class="order-1 lg:order-2 relative px-10">
                    <!-- Decorative Circle -->
                    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full aspect-square bg-indigo-600/5 rounded-full -z-10 border border-indigo-600/10"></div>
                    
                    <div class="relative transform hover:scale-105 transition-transform duration-700 ease-out">
                        <img src="{{ asset('images/colt.png') }}" alt="BPTrans" class="w-full h-auto object-contain drop-shadow-[0_20px_50px_rgba(0,0,0,0.2)]">
                        
                        <!-- Mini Badges -->
                        <div class="absolute top-0 right-0 bg-white p-4 rounded-2xl shadow-xl border border-slate-50 hidden md:block">
                            <div class="flex items-center gap-3">
                                <div class="h-8 w-8 bg-emerald-500 text-white rounded-lg flex items-center justify-center">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                </div>
                                <span class="text-[10px] font-black text-slate-900 uppercase">Trusted Quality</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Services Section (Tentang Kami) -->
    <section id="tentang" class="py-32 bg-slate-50 relative overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-20 space-y-4">
                <p class="text-indigo-600 font-black text-xs uppercase tracking-[0.3em]">Mengapa Memilih Kami</p>
                <h2 class="text-4xl font-display font-black text-slate-900 leading-tight">Solusi Terbaik Untuk Kebutuhan Konstruksi Anda</h2>
                <div class="w-20 h-1.5 bg-indigo-600 mx-auto rounded-full"></div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Feature 1 -->
                <div class="group bg-white p-10 rounded-[2.5rem] border border-slate-100 shadow-sm hover:shadow-2xl hover:shadow-indigo-100 transition-all duration-500 hover:-translate-y-2">
                    <div class="h-16 w-16 bg-slate-50 text-indigo-600 rounded-2xl flex items-center justify-center mb-8 group-hover:bg-indigo-600 group-hover:text-white transition-all duration-500">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                    </div>
                    <h3 class="text-2xl font-black text-slate-900 mb-4">Material Terlengkap</h3>
                    <p class="text-slate-500 leading-relaxed">Dari pasir, batu pecah, hingga bata merah. Segala jenis material konstruksi tersedia dengan berbagai varian kualitas.</p>
                </div>

                <!-- Feature 2 -->
                <div class="group bg-white p-10 rounded-[2.5rem] border border-slate-100 shadow-sm hover:shadow-2xl hover:shadow-indigo-100 transition-all duration-500 hover:-translate-y-2">
                    <div class="h-16 w-16 bg-slate-50 text-emerald-600 rounded-2xl flex items-center justify-center mb-8 group-hover:bg-emerald-600 group-hover:text-white transition-all duration-500">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <h3 class="text-2xl font-black text-slate-900 mb-4">Harga Kompetitif</h3>
                    <p class="text-slate-500 leading-relaxed">Kami menjamin harga terbaik langsung dari sumbernya, memungkinkan efisiensi biaya proyek pembangunan Anda.</p>
                </div>

                <!-- Feature 3 -->
                <div class="group bg-white p-10 rounded-[2.5rem] border border-slate-100 shadow-sm hover:shadow-2xl hover:shadow-indigo-100 transition-all duration-500 hover:-translate-y-2">
                    <div class="h-16 w-16 bg-slate-50 text-amber-600 rounded-2xl flex items-center justify-center mb-8 group-hover:bg-amber-600 group-hover:text-white transition-all duration-500">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    </div>
                    <h3 class="text-2xl font-black text-slate-900 mb-4">Jangkauan Luas</h3>
                    <p class="text-slate-500 leading-relaxed">Armada kami siap mengirimkan pesanan Anda ke seluruh wilayah Jepara dan sekitarnya dengan tepat waktu.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Product Grid Section -->
    <section id="produk" class="py-32 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row md:items-end justify-between mb-16 gap-6">
                <div class="max-w-xl space-y-4">
                    <p class="text-indigo-600 font-black text-xs uppercase tracking-[0.3em]">Katalog Material</p>
                    <h2 class="text-4xl font-display font-black text-slate-900">Produk Unggulan Kami</h2>
                </div>
                <a href="{{ route('login.user') }}" class="inline-flex items-center gap-2 text-sm font-black text-indigo-600 uppercase tracking-widest hover:translate-x-2 transition-transform">
                    Lihat Semua Produk
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7l5 5m0 0l-5 5m5-5H6"></path></svg>
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">
                @forelse($products->take(6) as $product)
                    <div class="group bg-white rounded-[2.5rem] border border-slate-100 shadow-sm hover:shadow-2xl transition-all duration-500 overflow-hidden flex flex-col">
                        <div class="relative h-64 overflow-hidden bg-slate-50">
                            @if($product->gambar)
                                <img src="{{ asset('storage/' . $product->gambar) }}" alt="{{ $product->nama }}" class="w-full h-full object-cover group-hover:scale-110 transition duration-700">
                            @else
                                <div class="w-full h-full flex flex-col items-center justify-center text-slate-300">
                                    <svg class="w-16 h-16 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    <span class="text-[10px] font-black uppercase tracking-widest">No Image</span>
                                </div>
                            @endif
                            <div class="absolute bottom-4 right-4 glass px-4 py-2 rounded-xl border border-white/50">
                                <span class="text-indigo-600 font-black text-lg">Rp {{ number_format($product->harga, 0, ',', '.') }}</span>
                                <span class="text-slate-400 text-[10px] font-bold">/ {{ $product->satuan ?? 'rit' }}</span>
                            </div>
                        </div>
                        <div class="p-8 flex-1 flex flex-col">
                            <h3 class="text-xl font-black text-slate-900 mb-2 group-hover:text-indigo-600 transition-colors">{{ $product->nama }}</h3>
                            <p class="text-sm text-slate-500 line-clamp-2 mb-8 flex-1">{{ $product->deskripsi ?? 'Material konstruksi berkualitas tinggi untuk kebutuhan pembangunan Anda.' }}</p>
                            <a href="{{ route('login.user') }}" class="w-full py-4 bg-slate-900 text-white rounded-2xl font-bold text-center hover:bg-indigo-600 transition-all active:scale-95 shadow-lg shadow-slate-200">
                                Pesan Sekarang
                            </a>
                        </div>
                    </div>
                @empty
                    <!-- Placeholder cards if no products -->
                    <div class="col-span-full py-20 text-center">
                        <p class="text-slate-400 italic">Belum ada produk untuk ditampilkan.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer id="kontak" class="bg-slate-900 text-slate-400 pt-24 pb-12 overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-16 mb-20">
                <!-- Brand -->
                <div class="col-span-2 space-y-8">
                    <a href="#" class="flex items-center gap-3">
                        <div class="h-12 w-12 bg-white rounded-xl flex items-center justify-center overflow-hidden p-1">
                            <img src="{{ asset('images/colt.png') }}" alt="Logo" class="w-full h-full object-contain">
                        </div>
                        <span class="text-2xl font-display font-black text-white tracking-tight uppercase italic">BPTrans</span>
                    </a>
                    <p class="text-lg leading-relaxed max-w-md">Distributor bahan bangunan terpercaya di Jepara dengan pengalaman lebih dari 20 tahun melayani kebutuhan konstruksi lokal.</p>
                    <div class="flex gap-4">
                        <a href="#" class="h-10 w-10 rounded-lg bg-slate-800 flex items-center justify-center text-white hover:bg-indigo-600 transition-colors"><svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/></svg></a>
                        <a href="#" class="h-10 w-10 rounded-lg bg-slate-800 flex items-center justify-center text-white hover:bg-pink-600 transition-colors"><svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204 013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg></a>
                    </div>
                </div>

                <!-- Links -->
                <div>
                    <h4 class="text-white font-bold uppercase tracking-widest text-sm mb-8">Navigasi</h4>
                    <ul class="space-y-4">
                        <li><a href="#home" class="hover:text-white transition-colors">Beranda</a></li>
                        <li><a href="#produk" class="hover:text-white transition-colors">Katalog Produk</a></li>
                        <li><a href="#tentang" class="hover:text-white transition-colors">Tentang Kami</a></li>
                        <li><a href="{{ route('login.admin') }}" class="text-slate-600 hover:text-rose-500 transition-colors">Admin Login</a></li>
                    </ul>
                </div>

                <!-- Contact -->
                <div>
                    <h4 class="text-white font-bold uppercase tracking-widest text-sm mb-8">Kontak</h4>
                    <ul class="space-y-4">
                        <li class="flex items-start gap-3">
                            <span class="text-indigo-500">📍</span>
                            <span class="text-sm">Gemiring Kidul, Kec. Nalumsari, Kab. Jepara, Jawa Tengah</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <span class="text-indigo-500">📞</span>
                            <span class="text-sm">+62 858-7765-3585</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <span class="text-indigo-500">✉️</span>
                            <span class="text-sm">infobptrans@gmail.com</span>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="pt-8 border-t border-slate-800 flex flex-col md:flex-row items-center justify-between gap-4">
                <p class="text-xs">&copy; {{ date('Y') }} BPTrans Logistik & Material. Seluruh hak dilindungi.</p>
                <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-slate-600">Membangun Fondasi, Mengantar Solusi</p>
            </div>
        </div>
    </footer>

    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</body>
</html>