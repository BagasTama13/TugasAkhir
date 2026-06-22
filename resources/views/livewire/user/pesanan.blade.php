<!-- Wrapper utama halaman dengan min-height full screen dan background abu-abu terang. Terdapat wire:poll untuk me-refresh komponen secara otomatis setiap 5 detik. -->
<div class="min-h-screen bg-[#F8FAFC] pb-12" wire:poll.5s>
    <!-- Header Section -->
    <!-- Bagian Header (Judul & Tombol) yang melayang (sticky) di bagian atas layar saat di-scroll, menggunakan z-30 agar tidak tertumpuk elemen lain -->
    <div class="bg-white border-b border-slate-200 sticky top-0 z-30 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="py-8 flex flex-col md:flex-row md:items-center md:justify-between gap-6">
                <div>
                    <h1 class="text-3xl font-display font-extrabold text-slate-900 tracking-tight">Pesanan Saya</h1>
                    <p class="text-sm text-slate-500 mt-1 font-medium italic">Pantau status pengiriman dan riwayat transaksi Anda di sini.</p>
                </div>
                <a href="{{ route('user.dashboard') }}" class="inline-flex items-center px-6 py-3 bg-indigo-600 text-white text-sm font-bold rounded-2xl hover:bg-slate-900 shadow-lg shadow-indigo-100 transition-all duration-300">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    Belanja Lagi
                </a>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-10">

        {{-- Flash success --}}
        @if(session('success'))
            <!-- Notifikasi sukses (Flash message) menggunakan Alpine.js (x-data) yang akan hilang otomatis setelah 4 detik (timeout) menggunakan efek memudar (fade-in) -->
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
                 class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-700 px-5 py-4 rounded-2xl flex justify-between items-center shadow-sm animate-in fade-in">
                <div class="flex items-center gap-2 font-bold text-sm">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                    {{ session('success') }}
                </div>
                <button @click="show = false" class="text-emerald-400 hover:text-emerald-600"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
            </div>
        @endif

        <!-- Orders List -->
        <div class="space-y-6">
            {{-- Melakukan looping (perulangan) pada seluruh data pesanan user. Jika kosong akan menampilkan "Belum Ada Riwayat" --}}
            @forelse($this->pesanans as $index => $pesanan)
                @php
                    // Array $statusStyles dan $statusLabels digunakan untuk memetakan status bahasa database ke label dan warna UI
                    $statusStyles = [
                        'pending'       => 'bg-amber-50 text-amber-600 border-amber-100',
                        'dalam_antrian' => 'bg-blue-50 text-blue-600 border-blue-100',
                        'diproses'      => 'bg-indigo-50 text-indigo-600 border-indigo-100',
                        'terkirim'      => 'bg-teal-50 text-teal-600 border-teal-100',
                        'rejected'      => 'bg-rose-50 text-rose-600 border-rose-100',
                    ][$pesanan->status] ?? 'bg-slate-50 text-slate-600 border-slate-100';

                    $statusLabels = [
                        'pending'       => 'Menunggu Konfirmasi',
                        'dalam_antrian' => 'Dalam Antrian',
                        'diproses'      => 'Sedang Diproses',
                        'terkirim'      => 'Terkirim',
                        'rejected'      => 'Ditolak',
                    ];
                    $statusLabel = $statusLabels[$pesanan->status] ?? $pesanan->status;

                    // $canPay digunakan sebagai flag (penanda) apakah tombol bayar / tagihan dapat dimunculkan
                    // Syarat: pesanan bukan 'pending' (sudah di-acc) dan payment_status masih 'belum_dibayar'
                    $canPay = in_array($pesanan->status, ['dalam_antrian', 'diproses', 'terkirim'])
                              && $pesanan->payment_status === 'belum_dibayar';
                @endphp
                <!-- Kartu daftar pesanan individu. Memiliki efek bayangan (shadow) saat dihover dan animasi masuk dari bawah (slide-in) ke atas secara bergiliran menggunakan gaya (style) delay index -->
                <div wire:click="showDetail({{ $pesanan->id }})"
                     class="bg-white rounded-[2rem] border border-slate-200 shadow-sm hover:shadow-xl hover:shadow-indigo-50/50 hover:border-indigo-200 transition-all duration-300 cursor-pointer group animate-in fade-in slide-in-from-bottom-4"
                     style="animation-delay: {{ $index * 0.05 }}s">
                    <div class="flex flex-col md:flex-row md:items-center gap-6 p-6">
                        <!-- Product Visual -->
                        <!-- Box tempat gambar produk dengan sudut melengkung (rounded). Terdapat animasi membesar secara perlahan (group-hover:scale-105 transition-transform) jika kartu pesanan dihover -->
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
                        <!-- Grid layout untuk detail text pesanan. Responsif: dibagi menjadi 2 kolom untuk HP, dan 4 kolom untuk Desktop (lg:grid-cols-4) -->
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
                                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mb-1">Total</p>
                                <p class="text-sm font-black text-indigo-600">
                                    @if($pesanan->total_harga)
                                        Rp{{ number_format($pesanan->total_harga, 0, ',', '.') }}
                                    @else
                                        -
                                    @endif
                                </p>
                            </div>

                            <div class="flex flex-col items-start lg:items-end gap-2">
                                <span class="inline-flex items-center px-4 py-1.5 rounded-xl text-[10px] font-bold border {{ $statusStyles }} uppercase tracking-wider shadow-sm">
                                    <span class="h-1.5 w-1.5 rounded-full bg-current mr-2 animate-pulse"></span>
                                    {{ $statusLabel }}
                                </span>
                                @if($pesanan->payment_status === 'telah_dibayar')
                                    <span class="inline-flex items-center px-3 py-1 rounded-lg text-[9px] font-bold bg-emerald-50 text-emerald-600 border border-emerald-100 uppercase tracking-wider">
                                        ✓ Lunas
                                    </span>
                                @elseif($canPay)
                                    <span class="inline-flex items-center px-3 py-1 rounded-lg text-[9px] font-bold bg-orange-50 text-orange-600 border border-orange-100 uppercase tracking-wider animate-pulse">
                                        ⚡ Belum Dibayar
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <!-- Tampilan Empty State (Keadaan Kosong). Ditampilkan jika user belum pernah melakukan pemesanan sama sekali -->
                <div class="bg-white rounded-[3rem] border border-slate-200 p-20 text-center shadow-sm">
                    <div class="h-24 w-24 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6 text-slate-200">
                        <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                    </div>
                    <h3 class="text-2xl font-display font-extrabold text-slate-900 mb-2">Belum Ada Riwayat</h3>
                    <p class="text-sm text-slate-500 mb-10 max-w-sm mx-auto font-medium">Sepertinya Anda belum melakukan pemesanan. Ayo mulai proyek Anda hari ini!</p>
                    <a href="{{ route('user.dashboard') }}" class="inline-flex items-center px-10 py-4 bg-slate-900 text-white text-sm font-bold rounded-[2rem] hover:bg-indigo-600 transition-all duration-300 shadow-xl shadow-slate-200">
                        Lihat Katalog Produk
                    </a>
                </div>
            @endforelse
        </div>

        <!-- DETAIL MODAL -->
        <!-- Blok ini merender Modal Popup Detail Pesanan, muncul hanya jika variabel selectedPesanan berisi id. -->
        @if($this->selectedPesanan)
        @php
            $sp = $this->selectedPesanan;
            $canPayModal = in_array($sp->status, ['dalam_antrian', 'diproses', 'terkirim'])
                           && $sp->payment_status === 'belum_dibayar';

            $modalStatusLabels = [
                'pending'       => 'Menunggu Konfirmasi',
                'dalam_antrian' => 'Dalam Antrian',
                'diproses'      => 'Sedang Diproses',
                'terkirim'      => 'Terkirim',
                'rejected'      => 'Ditolak',
            ];
            $modalStatusLabel = $modalStatusLabels[$sp->status] ?? $sp->status;
        @endphp
        <!-- Kontainer pembungkus utama Modal Popup dengan z-[60] memastikannya berada di posisi paling atas menutupi semua elemen halaman -->
        <div class="fixed inset-0 z-[60] flex items-center justify-center p-4 sm:p-8 overflow-y-auto">
            <!-- Backdrop -->
            <!-- Latar belakang gelap transparan (Backdrop) dengan efek blur (backdrop-blur-md). Klik pada area gelap ini akan menutup popup (memanggil wire:click closeDetail) -->
            <div wire:click="closeDetail" class="fixed inset-0 bg-slate-900/40 backdrop-blur-md transition-opacity duration-500 animate-in fade-in"></div>

            <!-- Modal Content -->
            <!-- Kotak utama konten popup dengan border sangat melengkung (rounded-[3rem]). Menggunakan animasi membesar secara pop-up (zoom-in-95) saat pertama kali dirender -->
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
                        <h2 class="text-3xl font-display font-black tracking-tight">{{ $sp->nomor }}</h2>
                        <div class="mt-4 flex flex-wrap gap-2">
                            <div class="inline-flex items-center px-3 py-1 bg-white/10 rounded-lg text-[10px] font-bold uppercase tracking-wider text-white backdrop-blur-sm">
                                <span class="h-2 w-2 rounded-full bg-emerald-400 mr-2"></span>
                                {{ $modalStatusLabel }}
                            </div>
                            @if($sp->payment_status === 'telah_dibayar')
                                <div class="inline-flex items-center px-3 py-1 bg-emerald-500/20 rounded-lg text-[10px] font-bold uppercase tracking-wider text-emerald-300">
                                    ✓ Telah Dibayar
                                </div>
                            @elseif($sp->payment_status === 'belum_dibayar')
                                <div class="inline-flex items-center px-3 py-1 bg-orange-500/20 rounded-lg text-[10px] font-bold uppercase tracking-wider text-orange-300">
                                    ⚡ Belum Dibayar
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Body -->
                <div class="p-10 space-y-10">
                    @if($sp->status === 'rejected' && $sp->alasan_penolakan)
                        <div class="bg-rose-50 border border-rose-200 rounded-2xl p-5 shadow-sm">
                            <div class="flex items-start gap-4">
                                <div class="mt-0.5 text-rose-500">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                </div>
                                <div>
                                    <h4 class="text-sm font-bold text-rose-900 mb-1">Pesanan Ditolak</h4>
                                    <div class="text-sm text-rose-700 whitespace-pre-line leading-relaxed">{{ $sp->alasan_penolakan }}</div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                        <!-- Left: Product Info -->
                        <div class="space-y-6">
                            <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest">Informasi Produk</h3>
                            <div class="flex items-center gap-5">
                                <div class="w-20 h-20 rounded-2xl overflow-hidden bg-slate-50 border border-slate-100 flex-shrink-0 shadow-sm">
                                    @if($sp->produk && $sp->produk->gambar)
                                        <img src="{{ asset('storage/' . $sp->produk->gambar) }}" class="w-full h-full object-cover">
                                    @endif
                                </div>
                                <div class="space-y-1">
                                    <p class="text-lg font-bold text-slate-900">{{ $sp->produk ? $sp->produk->nama : $sp->nama }}</p>
                                    <p class="text-xs text-indigo-600 font-bold uppercase tracking-wider">{{ $sp->tipe }}</p>
                                    <p class="text-xs text-slate-500 font-medium">
                                        {{ number_format($sp->jumlah) }} {{ $sp->produk->satuan ?? 'unit' }} × Rp{{ number_format($sp->produk->harga ?? 0, 0, ',', '.') }}
                                    </p>
                                </div>
                            </div>
                            <div class="pt-4 border-t border-slate-50">
                                <p class="text-[10px] text-slate-400 font-bold uppercase mb-1">Total Pembayaran</p>
                                <p class="text-3xl font-black text-slate-900">
                                    @if($sp->total_harga)
                                        Rp{{ number_format($sp->total_harga, 0, ',', '.') }}
                                    @else
                                        Rp{{ number_format(($sp->produk->harga ?? 0) * $sp->jumlah, 0, ',', '.') }}
                                    @endif
                                </p>
                                @if($sp->paid_at)
                                    <p class="text-xs text-emerald-600 font-bold mt-1">Dibayar: {{ $sp->paid_at->format('d M Y, H:i') }}</p>
                                @endif
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
                                        <p class="text-xs font-bold text-slate-700 leading-relaxed">{{ $sp->alamat_pengiriman }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="space-y-4">
                                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest">Hubungi Kami</h3>
                                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $sp->no_whatsapp) }}" target="_blank" class="flex items-center gap-4 bg-emerald-50 p-5 rounded-2xl border border-emerald-100 group transition-all hover:bg-emerald-600 hover:text-white">
                                    <div class="h-10 w-10 bg-white rounded-xl flex items-center justify-center text-emerald-600 shadow-sm flex-shrink-0 group-hover:scale-110 transition-transform">
                                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"></path></svg>
                                    </div>
                                    <div>
                                        <p class="text-xs font-bold text-emerald-800 uppercase group-hover:text-white transition-colors">Nomor WhatsApp</p>
                                        <p class="text-sm font-black text-emerald-900 group-hover:text-white transition-colors">{{ $sp->no_whatsapp }}</p>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Footer Action -->
                    <div class="pt-10 border-t border-slate-100 flex flex-col sm:flex-row justify-between items-center gap-4">
                        <button wire:click="closeDetail" class="px-8 py-3 bg-slate-100 text-slate-700 text-sm font-bold rounded-[2rem] hover:bg-slate-200 transition-all duration-300">
                            Kembali
                        </button>

                        @if($canPayModal)
                            {{-- Tombol Bayar Sekarang – trigger Midtrans Snap --}}
                            <button
                                id="pay-btn-{{ $sp->id }}"
                                onclick="bayarSekarang({{ $sp->id }}, '{{ csrf_token() }}')"
                                class="px-10 py-3 bg-indigo-600 text-white text-sm font-bold rounded-[2rem] hover:bg-indigo-700 shadow-xl shadow-indigo-200 transition-all duration-300 flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                                Bayar Sekarang
                            </button>
                        @elseif($sp->payment_status === 'telah_dibayar')
                            <div class="flex items-center gap-2 text-emerald-600 font-bold text-sm">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                                Pembayaran Selesai
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

