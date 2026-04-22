<div>
    <!-- Page Header -->
    <div class="mb-8 animate-fade-in-up">
        <h1 class="text-3xl font-extrabold text-gray-800 tracking-tight">Dashboard</h1>
        <p class="text-gray-500 mt-1">Selamat datang, <span class="font-semibold text-emerald-600">{{ auth()->user()->name }}</span>! Pilih produk untuk dipesan.</p>
    </div>

    <!-- Products Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-6">
        @forelse($this->products as $index => $product)
            <div class="bg-gray-200 p-4 rounded-xl flex flex-col animate-fade-in-up animate-delay-{{ ($index % 3) + 1 }} shadow-sm border border-gray-300">
                
                <!-- Product Picture -->
                <div class="bg-gray-100 rounded-lg h-40 border border-gray-300 flex items-center justify-center overflow-hidden mb-4">
                    @if($product->gambar)
                        <img src="{{ asset('storage/' . $product->gambar) }}"
                             alt="{{ $product->nama }}"
                             class="w-full h-full object-cover">
                    @else
                        <span class="text-gray-500 font-medium text-sm">Product Picture</span>
                    @endif
                </div>

                <!-- Product Description -->
                <div class="bg-gray-100 rounded-lg p-4 border border-gray-300 flex-1 flex flex-col justify-center items-center text-center mb-4 min-h-[160px]">
                    <h3 class="font-bold text-gray-800 text-lg capitalize mb-1">{{ $product->nama }}</h3>
                    @if($product->jenis)
                        <span class="text-xs font-semibold text-blue-600 mb-2">{{ $product->jenis }}</span>
                    @endif
                    <p class="text-gray-600 text-sm mb-3 font-medium">Product Description</p>
                    <p class="text-gray-500 text-xs mb-2 line-clamp-2">{{ $product->deskripsi ?? 'Detail produk tidak tersedia.' }}</p>
                    <span class="text-lg font-extrabold text-blue-700">Rp {{ number_format($product->harga, 0, ',', '.') }}</span>
                </div>

                <!-- Buy Button Box -->
                <div class="bg-gray-100 rounded-lg border border-gray-300 p-2 text-center hover:bg-gray-50 transition-colors">
                    <a href="{{ route('user.pesanan.detail', ['produk' => $product->id]) }}"
                       class="text-gray-700 font-semibold text-sm w-full block py-2">
                        Buy Button
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
