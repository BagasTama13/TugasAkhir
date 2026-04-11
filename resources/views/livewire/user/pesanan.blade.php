<div>
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8 gap-4 animate-fade-in-up">
        <div>
            <h1 class="text-3xl font-extrabold text-gray-800 tracking-tight">Pesanan Saya</h1>
            <p class="text-gray-500 mt-1">Riwayat dan status pesanan Anda.</p>
        </div>
        <a href="{{ route('user.pesanan.detail') }}" class="btn-primary text-center text-sm inline-flex items-center gap-2 w-fit">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Buat Pesanan Baru
        </a>
    </div>

    <!-- Orders List -->
    <div class="space-y-4">
        @forelse($this->pesanans as $index => $pesanan)
            <div class="user-card animate-fade-in-up" style="animation-delay: {{ $index * 0.08 }}s; opacity: 0">
                <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4 sm:gap-6 p-5">
                    <!-- Product Picture -->
                    <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center overflow-hidden flex-shrink-0 shadow-inner">
                        @if($pesanan->produk && $pesanan->produk->gambar)
                            <img src="{{ asset('storage/' . $pesanan->produk->gambar) }}"
                                 alt="{{ $pesanan->produk->nama }}"
                                 class="w-full h-full object-cover">
                        @else
                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                            </svg>
                        @endif
                    </div>

                    <!-- Order Details Grid -->
                    <div class="flex-1 grid grid-cols-2 sm:grid-cols-5 gap-3 sm:gap-4 w-full">
                        <!-- Tanggal Pesanan -->
                        <div>
                            <p class="text-xs text-gray-400 font-medium mb-0.5">Tanggal Pesanan</p>
                            <p class="text-sm font-semibold text-gray-700">{{ $pesanan->created_at->format('d M Y') }}</p>
                        </div>

                        <!-- Nama Product -->
                        <div>
                            <p class="text-xs text-gray-400 font-medium mb-0.5">Nama Product</p>
                            <p class="text-sm font-semibold text-gray-700">{{ $pesanan->nama }}</p>
                        </div>

                        <!-- Jumlah Product -->
                        <div>
                            <p class="text-xs text-gray-400 font-medium mb-0.5">Jumlah Product</p>
                            <p class="text-sm font-semibold text-gray-700">{{ number_format($pesanan->jumlah) }}</p>
                        </div>

                        <!-- Harga Total -->
                        <div>
                            <p class="text-xs text-gray-400 font-medium mb-0.5">Harga Total</p>
                            <p class="text-sm font-bold text-emerald-600">
                                @if($pesanan->produk)
                                    Rp {{ number_format($pesanan->produk->harga * $pesanan->jumlah, 0, ',', '.') }}
                                @else
                                    -
                                @endif
                            </p>
                        </div>

                        <!-- Status Pesanan -->
                        <div>
                            <p class="text-xs text-gray-400 font-medium mb-0.5">Status</p>
                            <span class="badge badge-{{ $pesanan->status }}">
                                {{ $pesanan->status }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="user-card animate-fade-in-up">
                <div class="text-center py-16 px-6">
                    <svg class="w-20 h-20 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                    <p class="text-gray-400 text-lg font-medium mb-2">Belum ada pesanan.</p>
                    <p class="text-gray-400 text-sm mb-6">Mulai pesan produk dari dashboard!</p>
                    <a href="{{ route('user.dashboard') }}" class="btn-secondary inline-flex items-center gap-2">
                        Lihat Produk
                    </a>
                </div>
            </div>
        @endforelse
    </div>
</div>
