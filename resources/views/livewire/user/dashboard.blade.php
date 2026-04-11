<div>
    <!-- Page Header -->
    <div class="mb-8 animate-fade-in-up">
        <h1 class="text-3xl font-extrabold text-gray-800 tracking-tight">Dashboard</h1>
        <p class="text-gray-500 mt-1">Selamat datang, <span class="font-semibold text-emerald-600">{{ auth()->user()->name }}</span>! Pilih produk untuk dipesan.</p>
    </div>

    <!-- Products Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-6">
        @forelse($this->products as $index => $product)
            <div class="user-card flex flex-col animate-fade-in-up animate-delay-{{ ($index % 3) + 1 }}">
                <!-- Product Image -->
                <div class="h-48 bg-gradient-to-br from-gray-50 to-gray-100 flex items-center justify-center overflow-hidden">
                    @if($product->gambar)
                        <img src="{{ asset('storage/' . $product->gambar) }}"
                             alt="{{ $product->nama }}"
                             class="w-full h-full object-cover hover:scale-110 transition-transform duration-500">
                    @else
                        <div class="text-center text-gray-400 p-4">
                            <svg class="w-16 h-16 mx-auto mb-2 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <p class="text-xs font-medium">No Image</p>
                        </div>
                    @endif
                </div>

                <!-- Product Info -->
                <div class="p-5 flex-1 flex flex-col">
                    <h3 class="font-bold text-gray-800 text-lg capitalize mb-1">{{ $product->nama }}</h3>
                    @if($product->jenis)
                        <span class="text-xs font-semibold text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded-full w-fit mb-3">{{ $product->jenis }}</span>
                    @endif

                    <p class="text-gray-500 text-sm flex-1 mb-3 line-clamp-3">{{ $product->deskripsi ?? 'Bahan bangunan berkualitas tinggi.' }}</p>

                    <div class="mb-4">
                        <span class="text-xl font-extrabold text-emerald-600">Rp {{ number_format($product->harga, 0, ',', '.') }}</span>
                        <span class="text-gray-400 text-sm"> / {{ $product->satuan ?? 'unit' }}</span>
                    </div>

                    <!-- Buy Button -->
                    <a href="{{ route('user.pesanan.detail', ['produk' => $product->id]) }}"
                       class="btn-primary text-center text-sm block">
                        🛒 Pesan Sekarang
                    </a>
                </div>
            </div>
        @empty
            <div class="col-span-full text-center py-16">
                <svg class="w-20 h-20 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
                <p class="text-gray-400 text-lg font-medium">Belum ada produk tersedia.</p>
            </div>
        @endforelse
    </div>
</div>
