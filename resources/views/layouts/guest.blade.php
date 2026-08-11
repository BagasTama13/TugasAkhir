<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="robots" content="noindex, nofollow">
        <meta name="description" content="BPTrans — Login atau daftar untuk mengakses layanan pemesanan material konstruksi premium.">

        <title>{{ config('app.name', 'BPTrans') }} — Login</title>
        <meta name="description" content="Masuk ke sistem BPTrans — platform manajemen pesanan dan logistik material bangunan.">

        {{-- DNS Prefetch --}}
        <link rel="dns-prefetch" href="//fonts.bunny.net">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link rel="preload" as="style" href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" />
        <link rel="stylesheet" href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" media="print" onload="this.media='all'" />
        <noscript><link rel="stylesheet" href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" /></noscript>

        <!-- Preload Logo Image -->
        <link rel="preload" href="{{ asset('images/colt.webp') }}" as="image" fetchpriority="high">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="min-h-screen flex flex-col justify-center lg:flex-row lg:items-center py-10 bg-gradient-to-br from-blue-50 via-blue-100 to-blue-50 px-4 gap-8">
            <!-- Form Card -->
            <div class="w-full sm:max-w-md px-6 py-8 bg-white shadow-2xl overflow-hidden sm:rounded-2xl border-t-4 border-blue-600">
                {{ $slot }}
            </div>


        </div>
        
        <!-- Footer -->
        <div class="py-6 w-full text-center bg-transparent">
            <p class="text-xs font-semibold text-gray-500">
                &copy; {{ date('Y') }} BPTrans Logistik & Material. Developed by <a href="https://github.com/BagasTama13" target="_blank" rel="noopener noreferrer" class="text-blue-600 hover:text-blue-500 transition-colors">BagasTama13</a>.
            </p>
        </div>


    </body>
</html>
