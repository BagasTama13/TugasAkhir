<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>BPTrans - Sistem Manajemen Pesanan</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <!-- Navigation -->
        <nav class="bg-gradient-to-r from-blue-600 to-blue-800 shadow-lg">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-20">
                    <!-- Logo -->
                    <div class="flex items-center">
                        <img src="{{ asset('images/colt.png') }}" alt="COLT Logo" class="h-12 w-12 object-contain mr-3">
                        <div>
                            <h1 class="text-white text-2xl font-bold">BPTrans</h1>
                            <p class="text-blue-100 text-xs">Admin Dashboard</p>
                        </div>
                    </div>

                    <!-- Login Button -->
                    <a href="{{ route('login') }}" class="bg-white hover:bg-gray-100 text-blue-600 font-semibold py-2 px-6 rounded-lg transition duration-200 shadow-md">
                        Login
                    </a>
                </div>
            </div>
        </nav>

        <!-- Hero Section -->
        <section class="bg-gradient-to-br from-blue-50 via-blue-100 to-blue-50 py-20">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
                    <!-- Left Content -->
                    <div>
                        <h2 class="text-4xl md:text-5xl font-bold text-gray-800 mb-6">
                            Sistem Manajemen <span class="text-blue-600">Pesanan & Pemasukan</span>
                        </h2>
                        <p class="text-xl text-gray-600 mb-8 leading-relaxed">
                            BPTrans Admin Dashboard adalah solusi lengkap untuk mengelola pesanan, pemasukan, produk, dan aktivitas bisnis Anda secara real-time dengan interface yang mudah digunakan.
                        </p>
                        <div class="flex gap-4">
                            <a href="{{ route('login') }}" class="bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-semibold py-3 px-8 rounded-lg transform transition duration-200 hover:scale-105 shadow-lg">
                                Masuk Sekarang
                            </a>
                            <a href="#features" class="bg-white hover:bg-gray-100 text-blue-600 font-semibold py-3 px-8 rounded-lg border-2 border-blue-600 transition duration-200">
                                Pelajari Lebih Lanjut
                            </a>
                        </div>
                    </div>

                    <!-- Right Image -->
                    <div class="flex justify-center">
                        <img src="{{ asset('images/colt.png') }}" alt="BPTrans Logo" class="w-64 h-64 object-contain drop-shadow-xl transform hover:scale-110 transition duration-300">
                    </div>
                </div>
            </div>
        </section>

        <!-- Products Section -->
        <section id="features" class="py-20 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-16">
                    <h3 class="text-4xl font-bold text-gray-800 mb-4">Daftar Produk</h3>
                    <p class="text-xl text-gray-600">Produk unggulan kami dengan harga terbaik</p>
                </div>

                @if ($products && $products->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                        @foreach ($products as $product)
                            <div class="bg-white border border-gray-200 p-6 rounded-xl shadow-lg hover:shadow-xl transform hover:scale-105 transition duration-300">
                                <!-- Product Image -->
                                <div class="mb-4 h-48 bg-gray-100 rounded-lg flex items-center justify-center overflow-hidden">
                                    @if ($product->gambar)
                                        <img src="{{ asset('storage/' . $product->gambar) }}" alt="{{ $product->nama }}" class="w-full h-full object-cover hover:scale-110 transition duration-300">
                                    @else
                                        <div class="text-gray-400 text-center">
                                            <svg class="w-16 h-16 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <p>Tidak ada gambar</p>
                        </div>
                    @endif
                </div>

                <!-- Product Name -->
                <h4 class="text-xl font-bold text-gray-800 mb-2">{{ $product->nama }}</h4>

                <!-- Product Price -->
                <div class="mb-3">
                    <span class="text-2xl font-bold text-blue-600">Rp {{ number_format($product->harga, 0, ',', '.') }}</span>
                    <span class="text-gray-600 ml-2">/ {{ $product->satuan ?? 'unit' }}</span>
                </div>

                <!-- Product Description -->
                <p class="text-gray-600 text-sm line-clamp-3">{{ $product->deskripsi ?? 'Deskripsi tidak tersedia' }}</p>
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

        <!-- Stats Section -->
        <section class="bg-gradient-to-r from-blue-600 to-blue-800 py-16">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-8 text-white text-center">
                    <div>
                        <h4 class="text-4xl font-bold mb-2">100%</h4>
                        <p class="text-blue-100">Uptime Guaranteed</p>
                    </div>
                    <div>
                        <h4 class="text-4xl font-bold mb-2">24/7</h4>
                        <p class="text-blue-100">Real-time Monitoring</p>
                    </div>
                    <div>
                        <h4 class="text-4xl font-bold mb-2">∞</h4>
                        <p class="text-blue-100">Scalable System</p>
                    </div>
                    <div>
                        <h4 class="text-4xl font-bold mb-2">∞</h4>
                        <p class="text-blue-100">Data Storage</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA Section -->
        <section class="bg-gray-50 py-20">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <h3 class="text-4xl font-bold text-gray-800 mb-6">Siap Memulai?</h3>
                <p class="text-xl text-gray-600 mb-10 max-w-2xl mx-auto">
                    Login ke dashboard admin kami sekarang dan mulai kelola pesanan, pemasukan, serta aktivitas bisnis Anda dengan lebih efisien.
                </p>
                <a href="{{ route('login') }}" class="inline-block bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-semibold py-4 px-12 rounded-lg transform transition duration-200 hover:scale-105 shadow-lg text-lg">
                    Login ke Dashboard
                </a>
            </div>
        </section>

        <!-- Footer -->
        <footer class="bg-gray-800 text-gray-300 py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-8">
                    <div>
                        <div class="flex items-center mb-4">
                            <img src="{{ asset('images/colt.png') }}" alt="COLT Logo" class="h-10 w-10 object-contain mr-2">
                            <h5 class="text-white text-lg font-bold">BPTrans</h5>
                        </div>
                        <p class="text-gray-400">Sistem Admin Dashboard untuk manajemen pesanan dan pemasukan yang terintegrasi.</p>
                    </div>
                    <div>
                        <h5 class="text-white font-bold mb-4">Fitur</h5>
                        <ul class="space-y-2 text-gray-400">
                            <li><a href="#" class="hover:text-white transition">Manajemen Pesanan</a></li>
                            <li><a href="#" class="hover:text-white transition">Tracking Pemasukan</a></li>
                            <li><a href="#" class="hover:text-white transition">Log Aktivitas</a></li>
                            <li><a href="#" class="hover:text-white transition">Dashboard</a></li>
                        </ul>
                    </div>
                    <div>
                        <h5 class="text-white font-bold mb-4">Kontak</h5>
                        <ul class="space-y-2 text-gray-400">
                            <li>Email: admin@bptrans.com</li>
                            <li>Phone: +62 123 456 789</li>
                            <li>Address: Jakarta, Indonesia</li>
                        </ul>
                    </div>
                </div>
                <div class="border-t border-gray-700 pt-8 text-center text-gray-400">
                    <p>&copy; 2026 BPTrans Admin Dashboard. All rights reserved.</p>
                </div>
            </div>
        </footer>
    </body>
</html>
                    