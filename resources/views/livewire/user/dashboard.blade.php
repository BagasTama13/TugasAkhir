<div>
    <!-- Page Header -->
    <div class="mb-10 animate-fade-in-up">
        <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">Katalog Produk</h1>
        <p class="text-gray-500 mt-2">Selamat datang, <span class="font-bold text-blue-600">{{ auth()->user()->name }}</span>! Temukan bahan bangunan berkualitas untuk proyek Anda.</p>
    </div>

    <!-- Products Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 2xl:grid-cols-5 gap-8">
        @forelse($this->products as $index => $product)
            <div class="group bg-white rounded-[2rem] border border-gray-100 shadow-xl shadow-gray-200/50 hover:shadow-2xl hover:shadow-blue-200/50 transition-all duration-500 overflow-hidden flex flex-col animate-fade-in-up" style="animation-delay: {{ $index * 0.05 }}s">
                
                <!-- Product Picture -->
                <div class="relative h-48 bg-gray-50 overflow-hidden">
                    @if($product->gambar)
                        <img src="{{ asset('storage/' . $product->gambar) }}"
                             alt="{{ $product->nama }}"
                             class="w-full h-full object-cover group-hover:scale-110 transition duration-700">
                    @else
                        <div class="w-full h-full flex flex-col items-center justify-center text-gray-300">
                            <svg class="w-12 h-12 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                    @endif

                    <!-- Price Badge -->
                    <div class="absolute bottom-3 right-3 bg-white/90 backdrop-blur-md px-3 py-1.5 rounded-xl shadow-lg border border-white/50">
                        <span class="text-blue-600 font-black text-sm">Rp {{ number_format($product->harga, 0, ',', '.') }}</span>
                        <span class="text-gray-400 text-[9px] font-bold uppercase">/ {{ $product->satuan ?? 'unit' }}</span>
                    </div>
                </div>

                <!-- Product Content -->
                <div class="p-6 flex-1 flex flex-col">
                    <h3 class="text-xl font-black text-gray-900 mb-2 group-hover:text-blue-600 transition-colors duration-300 capitalize">{{ $product->nama }}</h3>
                    
                    <p class="text-gray-500 text-xs leading-relaxed mb-6 flex-1 line-clamp-3">
                        {{ $product->deskripsi ?? 'Detail produk tidak tersedia untuk saat ini. Silakan hubungi admin untuk informasi lebih lanjut.' }}
                    </p>

                    <!-- Buy Button -->
                    <a href="{{ route('user.pesanan.detail', ['produk' => $product->id]) }}"
                       class="inline-flex items-center justify-center w-full py-3 bg-gray-900 text-white font-bold rounded-2xl hover:bg-blue-600 transform hover:-translate-y-1 transition-all duration-300 gap-2 group/btn shadow-lg shadow-gray-100">
                        Pesan Sekarang
                        <svg class="w-4 h-4 group-hover/btn:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                        </svg>
                    </a>
                </div>

            </div>
        @empty
            <div class="col-span-full text-center py-20 bg-white rounded-[2rem] border border-gray-100 shadow-sm">
                <div class="h-20 w-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-gray-900">Belum Ada Produk</h3>
                <p class="text-sm text-gray-500 mt-1">Stok produk sedang diperbarui. Silakan kembali lagi nanti.</p>
            </div>
        @endforelse
    </div>
</div>

<style>
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in-up {
        animation: fadeInUp 0.6s ease-out forwards;
    }
</style>
