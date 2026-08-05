<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-50/50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    {{-- Panel internal: tidak perlu diindeks search engine --}}
    <meta name="robots" content="noindex, nofollow">
    <title>
        @php
            $segment = request()->segment(1);
            if ($segment === 'owner') {
                echo 'Owner Portal | BPTrans';
            } elseif ($segment === 'worker') {
                echo 'Worker Portal | BPTrans';
            } else {
                echo 'Admin Control | BPTrans';
            }
        @endphp
    </title>
    @php
        $segment = request()->segment(1);
        if ($segment === 'owner') {
            $metaDesc = 'Portal Owner BPTrans — pantau laporan keuangan, manajemen pegawai, dan rekap pesanan secara real-time.';
        } elseif ($segment === 'worker') {
            $metaDesc = 'Portal Worker BPTrans — kelola dan perbarui status pengiriman pesanan.';
        } else {
            $metaDesc = 'Panel Admin BPTrans — manajemen pesanan, etalase produk, dan laporan pemasukan bisnis logistik.';
        }
    @endphp
    <meta name="description" content="{{ $metaDesc }}">

    {{-- DNS Prefetch untuk mempercepat koneksi ke domain eksternal --}}
    <link rel="dns-prefetch" href="//fonts.googleapis.com">
    <link rel="dns-prefetch" href="//fonts.gstatic.com">

    {{-- Google Fonts dengan font-display=swap agar teks tetap tampil saat font dimuat --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&family=Plus+Jakarta+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,300;1,400;1,500;1,600;1,700;1,800&display=swap&font-display=swap" rel="stylesheet">

    @livewireStyles
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('head')

    <style>
        [x-cloak] { display: none !important; }
        .nav-link-active {
            @apply bg-indigo-600 text-white !important;
        }
    </style>
</head>
<body class="h-full text-slate-900 antialiased overflow-hidden overflow-x-hidden selection:bg-indigo-500 selection:text-white">

    <div x-data="{ sidebarOpen: false }" class="flex h-screen overflow-hidden">
        
        @php
            $isOwner = request()->segment(1) === 'owner';
            $isWorker = request()->segment(1) === 'worker';
            $panelPrefix = $isOwner ? '/owner/'.request()->segment(2) : ($isWorker ? '/worker/'.request()->segment(2) : '');
            
            $navItems = [
                ['name' => 'Dashboard', 'icon' => 'M4 6h16M4 12h16M4 18h16', 'link' => $panelPrefix.'/dashboard', 'active' => request()->is($panelPrefix ? ltrim($panelPrefix.'/*', '/') : 'dashboard')],
                ['name' => 'Daftar Pesanan', 'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2', 'link' => $panelPrefix.'/pesanan', 'active' => request()->is('*/pesanan*')],
            ];

            if (!$isWorker) {
                $navItems[] = ['name' => 'Laporan Pemasukan', 'icon' => 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z', 'link' => $panelPrefix.'/pemasukan', 'active' => request()->is('*/pemasukan*')];
            }

            if (!$isWorker) {
                $navItems[] = ['name' => 'Manajemen Etalase', 'icon' => 'M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10', 'link' => $panelPrefix.'/etalase', 'active' => request()->is('*/etalase*')];
            }
            
            if ($isOwner) {
                $navItems[] = ['name' => 'Manajemen Pegawai', 'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z', 'link' => $panelPrefix.'/pegawai', 'active' => request()->is('*/pegawai*')];
            }

            $navItems[] = ['name' => 'Log Aktivitas', 'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z', 'link' => $panelPrefix.'/activity', 'active' => request()->is('*/activity*')];
        @endphp

        <!-- Mobile Sidebar -->
        <div x-show="sidebarOpen" x-cloak class="fixed inset-0 flex z-40 md:hidden">
            <div @click="sidebarOpen = false" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity duration-300"></div>
            <div class="relative flex-1 flex flex-col max-w-xs w-full bg-slate-950 transition duration-300 transform">
                <div class="absolute top-0 right-0 -mr-12 pt-2">
                    <button @click="sidebarOpen = false" class="ml-1 flex items-center justify-center h-10 w-10 rounded-full focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white">
                        <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>
                <div class="flex-1 h-0 pt-5 pb-4 overflow-y-auto">
                    <div class="flex-shrink-0 flex items-center px-6">
                        <img class="h-9 w-auto" src="{{ asset('images/colt.png') }}" alt="BPTrans">
                        <span class="ml-3 text-white text-xl font-display font-black tracking-widest uppercase italic">BPTRANS</span>
                    </div>
                    <nav class="mt-6 px-4 space-y-1.5">
                        @foreach($navItems as $item)
                            <a href="{{ $item['link'] }}" class="{{ $item['active'] ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/30' : 'text-slate-400 hover:bg-slate-900 hover:text-white' }} group flex items-center px-4 py-3 text-sm font-semibold rounded-2xl transition-all duration-300">
                                <svg class="mr-4 h-5 w-5 {{ $item['active'] ? 'text-white' : 'text-slate-500 group-hover:text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $item['icon'] }}" /></svg>
                                {{ $item['name'] }}
                            </a>
                        @endforeach
                    </nav>
                </div>
            </div>
        </div>

        <!-- Desktop Sidebar -->
        <aside class="hidden md:flex md:flex-shrink-0">
            <div class="flex flex-col w-68 bg-slate-950 border-r border-slate-900">
                <div class="flex flex-col h-0 flex-1">
                    <div class="flex items-center h-20 flex-shrink-0 px-6 border-b border-slate-900 bg-slate-950">
                        <div class="h-10 w-10 bg-white rounded-2xl flex items-center justify-center p-1.5 shadow-lg shadow-white/5">
                            <img src="{{ asset('images/colt.png') }}" alt="BPTrans" class="w-full h-full object-contain">
                        </div>
                        <span class="ml-3 text-white text-xl font-display font-black tracking-widest uppercase italic bg-clip-text text-transparent bg-gradient-to-r from-white to-slate-400">BPTRANS</span>
                    </div>
                    <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto scrollbar-hide">
                        @foreach($navItems as $item)
                            <a href="{{ $item['link'] }}" class="{{ $item['active'] ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/30 scale-[1.02] premium-glow-active' : 'text-slate-400 hover:bg-slate-900/60 hover:text-white hover:scale-[1.01] active:scale-95' }} group flex items-center px-4 py-3.5 text-sm font-bold rounded-2xl transition-all duration-300">
                                <svg class="mr-3 h-5 w-5 {{ $item['active'] ? 'text-white' : 'text-slate-500 group-hover:text-slate-400' }} transition-colors duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $item['icon'] }}" /></svg>
                                {{ $item['name'] }}
                            </a>
                        @endforeach
                    </nav>
                    <div class="flex-shrink-0 flex bg-slate-900/30 border-t border-slate-900 p-5">
                        <div class="flex items-center">
                            <div class="inline-block h-10 w-10 rounded-2xl overflow-hidden bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white font-bold shadow-lg shadow-indigo-500/10">
                                {{ substr(auth()->user()->name, 0, 1) }}
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-bold text-white leading-none">{{ auth()->user()->name }}</p>
                                <span class="inline-block text-[10px] font-bold text-indigo-400 bg-indigo-500/10 px-2 py-0.5 rounded-md mt-1.5 border border-indigo-500/20 uppercase tracking-wider">
                                    {{ $isOwner ? 'Owner' : ($isWorker ? 'Worker' : 'Administrator') }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Main Content Area -->
        <div class="flex flex-col w-0 flex-1 overflow-hidden">
            <header class="relative z-10 flex-shrink-0 flex h-20 bg-white/80 backdrop-blur-md border-b border-slate-200/60 shadow-sm">
                <button @click="sidebarOpen = true" class="px-6 border-r border-slate-200 text-slate-500 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500 md:hidden">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" /></svg>
                </button>
                <div class="flex-1 px-6 flex justify-between">
                    <div class="flex-1 flex items-center">
                        <span class="inline-flex items-center gap-2 px-3 py-1 bg-indigo-50 text-indigo-700 text-xs font-semibold rounded-full border border-indigo-100/50">
                            <span class="h-1.5 w-1.5 rounded-full bg-indigo-500 animate-pulse"></span>
                            Suplai Material Premium, Solusi Logistik Terpadu
                        </span>
                    </div>
                    <div class="ml-4 flex items-center md:ml-6 space-x-6">
                        <div class="text-sm font-bold text-slate-700 flex items-center gap-2 bg-slate-50 px-4 py-2 rounded-2xl border border-slate-100">
                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            {{ now()->translatedFormat('l, d F Y') }}
                        </div>
                        <div class="h-6 w-px bg-slate-200"></div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="inline-flex items-center gap-2 text-sm font-bold text-rose-600 hover:text-rose-700 transition-colors bg-rose-50 hover:bg-rose-100/50 px-4 py-2 rounded-2xl border border-rose-100/30">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                                Keluar
                            </button>
                        </form>
                    </div>
                </div>
            </header>

            <main class="flex-1 relative overflow-y-auto focus:outline-none bg-slate-50/50 p-8">
                <div class="max-w-7xl mx-auto animate-in fade-in slide-in-from-bottom-6 duration-500 flex flex-col min-h-[calc(100vh-8rem)]">
                    <div class="flex-1">
                        {{ $slot }}
                    </div>
                    
                    <footer class="mt-auto border-t border-slate-200/60 pt-6 pb-2">
                        <div class="flex items-center justify-center text-center">
                            <p class="text-xs font-semibold text-slate-400">
                                &copy; {{ date('Y') }} BPTrans Logistik & Material. Developed by <a href="https://github.com/BagasTama13" target="_blank" rel="noopener noreferrer" class="text-indigo-600 hover:text-indigo-500 transition-colors">BagasTama13</a>.
                            </p>
                        </div>
                    </footer>
                </div>
            </main>
        </div>
    </div>

    @livewireScripts
</body>
</html>