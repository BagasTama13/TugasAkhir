<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Hub Pelanggan | BPTrans Logistik</title>

    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    @livewireStyles
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="h-full text-slate-900 antialiased bg-slate-50">

    <div x-data="{ mobileMenu: false }" class="min-h-screen flex flex-col">
        <!-- Standard White Navbar -->
        <nav class="bg-white border-b border-slate-200 sticky top-0 z-40 shadow-sm">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex">
                        <div class="flex-shrink-0 flex items-center">
                            <a href="{{ route('user.dashboard') }}" class="flex items-center gap-2">
                                <img class="h-8 w-auto" src="{{ asset('images/colt.png') }}" alt="BPTrans">
                                <span class="text-xl font-bold tracking-tight text-slate-900">BPTRANS</span>
                            </a>
                        </div>
                        <div class="hidden sm:-my-px sm:ml-10 sm:flex sm:space-x-8">
                            <a href="{{ route('user.dashboard') }}" class="{{ request()->routeIs('user.dashboard') ? 'border-indigo-500 text-slate-900' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300' }} inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">Katalog Produk</a>
                            <a href="{{ route('user.pesanan') }}" class="{{ request()->routeIs('user.pesanan*') ? 'border-indigo-500 text-slate-900' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300' }} inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">Pesanan Saya</a>
                        </div>
                    </div>
                    <div class="hidden sm:ml-6 sm:flex sm:items-center space-x-4">
                        <div class="text-right mr-2">
                            <p class="text-sm font-semibold text-slate-900 leading-none">{{ auth()->user()->name }}</p>
                            <p class="text-xs text-slate-500 mt-1">Customer Hub</p>
                        </div>
                        <a href="{{ route('user.profile') }}" class="bg-indigo-600 p-2 rounded-full text-white hover:bg-indigo-700 transition-colors">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="text-sm font-medium text-rose-600 hover:text-rose-700">Sign Out</button>
                        </form>
                    </div>
                    <div class="-mr-2 flex items-center sm:hidden">
                        <button @click="mobileMenu = !mobileMenu" class="bg-white inline-flex items-center justify-center p-2 rounded-md text-slate-400 hover:text-slate-500 hover:bg-slate-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" /></svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Mobile Menu -->
            <div x-show="mobileMenu" x-cloak class="sm:hidden bg-white border-t border-slate-200">
                <div class="pt-2 pb-3 space-y-1">
                    <a href="{{ route('user.dashboard') }}" class="block pl-3 pr-4 py-2 border-l-4 text-base font-medium {{ request()->routeIs('user.dashboard') ? 'bg-indigo-50 border-indigo-500 text-indigo-700' : 'border-transparent text-slate-600 hover:bg-slate-50 hover:border-slate-300 hover:text-slate-800' }}">Katalog Produk</a>
                    <a href="{{ route('user.pesanan') }}" class="block pl-3 pr-4 py-2 border-l-4 text-base font-medium {{ request()->routeIs('user.pesanan*') ? 'bg-indigo-50 border-indigo-500 text-indigo-700' : 'border-transparent text-slate-600 hover:bg-slate-50 hover:border-slate-300 hover:text-slate-800' }}">Pesanan Saya</a>
                </div>
                <div class="pt-4 pb-3 border-t border-slate-200">
                    <div class="flex items-center px-4">
                        <div class="flex-shrink-0">
                            <div class="h-10 w-10 rounded-full bg-slate-200 flex items-center justify-center text-slate-600 font-bold">
                                {{ substr(auth()->user()->name, 0, 1) }}
                            </div>
                        </div>
                        <div class="ml-3">
                            <div class="text-base font-medium text-slate-800">{{ auth()->user()->name }}</div>
                            <div class="text-sm font-medium text-slate-500">{{ auth()->user()->email }}</div>
                        </div>
                    </div>
                    <div class="mt-3 space-y-1">
                        <a href="{{ route('user.profile') }}" class="block px-4 py-2 text-base font-medium text-slate-500 hover:text-slate-800 hover:bg-slate-100">Profil Saya</a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="block w-full text-left px-4 py-2 text-base font-medium text-rose-600 hover:text-rose-800 hover:bg-slate-100">Sign Out</button>
                        </form>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Main Content Area -->
        <main class="flex-1 py-10 px-4 sm:px-6 lg:px-8">
            <div class="max-w-7xl mx-auto">
                {{ $slot }}
            </div>
        </main>

        <!-- Standard Gray Footer -->
        <footer class="bg-white border-t border-slate-200 py-8">
            <div class="max-w-7xl mx-auto px-4 text-center">
                <p class="text-sm text-slate-500">BPTrans Logistik &mdash; Membangun Fondasi, Mengantar Solusi</p>
                <p class="text-xs text-slate-400 mt-2">&copy; {{ date('Y') }} BPTrans Jepara. Seluruh hak cipta dilindungi.</p>
            </div>
        </footer>
    </div>

    @livewireScripts
</body>
</html>
