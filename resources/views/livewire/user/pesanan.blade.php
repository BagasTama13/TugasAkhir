<div class="min-h-screen bg-[#F8FAFC] pb-12">
    <!-- Header Section -->
    <div class="bg-white border-b border-slate-200 sticky top-0 z-30 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="py-8 flex flex-col md:flex-row md:items-center md:justify-between gap-6">
                <div>
                    <h1 class="text-3xl font-display font-extrabold text-slate-900 tracking-tight">Pesanan Saya</h1>
                    <p class="text-sm text-slate-500 mt-1 font-medium italic">Pantau status pengiriman dan riwayat material Anda di sini.</p>
                </div>
                <a href="{{ route('user.dashboard') }}" class="inline-flex items-center px-6 py-3 bg-indigo-600 text-white text-sm font-bold rounded-2xl hover:bg-slate-900 shadow-lg shadow-indigo-100 transition-all duration-300">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    Belanja Lagi
                </a>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-10">
        <!-- Orders List -->
        <div class="space-y-6">
            @forelse($this->pesanans as $index => $pesanan)
                <div wire:click="showDetail({{ $pesanan->id }})" 
                     class="bg-white rounded-[2rem] border border-slate-200 shadow-sm hover:shadow-xl hover:shadow-indigo-50/50 hover:border-indigo-200 transition-all duration-300 cursor-pointer group animate-in fade-in slide-in-from-bottom-4" 
                     style="animation-delay: {{ $index * 0.05 }}s">
                    <div class="flex flex-col md:flex-row md:items-center gap-6 p-6">
                        <!-- Product Visual -->
                        <div class="w-20 h-20 rounded-2xl bg-slate-50 flex items-center justify-center overflow-hidden flex-shrink-0 border border-slate-100 shadow-inner group-hover:scale-105 transition-transform duration-500">
                            @if($pesanan->produk && $pesanan->produk->gambar)
                                <img src="{{ asset('storage/' . $pesanan->produk->gambar) }}"
                                     alt="{{ $pesanan->produk->nama }}"
                                     class="w-full h-full object-cover">
                            @else
                                <svg class="w-8 h-8 text-slate-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                            @endif
                        </div>

                        <!-- Info Grid -->
                        <div class="flex-1 grid grid-cols-2 lg:grid-cols-4 gap-6">
                            <div>
                                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mb-1">ID Pesanan</p>
                                <p class="text-sm font-black text-slate-900">#{{ substr($pesanan->nomor, -6) }}</p>
                                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-tighter">{{ $pesanan->created_at->format('d M Y') }}</p>
                            </div>

                            <div>
                                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mb-1">Produk & Tipe</p>
                                <p class="text-sm font-bold text-slate-700 truncate capitalize">{{ $pesanan->produk ? $pesanan->produk->nama : $pesanan->nama }}</p>
                                <span class="px-2 py-0.5 bg-slate-100 rounded-md text-[9px] font-bold text-slate-500 uppercase tracking-tight">{{ $pesanan->tipe }}</span>
                            </div>

                            <div>
                                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mb-1">Jumlah & Total</p>
                                <p class="text-sm font-bold text-slate-900">{{ number_format($pesanan->jumlah) }} <span class="text-[10px] text-slate-400 font-normal uppercase">{{ $pesanan->produk->satuan ?? '' }}</span></p>
                                <p class="text-xs font-black text-indigo-600">
                                    @if($pesanan->produk)
                                        Rp{{ number_format($pesanan->produk->harga * $pesanan->jumlah, 0, ',', '.') }}
                                    @else
                                        -
                                    @endif
                                </p>
                            </div>

                            <div class="flex items-center lg:justify-end">
                                @php
                                    $statusStyles = [
                                        'pending' => 'bg-amber-50 text-amber-600 border-amber-100',
                                        'accepted' => 'bg-emerald-50 text-emerald-600 border-emerald-100',
                                        'rejected' => 'bg-rose-50 text-rose-600 border-rose-100',
                                        'delivered' => 'bg-indigo-50 text-indigo-600 border-indigo-100',
                                    ][$pesanan->status] ?? 'bg-slate-50 text-slate-600 border-slate-100';
                                @endphp
                                <span class="inline-flex items-center px-4 py-1.5 rounded-xl text-[10px] font-bold border {{ $statusStyles }} uppercase tracking-wider shadow-sm">
                                    <span class="h-1.5 w-1.5 rounded-full bg-current mr-2 animate-pulse"></span>
                                    {{ $pesanan->status }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-white rounded-[3rem] border border-slate-200 p-20 text-center shadow-sm">
                    <div class="h-24 w-24 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6 text-slate-200">
                        <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                    </div>
                    <h3 class="text-2xl font-display font-extrabold text-slate-900 mb-2">Belum Ada Riwayat</h3>
                    <p class="text-sm text-slate-500 mb-10 max-w-sm mx-auto font-medium">Sepertinya Anda belum melakukan pemesanan material. Ayo mulai proyek Anda hari ini!</p>
                    <a href="{{ route('user.dashboard') }}" class="inline-flex items-center px-10 py-4 bg-slate-900 text-white text-sm font-bold rounded-[2rem] hover:bg-indigo-600 transition-all duration-300 shadow-xl shadow-slate-200">
                        Lihat Katalog Produk
                    </a>
                </div>
            @endforelse
        </div>

        <!-- DETAIL MODAL -->
        @if($this->selectedPesanan)
        <div class="fixed inset-0 z-[60] flex items-center justify-center p-4 sm:p-8 overflow-y-auto">
            <!-- Backdrop -->
            <div wire:click="closeDetail" class="fixed inset-0 bg-slate-900/40 backdrop-blur-md transition-opacity duration-500 animate-in fade-in"></div>
            
            <!-- Modal Content -->
            <div class="relative bg-white w-full max-w-2xl rounded-[3rem] shadow-2xl overflow-hidden border border-white animate-in zoom-in-95 duration-300">
                <!-- Header -->
                <div class="bg-slate-900 p-8 text-white relative">
                    <div class="absolute top-0 right-0 p-8">
                        <button wire:click="closeDetail" class="p-2 hover:bg-white/10 rounded-xl transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-slate-400 mb-1">Rincian Transaksi</p>
                        <h2 class="text-3xl font-display font-black tracking-tight">{{ $this->selectedPesanan->nomor }}</h2>
                        <div class="mt-4 inline-flex items-center px-3 py-1 bg-white/10 rounded-lg text-[10px] font-bold uppercase tracking-wider text-white backdrop-blur-sm">
                            <span class="h-2 w-2 rounded-full bg-emerald-400 mr-2"></span>
                            Status: {{ $this->selectedPesanan->status }}
                        </div>
                    </div>
                </div>

                <!-- Body -->
                <div class="p-10 space-y-10">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                        <!-- Left: Product Info -->
                        <div class="space-y-6">
                            <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest">Informasi Produk</h3>
                            <div class="flex items-center gap-5">
                                <div class="w-20 h-20 rounded-2xl overflow-hidden bg-slate-50 border border-slate-100 flex-shrink-0 shadow-sm">
                                    @if($this->selectedPesanan->produk && $this->selectedPesanan->produk->gambar)
                                        <img src="{{ asset('storage/' . $this->selectedPesanan->produk->gambar) }}" class="w-full h-full object-cover">
                                    @endif
                                </div>
                                <div class="space-y-1">
                                    <p class="text-lg font-bold text-slate-900">{{ $this->selectedPesanan->produk ? $this->selectedPesanan->produk->nama : $this->selectedPesanan->nama }}</p>
                                    <p class="text-xs text-indigo-600 font-bold uppercase tracking-wider">{{ $this->selectedPesanan->tipe }}</p>
                                    <p class="text-xs text-slate-500 font-medium">
                                        {{ number_format($this->selectedPesanan->jumlah) }} {{ $this->selectedPesanan->produk->satuan ?? 'unit' }} × Rp{{ number_format($this->selectedPesanan->produk->harga ?? 0, 0, ',', '.') }}
                                    </p>
                                </div>
                            </div>
                            <div class="pt-4 border-t border-slate-50">
                                <p class="text-[10px] text-slate-400 font-bold uppercase mb-1">Total Pembayaran</p>
                                <p class="text-3xl font-black text-slate-900">Rp{{ number_format(($this->selectedPesanan->produk->harga ?? 0) * $this->selectedPesanan->jumlah, 0, ',', '.') }}</p>
                            </div>
                        </div>

                        <!-- Right: Logistics & Contact -->
                        <div class="space-y-8">
                            <div class="space-y-4">
                                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest">Logistik & Pengiriman</h3>
                                <div class="bg-slate-50 p-5 rounded-2xl border border-slate-100 flex items-start gap-4">
                                    <div class="h-10 w-10 bg-white rounded-xl flex items-center justify-center text-slate-400 shadow-sm flex-shrink-0">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Alamat Tujuan</p>
                                        <p class="text-xs font-bold text-slate-700 leading-relaxed">{{ $this->selectedPesanan->alamat_pengiriman }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="space-y-4">
                                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest">Hubungi Admin</h3>
                                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $this->selectedPesanan->no_whatsapp) }}" target="_blank" class="flex items-center gap-4 bg-emerald-50 p-5 rounded-2xl border border-emerald-100 group transition-all hover:bg-emerald-600 hover:text-white">
                                    <div class="h-10 w-10 bg-white rounded-xl flex items-center justify-center text-emerald-600 shadow-sm flex-shrink-0 group-hover:scale-110 transition-transform">
                                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"></path></svg>
                                    </div>
                                    <div>
                                        <p class="text-xs font-bold text-emerald-800 uppercase group-hover:text-white transition-colors">Nomor WhatsApp</p>
                                        <p class="text-sm font-black text-emerald-900 group-hover:text-white transition-colors">{{ $this->selectedPesanan->no_whatsapp }}</p>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Footer Action -->
                    <div class="pt-10 flex justify-end">
                        <button wire:click="closeDetail" class="px-12 py-4 bg-slate-900 text-white text-sm font-bold rounded-[2rem] hover:bg-indigo-600 shadow-xl shadow-slate-200 transition-all duration-300">
                            Kembali
                        </button>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
