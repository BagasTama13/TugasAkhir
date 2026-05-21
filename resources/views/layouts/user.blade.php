<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-50/50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Hub Pelanggan | BPTrans Logistik</title>

    <!-- Google Fonts: Outfit & Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&family=Plus+Jakarta+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,300;1,400;1,500;1,600;1,700;1,800&display=swap" rel="stylesheet">

    @livewireStyles
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="h-full text-slate-900 antialiased bg-slate-50/50 overflow-x-hidden selection:bg-indigo-500 selection:text-white">

    <div x-data="{ mobileMenu: false }" class="min-h-screen flex flex-col">
        <!-- Glassmorphism Sticky Navbar -->
        <nav class="glass-navbar sticky top-0 z-40 shadow-sm">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-20">
                    <div class="flex">
                        <div class="flex-shrink-0 flex items-center">
                            <a href="{{ route('user.dashboard') }}" class="flex items-center gap-3 group">
                                <div class="h-10 w-10 bg-slate-900 rounded-2xl flex items-center justify-center p-1.5 shadow-lg shadow-slate-900/10 group-hover:scale-105 transition-transform duration-300">
                                    <img src="{{ asset('images/colt.png') }}" alt="BPTrans" class="w-full h-full object-contain">
                                </div>
                                <span class="text-xl font-display font-black tracking-widest uppercase italic bg-clip-text text-transparent bg-gradient-to-r from-slate-900 to-slate-700">BPTRANS</span>
                            </a>
                        </div>
                        <div class="hidden sm:-my-px sm:ml-10 sm:flex sm:space-x-8">
                            <a href="{{ route('user.dashboard') }}" class="{{ request()->routeIs('user.dashboard') ? 'border-indigo-600 text-indigo-600' : 'border-transparent text-slate-500 hover:text-slate-900 hover:border-slate-300' }} inline-flex items-center px-1 pt-1 border-b-2 text-sm font-bold transition-all duration-300">Katalog Produk</a>
                            <a href="{{ route('user.pesanan') }}" class="{{ request()->routeIs('user.pesanan*') ? 'border-indigo-600 text-indigo-600' : 'border-transparent text-slate-500 hover:text-slate-900 hover:border-slate-300' }} inline-flex items-center px-1 pt-1 border-b-2 text-sm font-bold transition-all duration-300">Pesanan Saya</a>
                        </div>
                    </div>
                    <div class="hidden sm:ml-6 sm:flex sm:items-center space-x-6">
                        <div class="text-right">
                            <p class="text-sm font-bold text-slate-800 leading-none">{{ auth()->user()->name }}</p>
                            <span class="inline-block text-[10px] font-bold text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded-md mt-1 border border-indigo-100 uppercase tracking-wider">Customer Hub</span>
                        </div>
                        <a href="{{ route('user.profile') }}" class="bg-indigo-50 p-2.5 rounded-2xl text-indigo-600 hover:bg-indigo-600 hover:text-white transition-all duration-300 shadow-sm hover:shadow-indigo-100 hover:scale-105 border border-indigo-100/50">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                        </a>
                        <div class="h-6 w-px bg-slate-200"></div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="inline-flex items-center gap-1.5 text-sm font-bold text-rose-600 hover:text-rose-700 transition-colors bg-rose-50 hover:bg-rose-100/50 px-4 py-2 rounded-2xl border border-rose-100/30">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                                Keluar
                            </button>
                        </form>
                    </div>
                    <div class="-mr-2 flex items-center sm:hidden">
                        <button @click="mobileMenu = !mobileMenu" class="bg-white/60 backdrop-blur-md inline-flex items-center justify-center p-2.5 rounded-2xl text-slate-500 hover:text-slate-900 hover:bg-slate-100/80 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500 border border-slate-200/50 transition-all duration-300">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" /></svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Mobile Menu -->
            <div x-show="mobileMenu" x-cloak class="sm:hidden bg-white/95 backdrop-blur-md border-t border-slate-200/60 shadow-lg animate-in fade-in duration-200">
                <div class="pt-2 pb-3 space-y-1 px-4">
                    <a href="{{ route('user.dashboard') }}" class="flex items-center px-4 py-3 text-base font-bold rounded-2xl transition-all duration-300 {{ request()->routeIs('user.dashboard') ? 'bg-indigo-50 text-indigo-700 border-l-4 border-indigo-600' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">Katalog Produk</a>
                    <a href="{{ route('user.pesanan') }}" class="flex items-center px-4 py-3 text-base font-bold rounded-2xl transition-all duration-300 {{ request()->routeIs('user.pesanan*') ? 'bg-indigo-50 text-indigo-700 border-l-4 border-indigo-600' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">Pesanan Saya</a>
                </div>
                <div class="pt-4 pb-4 border-t border-slate-200/60 px-4">
                    <div class="flex items-center px-4 mb-4">
                        <div class="flex-shrink-0">
                            <div class="h-10 w-10 rounded-2xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white font-bold shadow-lg shadow-indigo-500/10">
                                {{ substr(auth()->user()->name, 0, 1) }}
                            </div>
                        </div>
                        <div class="ml-3">
                            <div class="text-base font-bold text-slate-800 leading-none">{{ auth()->user()->name }}</div>
                            <div class="text-xs text-slate-500 mt-1.5">{{ auth()->user()->email }}</div>
                        </div>
                    </div>
                    <div class="space-y-1.5 px-2">
                        <a href="{{ route('user.profile') }}" class="flex items-center gap-3 px-4 py-3 text-sm font-bold text-slate-600 hover:text-slate-900 hover:bg-slate-50 rounded-2xl transition-all duration-300">
                            <svg class="h-5 w-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                            Profil Saya
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="flex items-center gap-3 w-full text-left px-4 py-3 text-sm font-bold text-rose-600 hover:text-rose-800 hover:bg-rose-50 rounded-2xl transition-all duration-300">
                                <svg class="h-5 w-5 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                                Keluar (Sign Out)
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Main Content Area -->
        <main class="flex-1 py-10 px-4 sm:px-6 lg:px-8 bg-slate-50/50">
            <div class="max-w-7xl mx-auto animate-in fade-in slide-in-from-bottom-6 duration-500">
                {{ $slot }}
            </div>
        </main>

        <!-- Modern Premium Footer -->
        <footer class="bg-white border-t border-slate-200/60 py-10 mt-auto">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col items-center justify-between gap-6 sm:flex-row">
                    <div class="flex items-center gap-2">
                        <img class="h-6 w-auto" src="{{ asset('images/colt.png') }}" alt="BPTrans">
                        <span class="text-sm font-display font-extrabold tracking-wider text-slate-900 uppercase italic">BPTrans Jepara</span>
                    </div>
                    <p class="text-xs font-semibold text-slate-400">&copy; {{ date('Y') }} BPTrans Jepara. Seluruh hak cipta dilindungi.</p>
                </div>
                <div class="mt-6 border-t border-slate-100 pt-6 text-center">
                    <span class="inline-flex items-center gap-2 px-3 py-1 bg-indigo-50 text-indigo-700 text-[10px] font-bold rounded-full border border-indigo-100/50 uppercase tracking-widest">
                        <span class="h-1.5 w-1.5 rounded-full bg-indigo-500 animate-pulse"></span>
                        Membangun Fondasi, Mengantar Solusi
                    </span>
                </div>
            </div>
        </footer>
    </div>

    @livewireScripts
</body>
</html>
