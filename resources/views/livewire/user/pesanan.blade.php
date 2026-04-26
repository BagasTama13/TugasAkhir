<div>
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8 gap-4 animate-fade-in-up">
        <div>
            <h1 class="text-3xl font-extrabold text-gray-800 tracking-tight">Pesanan Saya</h1>
            <p class="text-gray-500 mt-1">Riwayat dan status pesanan Anda.</p>
        </div>
        <a href="{{ route('user.dashboard') }}" class="btn-primary text-center text-sm inline-flex items-center gap-2 w-fit">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Buat Pesanan Baru
        </a>
    </div>

    <!-- Orders List -->
    <div class="space-y-4">
        @forelse($this->pesanans as $index => $pesanan)
            <div wire:click="showDetail({{ $pesanan->id }})" 
                 class="user-card animate-fade-in-up cursor-pointer hover:ring-2 hover:ring-blue-500 transition-all group" 
                 style="animation-delay: {{ $index * 0.08 }}s; opacity: 0">
                <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4 sm:gap-6 p-5">
                    <!-- Product Picture -->
                    <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-gray-50 to-gray-100 flex items-center justify-center overflow-hidden flex-shrink-0 shadow-inner group-hover:scale-110 transition-transform duration-300">
                        @if($pesanan->produk && $pesanan->produk->gambar)
                            <img src="{{ asset('storage/' . $pesanan->produk->gambar) }}"
                                 alt="{{ $pesanan->produk->nama }}"
                                 class="w-full h-full object-cover">
                        @else
                            <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                            </svg>
                        @endif
                    </div>

                    <!-- Order Details Grid -->
                    <div class="flex-1 grid grid-cols-2 sm:grid-cols-5 gap-3 sm:gap-4 w-full">
                        <!-- Nomor Pesanan -->
                        <div>
                            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider mb-0.5">Nomor</p>
                            <p class="text-sm font-bold text-gray-900">#{{ substr($pesanan->nomor, -6) }}</p>
                        </div>

                        <!-- Nama Product -->
                        <div>
                            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider mb-0.5">Produk</p>
                            <p class="text-sm font-bold text-gray-700 truncate">{{ $pesanan->produk ? $pesanan->produk->nama : $pesanan->nama }}</p>
                        </div>

                        <!-- Jumlah -->
                        <div>
                            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider mb-0.5">Jumlah</p>
                            <p class="text-sm font-bold text-gray-700">{{ number_format($pesanan->jumlah) }} <span class="text-[10px] text-gray-400 font-medium">{{ $pesanan->produk->satuan ?? '' }}</span></p>
                        </div>

                        <!-- Harga Total -->
                        <div>
                            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider mb-0.5">Total</p>
                            <p class="text-sm font-black text-blue-600">
                                @if($pesanan->produk)
                                    Rp {{ number_format($pesanan->produk->harga * $pesanan->jumlah, 0, ',', '.') }}
                                @else
                                    -
                                @endif
                            </p>
                        </div>

                        <!-- Status -->
                        <div class="flex items-center sm:justify-end">
                            <span class="badge badge-{{ $pesanan->status }} scale-90 sm:scale-100 origin-left sm:origin-right">
                                {{ $pesanan->status }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="user-card animate-fade-in-up">
                <div class="text-center py-20 px-6">
                    <div class="h-20 w-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                    </div>
                    <p class="text-gray-900 text-lg font-bold mb-1">Belum Ada Pesanan</p>
                    <p class="text-gray-400 text-sm mb-8">Mulai pesan produk dari dashboard katalog kami!</p>
                    <a href="{{ route('user.dashboard') }}" class="inline-flex items-center gap-2 bg-gray-900 text-white px-8 py-3 rounded-2xl font-bold hover:bg-blue-600 transition-all">
                        Lihat Katalog
                    </a>
                </div>
            </div>
        @endforelse
    </div>

    <!-- DETAIL MODAL -->
    @if($this->selectedPesanan)
    <div class="fixed inset-0 z-[60] flex items-center justify-center p-4 sm:p-6 overflow-y-auto">
        <!-- Backdrop -->
        <div wire:click="closeDetail" class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity"></div>
        
        <!-- Modal Content -->
        <div class="relative bg-white w-full max-w-2xl rounded-[2.5rem] shadow-2xl overflow-hidden animate-slide-up border border-white">
            <!-- Header -->
            <div class="bg-gradient-to-r from-gray-900 to-blue-900 p-8 text-white">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-[10px] font-bold uppercase tracking-[0.2em] opacity-60 mb-1">Detail Pesanan</p>
                        <h2 class="text-2xl font-black">{{ $this->selectedPesanan->nomor }}</h2>
                    </div>
                    <button wire:click="closeDetail" class="p-2 hover:bg-white/10 rounded-xl transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Body -->
            <div class="p-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Section: Produk -->
                    <div class="space-y-4">
                        <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest flex items-center gap-2">
                            <span class="w-1.5 h-1.5 rounded-full bg-blue-600"></span>
                            Informasi Produk
                        </h3>
                        <div class="flex items-center gap-4 bg-gray-50 p-4 rounded-2xl border border-gray-100">
                            <div class="w-16 h-16 rounded-xl overflow-hidden bg-white shadow-sm border border-gray-100 flex-shrink-0">
                                @if($this->selectedPesanan->produk && $this->selectedPesanan->produk->gambar)
                                    <img src="{{ asset('storage/' . $this->selectedPesanan->produk->gambar) }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-gray-300 italic text-[10px]">No img</div>
                                @endif
                            </div>
                            <div>
                                <p class="text-sm font-black text-gray-900">{{ $this->selectedPesanan->produk ? $this->selectedPesanan->produk->nama : $this->selectedPesanan->nama }}</p>
                                <p class="text-xs text-blue-600 font-bold mt-0.5">{{ $this->selectedPesanan->tipe }}</p>
                                <p class="text-xs text-gray-500 font-medium mt-1">
                                    {{ number_format($this->selectedPesanan->jumlah) }} {{ $this->selectedPesanan->produk->satuan ?? 'unit' }} × Rp {{ number_format($this->selectedPesanan->produk->harga ?? 0, 0, ',', '.') }}
                                </p>
                            </div>
                        </div>
                        <div class="pt-2">
                            <p class="text-xs text-gray-400 font-bold uppercase mb-1">Total Pembayaran</p>
                            <p class="text-3xl font-black text-blue-600">Rp {{ number_format(($this->selectedPesanan->produk->harga ?? 0) * $this->selectedPesanan->jumlah, 0, ',', '.') }}</p>
                        </div>
                    </div>

                    <!-- Section: Status & Kontak -->
                    <div class="space-y-4">
                        <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest flex items-center gap-2">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-600"></span>
                            Status & Kontak
                        </h3>
                        <div class="space-y-4">
                            <div>
                                <p class="text-[10px] text-gray-400 font-bold uppercase mb-1.5">Status Pesanan</p>
                                <span class="badge badge-{{ $this->selectedPesanan->status }} text-xs inline-block">
                                    {{ $this->selectedPesanan->status }}
                                </span>
                            </div>
                            <div>
                                <p class="text-[10px] text-gray-400 font-bold uppercase mb-1.5">No. WhatsApp</p>
                                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $this->selectedPesanan->no_whatsapp) }}" target="_blank" class="flex items-center gap-2 text-sm font-bold text-gray-700 hover:text-emerald-600 transition-colors">
                                    <span class="text-emerald-600">📱</span>
                                    {{ $this->selectedPesanan->no_whatsapp }}
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Section: Alamat -->
                    <div class="md:col-span-2 space-y-4">
                        <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest flex items-center gap-2">
                            <span class="w-1.5 h-1.5 rounded-full bg-orange-600"></span>
                            Alamat Pengiriman
                        </h3>
                        <div class="bg-gray-50 p-5 rounded-[2rem] border border-gray-100 flex items-start gap-4">
                            <span class="text-2xl mt-1">📍</span>
                            <div class="flex-1">
                                <p class="text-sm font-bold text-gray-800 leading-relaxed">{{ $this->selectedPesanan->alamat_pengiriman }}</p>
                                @if($this->selectedPesanan->catatan)
                                    <div class="mt-3 pt-3 border-t border-gray-200">
                                        <p class="text-[10px] text-gray-400 font-bold uppercase mb-1">Catatan Tambahan</p>
                                        <p class="text-xs text-gray-600 italic font-medium">"{{ $this->selectedPesanan->catatan }}"</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Footer Action -->
                <div class="mt-10 pt-6 border-t border-gray-100 flex justify-end">
                    <button wire:click="closeDetail" class="px-10 py-3 bg-gray-100 text-gray-700 font-bold rounded-2xl hover:bg-gray-200 transition-all">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>

    <style>
        @keyframes slide-up {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-slide-up {
            animation: slide-up 0.4s cubic-bezier(0.4, 0, 0.2, 1) forwards;
        }
    </style>
    @endif
</div>
</div>
