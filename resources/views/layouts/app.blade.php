<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>BPTrans Admin</title>

@livewireStyles
@vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 font-sans h-screen overflow-hidden">

<!-- Navigation Bar -->
<div class="fixed top-0 left-0 right-0 bg-white shadow-sm h-16 z-50 flex items-center justify-between px-8">
    <div class="text-xl font-bold text-gray-900">BPTrans Admin</div>
    <div class="flex items-center gap-4">
        <span class="text-sm text-gray-600">Selamat datang, <strong>{{ auth()->user()->name }}</strong></span>
        <form method="POST" action="{{ route('logout') }}" class="inline">
            @csrf
            <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition text-sm font-medium">
                Logout
            </button>
        </form>
    </div>
</div>

<div class="flex h-screen pt-16">

    <!-- Sidebar Fixed -->
    <div class="w-64 bg-gray-900 text-gray-300 flex flex-col fixed left-0 top-16 h-[calc(100vh-64px)] overflow-y-auto">

        <div class="flex-1 p-4 space-y-2">

            <a href="/dashboard"
               class="flex items-center px-4 py-2 rounded-lg transition 
               {{ request()->is('dashboard') ? 'bg-blue-500 text-white' : 'hover:bg-gray-800' }}">
                📊 <span class="ml-3">Dashboard</span>
            </a>

            <a href="/pesanan"
               class="flex items-center px-4 py-2 rounded-lg transition 
               {{ request()->is('pesanan') ? 'bg-blue-500 text-white' : 'hover:bg-gray-800' }}">
                📦 <span class="ml-3">Pesanan</span>
            </a>

            <a href="/pemasukan"
               class="flex items-center px-4 py-2 rounded-lg transition 
               {{ request()->is('pemasukan') ? 'bg-blue-500 text-white' : 'hover:bg-gray-800' }}">
                💰 <span class="ml-3">Pemasukan</span>
            </a>

            <a href="/etalase"
               class="flex items-center px-4 py-2 rounded-lg transition 
               {{ request()->is('etalase') ? 'bg-blue-500 text-white' : 'hover:bg-gray-800' }}">
                🏪 <span class="ml-3">Etalase</span>
            </a>

            <a href="/activity"
               class="flex items-center px-4 py-2 rounded-lg transition 
               {{ request()->is('activity') ? 'bg-blue-500 text-white' : 'hover:bg-gray-800' }}">
                📋 <span class="ml-3">Activity</span>
            </a>

        </div>

        <div class="p-4 text-xs text-gray-500 border-t border-gray-800">
            © 2026 BPTrans
        </div>

    </div>

    <!-- Content dengan scroll -->
    <div class="flex-1 ml-64 overflow-y-auto">
        <div class="p-8">
            {{ $slot }}
        </div>
    </div>

</div>

@livewireScripts

</body>
</html>