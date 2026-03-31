<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>BPTrans - Bahan Bangunan Berkualitas Terbaik</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <!-- Navigation -->
        <nav class="bg-gradient-to-r from-blue-600 to-blue-800 shadow-lg sticky top-0 z-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-20">
                    <!-- Logo -->
                    <div class="flex items-center">
                        <img src="{{ asset('images/colt.png') }}" alt="BPTrans Logo" class="h-12 w-12 object-contain mr-3">
                        <div>
                            <h1 class="text-white text-2xl font-bold">BPTrans</h1>
                            <p class="text-blue-100 text-xs">Bahan Bangunan Terpercaya</p>
                        </div>
                    </div>

                    <!-- Contact Button -->
                    <div class="flex gap-3">
                        <a href="#kontak" class="bg-white hover:bg-gray-100 text-blue-600 font-semibold py-2 px-6 rounded-lg transition duration-200 shadow-md">
                            Hubungi Kami
                        </a>
                    </div>
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
                            Bahan Bangunan <span class="text-blue-600">Berkualitas Terbaik</span>
                        </h2>
                        <p class="text-xl text-gray-600 mb-4 leading-relaxed">
                            Kami menyediakan berbagai pilihan bahan bangunan premium dengan harga kompetitif di Jepara. Dari kayu pilihan, batu bata, hingga genteng berkualitas tinggi - semua tersedia untuk kebutuhan konstruksi Anda.
                        </p>
                        <p class="text-gray-600 mb-8">
                            Dipercaya oleh pelanggan setia di area Jepara dan sekitarnya untuk proyek bangunan residensial dan komersial dengan kualitas terjamin.
                        </p>
                        <div class="flex gap-4">
                            <a href="#produk" class="bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-semibold py-3 px-8 rounded-lg transform transition duration-200 hover:scale-105 shadow-lg">
                                Lihat Produk
                            </a>
                            <a href="#kontak" class="bg-white hover:bg-gray-100 text-blue-600 font-semibold py-3 px-8 rounded-lg border-2 border-blue-600 transition duration-200">
                                Pesan Sekarang
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

        <!-- About Section (Company Info) -->
        <section class="bg-white py-16">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <!-- About -->
                    <div class="bg-gray-50 p-6 rounded-lg">
                        <h3 class="text-xl font-bold text-gray-800 mb-3">Tentang BPTrans</h3>
                        <p class="text-gray-600 text-sm">Distributor bahan bangunan terkemuka di Jepara dengan pengalaman 20+ tahun melayani kebutuhan konstruksi lokal dengan komitmen kualitas dan harga terbaik untuk semua kalangan.</p>
                    </div>

                    <!-- Vision -->
                    <div class="bg-blue-50 p-6 rounded-lg border-l-4 border-blue-600">
                        <h3 class="text-xl font-bold text-gray-800 mb-3">Visi</h3>
                        <p class="text-gray-600 text-sm">Menjadi distributor bahan bangunan pilihan utama di Jepara dan sekitarnya yang terpercaya, inovatif, dan menyediakan produk berkualitas dengan harga kompetitif untuk semua kebutuhan konstruksi.</p>
                    </div>

                    <!-- Mission -->
                    <div class="bg-green-50 p-6 rounded-lg border-l-4 border-green-600">
                        <h3 class="text-xl font-bold text-gray-800 mb-3">Misi</h3>
                        <ul class="text-gray-600 text-sm space-y-1">
                            <li>✓ Produk berkualitas premium terjamin</li>
                            <li>✓ Harga kompetitif & terjangkau</li>
                            <li>✓ Layanan pelanggan 24/7 responsif</li>
                            <li>✓ Pengiriman tepat waktu ke seluruh wilayah</li>
                        </ul>
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

                                <!-- Product Jenis -->
                                @if ($product->jenis)
                                    <p class="text-sm text-blue-600 font-semibold mb-2">{{ $product->jenis }}</p>
                                @endif

                                <!-- Product Price -->
                                <div class="mb-3">
                                    <span class="text-2xl font-bold text-blue-600">Rp {{ number_format($product->harga, 0, ',', '.') }}</span>
                                    <span class="text-gray-600 ml-2">/ {{ $product->satuan ?? 'unit' }}</span>
                                </div>

                                <!-- Product Description -->
                                <p class="text-gray-600 text-sm line-clamp-3 mb-4">{{ $product->deskripsi ?? 'Deskripsi tidak tersedia' }}</p>

                                <!-- Order Button -->
                                <a href="#kontak" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg transition duration-200 text-center">
                                    Pesan Sekarang
                                </a>
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

        <!-- Why Choose Us Section -->
        <section class="bg-gradient-to-r from-blue-600 to-blue-800 py-16">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-8 text-white text-center">
                    <div>
                        <h4 class="text-4xl font-bold mb-2">20+</h4>
                        <p class="text-blue-100">Tahun Berpengalaman</p>
                    </div>
                    <div>
                        <h4 class="text-4xl font-bold mb-2">10K+</h4>
                        <p class="text-blue-100">Pelanggan Puas</p>
                    </div>
                    <div>
                        <h4 class="text-4xl font-bold mb-2">100%</h4>
                        <p class="text-blue-100">Produk Original</p>
                    </div>
                    <div>
                        <h4 class="text-4xl font-bold mb-2">24/7</h4>
                        <p class="text-blue-100">Layanan Pelanggan</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA Section -->
        <section id="kontak" class="bg-gray-50 py-20">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <h3 class="text-4xl font-bold text-gray-800 mb-6">Butuh Bahan Bangunan?</h3>
                <p class="text-xl text-gray-600 mb-10 max-w-2xl mx-auto">
                    Hubungi tim kami sekarang untuk mendapatkan penawaran terbaik dan konsultasi gratis. Berlokasi di Jepara, kami siap melayani kebutuhan bahan bangunan Anda dengan harga kompetitif dan kualitas terjamin.
                </p>
                <div class="flex flex-col md:flex-row gap-4 justify-center mb-6">
                    <a href="https://wa.me/+62123456789" target="_blank" class="inline-block bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white font-semibold py-4 px-12 rounded-lg transform transition duration-200 hover:scale-105 shadow-lg text-lg">
                        📱 WhatsApp: +62 123 456 789
                    </a>
                    <div class="inline-block bg-gradient-to-r from-blue-600 to-blue-700 text-white font-semibold py-4 px-12 rounded-lg shadow-lg text-lg">
                        📞 Telepon: +62 (021) 1234-5678
                    </div>
                </div>
                <p class="text-gray-600">
                    Email: <a href="mailto:info@bptrans.com" class="text-blue-600 hover:underline">info@bptrans.com</a>
                </p>
                <div class="mt-8">
                    <a href="https://maps.app.goo.gl/RgmK5rZsd5Ce3RVz7" target="_blank" class="inline-block bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white font-semibold py-3 px-10 rounded-lg transform transition duration-200 hover:scale-105 shadow-lg">
                        📍 Buka Lokasi Kami di Google Maps
                    </a>
                </div>
            </div>
        </section>

        <!-- Footer -->
        <footer class="bg-gray-800 text-gray-300 py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-8">
                    <!-- About -->
                    <div>i Jepara dan sekitarnya dengan pengalaman 20+ tahun melayani kebutuhan konstruksi lokal dengan dedikasi tinggi
                        <div class="flex items-center mb-4">
                            <img src="{{ asset('images/colt.png') }}" alt="BPTrans Logo" class="h-10 w-10 object-contain mr-2">
                            <h5 class="text-white text-lg font-bold">BPTrans</h5>
                        </div>
                        <p class="text-gray-400">Distributor bahan bangunan berkualitas terpercaya dengan pengalaman lebih dari 20 tahun melayani kebutuhan konstruksi di Indonesia.</p>
                    </div>

                    <!-- Products -->
                    <div>
                        <h5 class="text-white font-bold mb-4">Produk Kami</h5>
                        <ul class="space-y-2 text-gray-400">
                            <li><a href="#produk" class="hover:text-white transition">Batu Bata</a></li>
                            <li><a href="#produk" class="hover:text-white transition">Genteng</a></li>
                            <li><a href="#produk" class="hover:text-white transition">Kayu & Besi</a></li>
                            <li><a href="#produk" class="hover:text-white transition">Lihat Semua →</a></li>
                        </ul>
                    </div>

                    <!-- Contact -->
                    <div>
                        <h5 class="tepara, Jawa Tengah</li>
                            <li class="pt-2"><a href="https://maps.app.goo.gl/RgmK5rZsd5Ce3RVz7" target="_blank" class="text-blue-400 hover:text-white transition">→ Lihat di Google Maps</a>ld mb-4">Hubungi Kami</h5>
                        <ul class="space-y-2 text-gray-400">
                            <li>📧 Email: info@bptrans.com</li>
                            <li>📞 WhatsApp: +62 123 456 789</li>
                            <li>📱 Telepon: +62 (021) 1234-5678</li>
                            <li>📍 Jakarta, Indonesia</li>
                        </ul>
                    </div>
                </div>

                <div class="border-t border-gray-700 pt-8 text-center text-gray-400">
                    <p>© 2026 BPTrans. Semua hak dilindungi. | Distributor Bahan Bangunan Terpercaya</p>
                </div>
            </div>
        </footer>
    </body>
</html>
                    