{{-- Midtrans Snap Handler Script --}}
{{-- Skrip JavaScript ini digunakan untuk memanggil popup pembayaran Midtrans secara Asynchronous (SPA style) --}}
<script>
// Guard: prevent snap.pay() from being called while popup is already open
let _snapIsOpen = false;

// Fungsi yang dipanggil dari tombol 'Bayar Sekarang' di dalam HTML
function bayarSekarang(pesananId, csrfToken) {
    // Block if Snap popup is already showing
    if (_snapIsOpen) {
        console.warn('Snap popup already open.');
        return;
    }

    const btn = document.getElementById('pay-btn-' + pesananId);
    if (btn) {
        btn.disabled = true;
        // Ubah teks tombol menjadi state 'Loading' agar user tidak menekan berkali-kali
        btn.innerHTML = '<svg class="w-4 h-4 animate-spin inline mr-1" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg> Memuat...';
    }

    // Mengambil (Fetch) snap_token terbaru dari backend server (Controller) menggunakan AJAX
    fetch('/pesanan/' + pesananId + '/snap-token', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
        },
        body: JSON.stringify({ pesanan_id: pesananId }),
    })
    .then(res => res.json())
    .then(data => {
        if (data.error) {
            alert('Error: ' + data.error);
            resetBtn(btn);
            return;
        }

        if (typeof window.snap === 'undefined') {
            alert('Midtrans Snap tidak tersedia. Pastikan koneksi internet aktif.');
            resetBtn(btn);
            return;
        }

        // Double-check guard before calling snap.pay
        if (_snapIsOpen) {
            resetBtn(btn);
            return;
        }

        _snapIsOpen = true;

        // Memanggil interface / UI pembayaran dari Midtrans (SDK midtrans-snap)
        window.snap.pay(data.snap_token, {
            onSuccess: function(result) {
                console.log('Payment success', result);
                _snapIsOpen = false;
                resetBtn(btn);
                // Update DB langsung (bypass webhook untuk localhost)
                // Memanggil backend Livewire secara reaktif (@this merujuk ke instance Livewire PHP)
                const txId = result.transaction_id || result.order_id || '';
                @this.confirmPaymentFromClient(pesananId, txId);
            },
            onPending: function(result) {
                console.log('Payment pending', result);
                _snapIsOpen = false;
                resetBtn(btn);
                alert('Pembayaran dalam proses. Silakan selesaikan pembayaran Anda.');
            },
            onError: function(result) {
                console.error('Payment error', result);
                _snapIsOpen = false;
                resetBtn(btn);
                alert('Pembayaran gagal. Silakan coba lagi.');
            },
            onClose: function() {
                console.log('Snap popup closed by user.');
                _snapIsOpen = false;
                resetBtn(btn);
            }
        });
    })
    .catch(err => {
        console.error('Error fetching snap token', err);
        _snapIsOpen = false;
        resetBtn(btn);
        alert('Terjadi kesalahan. Silakan coba lagi.');
    });
}

function resetBtn(btn) {
    if (btn) {
        btn.disabled = false;
        btn.innerHTML = '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg> Bayar Sekarang';
    }
}
</script>

