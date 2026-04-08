<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>
        @php
            $segment = request()->segment(1);
            if ($segment === 'owner') {
                echo 'BPTrans Owner';
            } elseif ($segment === 'worker') {
                echo 'BPTrans Worker';
            } else {
                echo 'BPTrans Admin';
            }
        @endphp
    </title>

@livewireStyles
@vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 font-sans h-screen overflow-hidden">

<!-- Navigation Bar -->
<div class="fixed top-0 left-0 right-0 bg-white shadow-sm h-16 z-50 flex items-center justify-between px-8">
    <div class="text-xl font-bold text-gray-900">
        @php
            $segment = request()->segment(1);
            if ($segment === 'owner') {
                echo 'BPTrans Owner';
            } elseif ($segment === 'worker') {
                echo 'BPTrans Worker';
            } else {
                echo 'BPTrans Admin';
            }
        @endphp
    </div>
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
    @php
        $isOwner = request()->segment(1) === 'owner';
        $isWorker = request()->segment(1) === 'worker';
        $panelPrefix = $isOwner ? '/owner/'.request()->segment(2) : ($isWorker ? '/worker/'.request()->segment(2) : '');
    @endphp

    <div class="w-64 bg-gray-900 text-gray-300 flex flex-col fixed left-0 top-16 h-[calc(100vh-64px)] overflow-y-auto">

        <div class="flex-1 p-4 space-y-2">

            <a href="{{ $panelPrefix }}/dashboard"
               class="flex items-center px-4 py-2 rounded-lg transition 
               {{ request()->is($panelPrefix ? ($isOwner ? 'owner/*/dashboard' : ($isWorker ? 'worker/*/dashboard' : 'dashboard')) : 'dashboard') ? 'bg-blue-500 text-white' : 'hover:bg-gray-800' }}">
                📊 <span class="ml-3">Dashboard</span>
            </a>

            <a href="{{ $panelPrefix }}/pesanan"
               class="flex items-center px-4 py-2 rounded-lg transition 
               {{ request()->is($panelPrefix ? ($isOwner ? 'owner/*/pesanan' : ($isWorker ? 'worker/*/pesanan' : 'pesanan')) : 'pesanan') ? 'bg-blue-500 text-white' : 'hover:bg-gray-800' }}">
                📦 <span class="ml-3">Pesanan</span>
            </a>

            @if($isOwner)
                <a href="{{ $panelPrefix }}/pemasukan"
                   class="flex items-center px-4 py-2 rounded-lg transition 
                   {{ request()->is('owner/*/pemasukan') ? 'bg-blue-500 text-white' : 'hover:bg-gray-800' }}">
                    💰 <span class="ml-3">Pemasukan</span>
                </a>
            @endif

            @if(!$isWorker)
                <a href="{{ $panelPrefix }}/etalase"
                   class="flex items-center px-4 py-2 rounded-lg transition 
                   {{ request()->is($panelPrefix ? ($isOwner ? 'owner/*/etalase' : 'etalase') : 'etalase') ? 'bg-blue-500 text-white' : 'hover:bg-gray-800' }}">
                    🏪 <span class="ml-3">Etalase</span>
                </a>
            @endif

            <a href="{{ $panelPrefix }}/activity"
               class="flex items-center px-4 py-2 rounded-lg transition 
               {{ request()->is($panelPrefix ? ($isOwner ? 'owner/*/activity' : ($isWorker ? 'worker/*/activity' : 'activity')) : 'activity') ? 'bg-blue-500 text-white' : 'hover:bg-gray-800' }}">
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

<!-- Auto reload script for development -->
@if(app()->environment('local'))
<script>
    // Auto reload when files change (for development)
    if (typeof EventSource !== 'undefined') {
        const evtSource = new EventSource('http://localhost:5174/');
        evtSource.onmessage = function(event) {
            if (event.data === 'connected') {
                console.log('🔄 Auto-reload connected');
            }
        };
        evtSource.addEventListener('reload', function(event) {
            console.log('🔄 Reloading page due to file changes...');
            window.location.reload();
        });
        evtSource.onerror = function() {
            // Fallback: check for changes every 2 seconds
            setInterval(() => {
                fetch(window.location.href, { method: 'HEAD' })
                    .then(response => {
                        if (response.status !== 200) {
                            window.location.reload();
                        }
                    })
                    .catch(() => {
                        // Ignore errors in development
                    });
            }, 2000);
        };
    }
</script>
@endif

</body>
</html>