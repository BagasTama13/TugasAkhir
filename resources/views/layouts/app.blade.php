<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
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

    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    @livewireStyles
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        [x-cloak] { display: none !important; }
        .nav-link-active {
            @apply bg-indigo-600 text-white !important;
        }
    </style>
</head>
<body class="h-full text-slate-900 antialiased overflow-hidden">

    <div x-data="{ sidebarOpen: false }" class="flex h-screen overflow-hidden">
        
        @php
            $isOwner = request()->segment(1) === 'owner';
            $isWorker = request()->segment(1) === 'worker';
            $panelPrefix = $isOwner ? '/owner/'.request()->segment(2) : ($isWorker ? '/worker/'.request()->segment(2) : '');
            
            $navItems = [
                ['name' => 'Dashboard', 'icon' => 'M4 6h16M4 12h16M4 18h16', 'link' => $panelPrefix.'/dashboard', 'active' => request()->is($panelPrefix ? ltrim($panelPrefix.'/*', '/') : 'dashboard')],
                ['name' => 'Daftar Pesanan', 'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2', 'link' => $panelPrefix.'/pesanan', 'active' => request()->is('*/pesanan*')],
            ];

            if ($isOwner) {
                $navItems[] = ['name' => 'Laporan Pemasukan', 'icon' => 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z', 'link' => $panelPrefix.'/pemasukan', 'active' => request()->is('*/pemasukan*')];
            }

            if (!$isWorker) {
                $navItems[] = ['name' => 'Manajemen Etalase', 'icon' => 'M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10', 'link' => $panelPrefix.'/etalase', 'active' => request()->is('*/etalase*')];
            }

            $navItems[] = ['name' => 'Log Aktivitas', 'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z', 'link' => $panelPrefix.'/activity', 'active' => request()->is('*/activity*')];
        @endphp

        <!-- Mobile Sidebar -->
        <div x-show="sidebarOpen" x-cloak class="fixed inset-0 flex z-40 md:hidden">
            <div @click="sidebarOpen = false" class="fixed inset-0 bg-slate-600 bg-opacity-75 transition-opacity"></div>
            <div class="relative flex-1 flex flex-col max-w-xs w-full bg-slate-900 transition duration-300 transform">
                <div class="absolute top-0 right-0 -mr-12 pt-2">
                    <button @click="sidebarOpen = false" class="ml-1 flex items-center justify-center h-10 w-10 rounded-full focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white">
                        <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>
                <div class="flex-1 h-0 pt-5 pb-4 overflow-y-auto">
                    <div class="flex-shrink-0 flex items-center px-4">
                        <img class="h-8 w-auto" src="{{ asset('images/colt.png') }}" alt="BPTrans">
                        <span class="ml-3 text-white text-lg font-bold tracking-tight">BPTRANS</span>
                    </div>
                    <nav class="mt-5 px-2 space-y-1">
                        @foreach($navItems as $item)
                            <a href="{{ $item['link'] }}" class="{{ $item['active'] ? 'bg-slate-800 text-white' : 'text-slate-300 hover:bg-slate-700 hover:text-white' }} group flex items-center px-2 py-2 text-base font-medium rounded-md">
                                <svg class="mr-4 h-6 w-6 text-slate-400 group-hover:text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $item['icon'] }}" /></svg>
                                {{ $item['name'] }}
                            </a>
                        @endforeach
                    </nav>
                </div>
            </div>
        </div>

        <!-- Desktop Sidebar -->
        <aside class="hidden md:flex md:flex-shrink-0">
            <div class="flex flex-col w-64 bg-slate-900">
                <div class="flex flex-col h-0 flex-1">
                    <div class="flex items-center h-16 flex-shrink-0 px-4 bg-slate-900 border-b border-slate-800">
                        <img class="h-8 w-auto" src="{{ asset('images/colt.png') }}" alt="BPTrans">
                        <span class="ml-3 text-white text-lg font-bold tracking-tight">BPTRANS</span>
                    </div>
                    <nav class="flex-1 px-2 py-4 space-y-1 overflow-y-auto">
                        @foreach($navItems as $item)
                            <a href="{{ $item['link'] }}" class="{{ $item['active'] ? 'bg-indigo-600 text-white shadow-sm' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }} group flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-all">
                                <svg class="mr-3 h-5 w-5 {{ $item['active'] ? 'text-white' : 'text-slate-500 group-hover:text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $item['icon'] }}" /></svg>
                                {{ $item['name'] }}
                            </a>
                        @endforeach
                    </nav>
                    <div class="flex-shrink-0 flex bg-slate-800 p-4">
                        <div class="flex items-center">
                            <div class="inline-block h-9 w-9 rounded-full overflow-hidden bg-slate-700 flex items-center justify-center text-white font-bold">
                                {{ substr(auth()->user()->name, 0, 1) }}
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-white">{{ auth()->user()->name }}</p>
                                <p class="text-xs font-medium text-slate-400 group-hover:text-slate-300">Operational</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Main Content Area -->
        <div class="flex flex-col w-0 flex-1 overflow-hidden">
            <header class="relative z-10 flex-shrink-0 flex h-16 bg-white shadow-sm border-b border-slate-200">
                <button @click="sidebarOpen = true" class="px-4 border-r border-slate-200 text-slate-500 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500 md:hidden">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" /></svg>
                </button>
                <div class="flex-1 px-4 flex justify-between">
                    <div class="flex-1 flex items-center">
                        <span class="text-sm font-medium text-slate-500 italic">Membangun Fondasi, Mengantar Solusi</span>
                    </div>
                    <div class="ml-4 flex items-center md:ml-6 space-x-4">
                        <div class="text-sm text-slate-500 font-medium">
                            {{ now()->translatedFormat('l, d F Y') }}
                        </div>
                        <div class="h-6 w-px bg-slate-200"></div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="text-sm font-medium text-rose-600 hover:text-rose-700 focus:outline-none">
                                Sign Out
                            </button>
                        </form>
                    </div>
                </div>
            </header>

            <main class="flex-1 relative overflow-y-auto focus:outline-none bg-slate-50 p-6">
                <div class="max-w-7xl mx-auto">
                    {{ $slot }}
                </div>
            </main>
        </div>
    </div>

    @livewireScripts
</body>
</html>