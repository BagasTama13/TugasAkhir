<div class="space-y-6">
    <!-- Success Alert -->
    @if(session()->has('success'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)" class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl flex justify-between items-center animate-in fade-in slide-in-from-top-4 duration-300">
            <div class="flex items-center gap-2 font-bold text-sm">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                {{ session('success') }}
            </div>
            <button @click="show = false" class="text-emerald-400 hover:text-emerald-600"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
        </div>
    @endif

    <!-- Standard Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Manajemen Pesanan</h1>
            <p class="text-sm text-slate-500 mt-1">Pantau dan kelola seluruh pesanan material pelanggan.</p>
        </div>

        <div class="flex items-center gap-3">
            @if(!$this->readonly && !isset($isWorkerView))
                <button wire:click="toggleForm" class="inline-flex items-center px-4 py-2 text-sm font-bold text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 transition-all shadow-sm">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Tambah Pesanan
                </button>
            @endif
        </div>
    </div>

    <!-- Quick Stats Grid -->
    <div wire:poll.5s class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4">
        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Total Pesanan</p>
            <p class="text-2xl font-bold text-slate-900 mt-1">{{ $this->pesanans->count() }}</p>
        </div>
        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm border-l-4 border-l-amber-500">
            <p class="text-[10px] font-bold text-amber-500 uppercase tracking-widest">Menunggu</p>
            <p class="text-2xl font-bold text-slate-900 mt-1">{{ $this->pesanans->where('status', 'pending')->count() }}</p>
        </div>
        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm border-l-4 border-l-blue-500">
            <p class="text-[10px] font-bold text-blue-500 uppercase tracking-widest">Antrian</p>
            <p class="text-2xl font-bold text-slate-900 mt-1">{{ $this->pesanans->where('status', 'dalam_antrian')->count() }}</p>
        </div>
        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm border-l-4 border-l-indigo-500">
            <p class="text-[10px] font-bold text-indigo-500 uppercase tracking-widest">Diproses</p>
            <p class="text-2xl font-bold text-slate-900 mt-1">{{ $this->pesanans->where('status', 'diproses')->count() }}</p>
        </div>
        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm border-l-4 border-l-emerald-500">
            <p class="text-[10px] font-bold text-emerald-500 uppercase tracking-widest">Terkirim</p>
            <p class="text-2xl font-bold text-slate-900 mt-1">{{ $this->pesanans->where('status', 'terkirim')->count() }}</p>
        </div>
    </div>

    <!-- Form Section -->
    @if(!$this->readonly && $showForm)
        <div class="bg-white rounded-xl shadow-lg border border-slate-200 overflow-hidden mb-8">
            <div class="px-6 py-4 bg-slate-50 border-b border-slate-200 flex justify-between items-center">
                <h2 class="font-bold text-slate-900">{{ $editingId ? 'Edit Data Pesanan' : 'Input Pesanan Baru' }}</h2>
                <button wire:click="closeForm" class="text-slate-400 hover:text-slate-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            <form wire:submit="tambahPesanan" class="p-6 space-y-6">

                {{-- Info banner: nomor & tipe otomatis --}}
                @if(!$editingId)
                <div class="flex items-start gap-3 bg-indigo-50 border border-indigo-100 rounded-lg px-4 py-3 text-xs text-indigo-700">
                    <svg class="w-4 h-4 mt-0.5 shrink-0 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span><strong>Nomor pesanan</strong> akan dibuat otomatis (format: <code class="bg-indigo-100 px-1 rounded">ADM-XXXXXX</code>). <strong>Tipe layanan</strong>, <strong>harga</strong>, dan <strong>deskripsi</strong> juga diisi otomatis dari produk yang dipilih — sama seperti alur pesanan pelanggan.</span>
                </div>
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div class="space-y-1.5">
                        <label class="text-xs font-bold text-slate-700 uppercase tracking-wide">Nama Pelanggan</label>
                        <input type="text" wire:model="nama" class="w-full px-4 py-2 text-sm rounded-lg border border-slate-300 focus:ring-2 focus:ring-indigo-500 outline-none">
                        @error('nama') <p class="text-xs text-rose-500 font-medium">{{ $message }}</p> @enderror
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-xs font-bold text-slate-700 uppercase tracking-wide">No. WhatsApp</label>
                        <input type="text" wire:model="no_whatsapp" placeholder="08xxxxxxxxxx" class="w-full px-4 py-2 text-sm rounded-lg border border-slate-300 focus:ring-2 focus:ring-indigo-500 outline-none">
                        @error('no_whatsapp') <p class="text-xs text-rose-500 font-medium">{{ $message }}</p> @enderror
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-xs font-bold text-slate-700 uppercase tracking-wide">Produk Material</label>
                        <select wire:model="produk_id" class="w-full px-4 py-2 text-sm rounded-lg border border-slate-300 focus:ring-2 focus:ring-indigo-500 outline-none">
                            <option value="">Pilih Produk</option>
                            @foreach($this->produks as $produk)
                                <option value="{{ $produk->id }}">{{ $produk->nama }} — {{ $produk->jenis }} (Rp{{ number_format($produk->harga, 0, ',', '.') }})</option>
                            @endforeach
                        </select>
                        @error('produk_id') <p class="text-xs text-rose-500 font-medium">{{ $message }}</p> @enderror
                    </div>
                    {{-- Jumlah: kondisional berdasarkan produk yang dipilih --}}
                    @if($this->isGrajen)
                        {{-- Grajen: 2 baris (Halus + Kasar), masing-masing ada jumlah & harga --}}
                        <div class="space-y-3 md:col-span-2 lg:col-span-2">
                            <label class="text-xs font-bold text-slate-700 uppercase tracking-wide flex items-center gap-2">
                                Jumlah & Harga
                                <span class="px-1.5 py-0.5 bg-amber-100 text-amber-700 text-[9px] font-bold rounded uppercase tracking-wider">Grajen</span>
                            </label>

                            {{-- Baris Halus --}}
                            <div class="bg-slate-50 rounded-lg p-3 space-y-2">
                                <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest">Halus</p>
                                <div class="flex gap-3">
                                    <div class="flex-1 space-y-1">
                                        <label class="text-[10px] text-slate-400 font-bold uppercase">Jumlah (sak)</label>
                                        <input type="number" wire:model="jumlah_halus" placeholder="0" class="w-full px-3 py-2 text-sm rounded-lg border border-slate-300 focus:ring-2 focus:ring-amber-400 outline-none">
                                        @error('jumlah_halus') <p class="text-xs text-rose-500 font-medium">{{ $message }}</p> @enderror
                                    </div>
                                    <div class="flex-1 space-y-1">
                                        <label class="text-[10px] text-slate-400 font-bold uppercase">Harga / sak (Rp)</label>
                                        <input type="number" wire:model="harga_halus" placeholder="0" class="w-full px-3 py-2 text-sm rounded-lg border border-slate-300 focus:ring-2 focus:ring-amber-400 outline-none">
                                        @error('harga_halus') <p class="text-xs text-rose-500 font-medium">{{ $message }}</p> @enderror
                                    </div>
                                </div>
                            </div>

                            {{-- Baris Kasar --}}
                            <div class="bg-slate-50 rounded-lg p-3 space-y-2">
                                <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest">Kasar</p>
                                <div class="flex gap-3">
                                    <div class="flex-1 space-y-1">
                                        <label class="text-[10px] text-slate-400 font-bold uppercase">Jumlah (sak)</label>
                                        <input type="number" wire:model="jumlah_kasar" placeholder="0" class="w-full px-3 py-2 text-sm rounded-lg border border-slate-300 focus:ring-2 focus:ring-amber-400 outline-none">
                                        @error('jumlah_kasar') <p class="text-xs text-rose-500 font-medium">{{ $message }}</p> @enderror
                                    </div>
                                    <div class="flex-1 space-y-1">
                                        <label class="text-[10px] text-slate-400 font-bold uppercase">Harga / sak (Rp)</label>
                                        <input type="number" wire:model="harga_kasar" placeholder="0" class="w-full px-3 py-2 text-sm rounded-lg border border-slate-300 focus:ring-2 focus:ring-amber-400 outline-none">
                                        @error('harga_kasar') <p class="text-xs text-rose-500 font-medium">{{ $message }}</p> @enderror
                                    </div>
                                </div>
                            </div>

                            {{-- Ringkasan total --}}
                            @php
                                $totalSak   = (int)($jumlah_halus ?? 0) + (int)($jumlah_kasar ?? 0);
                                $totalEstimasi = ((int)($jumlah_halus ?? 0) * (int)($harga_halus ?? 0))
                                              + ((int)($jumlah_kasar ?? 0) * (int)($harga_kasar ?? 0));
                            @endphp
                            @if($totalSak > 0)
                                <div class="flex items-center justify-between text-xs px-1">
                                    <span class="text-slate-400 font-medium">Total: <strong class="text-slate-700">{{ $totalSak }} sak</strong></span>
                                    <span class="text-indigo-600 font-black">Rp{{ number_format($totalEstimasi, 0, ',', '.') }}</span>
                                </div>
                            @endif
                        </div>
                    @else
                        {{-- Produk lain: 1 field jumlah biasa --}}
                        <div class="space-y-1.5">
                            <label class="text-xs font-bold text-slate-700 uppercase tracking-wide">Jumlah / Volume</label>
                            <input type="number" wire:model="jumlah" class="w-full px-4 py-2 text-sm rounded-lg border border-slate-300 focus:ring-2 focus:ring-indigo-500 outline-none">
                            @error('jumlah') <p class="text-xs text-rose-500 font-medium">{{ $message }}</p> @enderror
                        </div>
                    @endif
                    <div class="space-y-1.5">
                        <label class="text-xs font-bold text-slate-700 uppercase tracking-wide">Durasi (hari) <span class="font-normal text-slate-400 normal-case">— untuk tipe sewa/carter</span></label>
                        <input type="number" wire:model="durasi" placeholder="Kosongkan jika bukan sewa" class="w-full px-4 py-2 text-sm rounded-lg border border-slate-300 focus:ring-2 focus:ring-indigo-500 outline-none">
                        @error('durasi') <p class="text-xs text-rose-500 font-medium">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="space-y-1.5">
                    <label class="text-xs font-bold text-slate-700 uppercase tracking-wide">Alamat Pengiriman</label>
                    <textarea wire:model="alamat_pengiriman" rows="2" placeholder="Alamat tujuan pengiriman" class="w-full px-4 py-2 text-sm rounded-lg border border-slate-300 focus:ring-2 focus:ring-indigo-500 outline-none resize-none"></textarea>
                    @error('alamat_pengiriman') <p class="text-xs text-rose-500 font-medium">{{ $message }}</p> @enderror
                </div>

                <div class="space-y-1.5">
                    <label class="text-xs font-bold text-slate-700 uppercase tracking-wide">Catatan Tambahan <span class="font-normal text-slate-400 normal-case">(opsional)</span></label>
                    <textarea wire:model="catatan" rows="2" placeholder="Catatan khusus dari pelanggan, misal: antar pagi hari, minta plastik pelindung, dsb." class="w-full px-4 py-2 text-sm rounded-lg border border-slate-300 focus:ring-2 focus:ring-indigo-500 outline-none resize-none"></textarea>
                    @error('catatan') <p class="text-xs text-rose-500 font-medium">{{ $message }}</p> @enderror
                </div>

                <div class="flex justify-end gap-3 pt-4 border-t border-slate-100">
                    <button type="button" wire:click="closeForm" class="px-6 py-2 text-sm font-bold text-slate-600 hover:bg-slate-100 rounded-lg transition-all">Batal</button>
                    <button type="submit" class="px-8 py-2 bg-indigo-600 text-white rounded-lg text-sm font-bold hover:bg-indigo-700 shadow-sm transition-all">
                        {{ $editingId ? 'Simpan Perubahan' : 'Proses Pesanan' }}
                    </button>
                </div>
            </form>
        </div>
    @endif

    <!-- Data Table -->
    <div wire:poll.5s class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-slate-50 border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-500 uppercase tracking-widest">Nomor & Pelanggan</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-500 uppercase tracking-widest">Produk & Qty</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-500 uppercase tracking-widest">Total</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-500 uppercase tracking-widest text-center">Status Pesanan</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-500 uppercase tracking-widest text-center">Status Bayar</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-500 uppercase tracking-widest text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($this->pesanans as $pesanan)
                        @php
                            $statusMap = [
                                'pending'        => ['label' => 'Menunggu',     'class' => 'bg-amber-100 text-amber-700 border-amber-200'],
                                'dalam_antrian'  => ['label' => 'Dalam Antrian','class' => 'bg-blue-100 text-blue-700 border-blue-200'],
                                'diproses'       => ['label' => 'Diproses',     'class' => 'bg-indigo-100 text-indigo-700 border-indigo-200'],
                                'terkirim'       => ['label' => 'Terkirim',     'class' => 'bg-teal-100 text-teal-700 border-teal-200'],
                                'rejected'       => ['label' => 'Ditolak',      'class' => 'bg-rose-100 text-rose-700 border-rose-200'],
                            ];
                            $payMap = [
                                'belum_dibayar'  => ['label' => 'Belum Dibayar', 'class' => 'bg-orange-100 text-orange-700 border-orange-200'],
                                'telah_dibayar'  => ['label' => 'Telah Dibayar', 'class' => 'bg-emerald-100 text-emerald-700 border-emerald-200'],
                            ];
                            $currentStatus = $statusMap[$pesanan->status] ?? ['label' => $pesanan->status, 'class' => 'bg-slate-100 text-slate-600 border-slate-200'];
                            $currentPay    = $payMap[$pesanan->payment_status] ?? ['label' => ($pesanan->payment_status ?? '-'), 'class' => 'bg-slate-100 text-slate-500 border-slate-200'];
                        @endphp
                        <tr wire:key="p-{{ $pesanan->id }}" wire:click="showDetail({{ $pesanan->id }})" class="hover:bg-indigo-50/40 transition-colors cursor-pointer group">
                            <td class="px-6 py-4">
                                <div class="font-bold text-slate-900">#{{ $pesanan->nomor }}</div>
                                <div class="text-xs text-slate-500 mt-0.5">{{ $pesanan->nama }}</div>
                                <div class="mt-2">
                                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $pesanan->no_whatsapp) }}" target="_blank" class="inline-flex items-center text-[10px] font-bold text-emerald-600 hover:text-emerald-700 gap-1">
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.479-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"></path></svg>
                                        {{ $pesanan->no_whatsapp }}
                                    </a>
                                    @if($pesanan->alamat_penjemputan && str_contains($pesanan->alamat_penjemputan, ','))
                                        <span class="text-slate-300 mx-1">|</span>
                                        <a href="https://www.google.com/maps?q={{ $pesanan->alamat_penjemputan }}" target="_blank" class="inline-flex items-center text-[10px] font-bold text-indigo-600 hover:text-indigo-700 gap-1" title="Buka Titik Pengiriman di Google Maps">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path></svg>
                                            Peta (GPS)
                                        </a>
                                    @elseif($pesanan->alamat_pengiriman && $pesanan->alamat_pengiriman !== '-')
                                        <span class="text-slate-300 mx-1">|</span>
                                        <a href="https://www.google.com/maps?q={{ urlencode($pesanan->alamat_pengiriman) }}" target="_blank" class="inline-flex items-center text-[10px] font-bold text-indigo-600 hover:text-indigo-700 gap-1" title="Cari Alamat di Google Maps">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                                            Cari di Peta
                                        </a>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-semibold text-slate-700">{{ $pesanan->produk ? $pesanan->produk->nama : '-' }}</div>
                                <div class="text-[10px] font-bold text-slate-400 mt-0.5 uppercase">{{ $pesanan->jumlah }} Units | {{ $pesanan->tipe }}</div>
                                @if($pesanan->description)
                                    <div class="text-[9px] text-slate-500 mt-1 leading-tight max-w-[200px]">{{ $pesanan->description }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-bold text-slate-900">
                                    @if($pesanan->total_harga)
                                        Rp{{ number_format($pesanan->total_harga, 0, ',', '.') }}
                                    @else
                                        -
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold border {{ $currentStatus['class'] }} uppercase tracking-wider">
                                    {{ $currentStatus['label'] }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($pesanan->payment_status)
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold border {{ $currentPay['class'] }} uppercase tracking-wider">
                                        {{ $currentPay['label'] }}
                                    </span>
                                @else
                                    <span class="text-[10px] text-slate-300 italic">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex justify-center items-center gap-2 flex-wrap">
                                    @if(isset($isWorkerView))
                                        {{-- Worker Actions --}}
                                        @if($pesanan->status === 'dalam_antrian')
                                            <button type="button" wire:click="proseskan({{ $pesanan->id }})" wire:key="btn-proses-{{ $pesanan->id }}" class="px-3 py-1.5 bg-indigo-600 text-white text-[10px] font-bold rounded-lg hover:bg-indigo-700 transition-all uppercase tracking-widest shadow-sm">Proses</button>
                                        @elseif($pesanan->status === 'diproses')
                                            <button type="button" wire:click="konfirmasiKirim({{ $pesanan->id }})" wire:key="btn-kirim-{{ $pesanan->id }}" class="px-3 py-1.5 bg-teal-600 text-white text-[10px] font-bold rounded-lg hover:bg-teal-700 transition-all uppercase tracking-widest shadow-sm">Konfirmasi Kirim</button>
                                        @elseif($pesanan->status === 'terkirim' && $pesanan->payment_status === 'belum_dibayar')
                                            <button type="button" wire:click="openPaymentModal({{ $pesanan->id }}, 'cod')" wire:key="btn-cod-{{ $pesanan->id }}" class="px-3 py-1.5 bg-emerald-600 text-white text-[10px] font-bold rounded-lg hover:bg-emerald-700 transition-all uppercase tracking-widest shadow-sm">Konfirmasi COD</button>
                                        @elseif($pesanan->payment_status === 'telah_dibayar')
                                            <span class="text-[10px] font-black text-emerald-600 uppercase italic tracking-wider">Lunas ✓</span>
                                        @else
                                            <span class="text-[10px] font-black text-slate-400 uppercase italic tracking-wider">—</span>
                                        @endif
                                    @else
                                        {{-- Admin Actions --}}
                                        @if($pesanan->status === 'pending')
                                            <button type="button" title="Konfirmasi Pesanan" wire:click="acceptPesanan({{ $pesanan->id }})" wire:key="btn-accept-{{ $pesanan->id }}" class="p-2 bg-emerald-50 text-emerald-600 rounded-lg hover:bg-emerald-600 hover:text-white transition-all shadow-sm border border-emerald-100">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                            </button>
                                            <button type="button" title="Tolak Pesanan" wire:click="openRejectModal({{ $pesanan->id }})" wire:key="btn-reject-{{ $pesanan->id }}" class="p-2 bg-rose-50 text-rose-600 rounded-lg hover:bg-rose-600 hover:text-white transition-all shadow-sm border border-rose-100">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                            </button>
                                        @elseif(in_array($pesanan->status, ['dalam_antrian','diproses','terkirim']) && $pesanan->payment_status === 'belum_dibayar')
                                            {{-- Flow 4: Admin konfirmasi pembayaran di kantor --}}
                                            <button type="button" wire:click="openPaymentModal({{ $pesanan->id }}, 'admin')" wire:key="btn-pay-admin-{{ $pesanan->id }}" class="px-3 py-1.5 bg-emerald-600 text-white text-[10px] font-bold rounded-lg hover:bg-emerald-700 transition-all uppercase tracking-widest shadow-sm" title="Konfirmasi bayar di kantor (Flow 4)">
                                                Konfirmasi Bayar
                                            </button>
                                            @if(!$this->readonly)
                                                <button type="button" wire:click="editPesanan({{ $pesanan->id }})" wire:key="btn-edit-{{ $pesanan->id }}" class="p-2 bg-slate-50 text-slate-600 rounded-lg hover:bg-slate-600 hover:text-white transition-all shadow-sm border border-slate-100">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                                </button>
                                            @endif
                                        @elseif($pesanan->payment_status === 'telah_dibayar')
                                            <span class="text-[10px] font-black text-emerald-600 uppercase italic tracking-wider">Lunas ✓</span>
                                        @elseif($pesanan->status === 'rejected')
                                            <span class="text-[10px] font-black text-rose-400 uppercase italic tracking-wider">Ditolak</span>
                                        @else
                                            @if(!$this->readonly)
                                                <button type="button" wire:click="editPesanan({{ $pesanan->id }})" wire:key="btn-edit-{{ $pesanan->id }}" class="p-2 bg-slate-50 text-slate-600 rounded-lg hover:bg-slate-600 hover:text-white transition-all shadow-sm border border-slate-100">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                                </button>
                                            @endif
                                        @endif
                                        @if(!$this->readonly && $pesanan->status === 'pending')
                                            <button type="button" wire:click="deletePesanan({{ $pesanan->id }})" wire:key="btn-del-{{ $pesanan->id }}" class="p-2 bg-rose-50 text-rose-500 rounded-lg hover:bg-rose-600 hover:text-white transition-all shadow-sm border border-rose-100" onclick="return confirm('Yakin hapus pesanan ini?')">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        @endif
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-slate-400 italic font-medium">Belum ada pesanan yang masuk.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 bg-slate-50 border-t border-slate-100">
            <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                <div class="text-[11px] text-slate-500 uppercase tracking-widest">Halaman Pesanan</div>
                <div class="text-sm text-slate-600">
                    {{ $this->pesanans->links() }}
                </div>
            </div>
        </div>
        <div class="px-6 py-3 bg-slate-50 border-t border-slate-100 flex items-center gap-2">
            <svg class="w-3 h-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <p class="text-[10px] text-slate-400 font-medium italic">Admin: Konfirmasi = masuk antrian & pemasukan pending. Worker: Proses → Kirim → COD. Admin/User: bayar via Midtrans atau konfirmasi manual.</p>
        </div>
    </div>

    <!-- REJECT MODAL -->
    @if($showRejectModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 backdrop-blur-sm"
         x-data @keydown.escape.window="$wire.closeRejectModal()">
        <div @click.away="$wire.closeRejectModal()" class="bg-white rounded-xl shadow-xl w-full max-w-md p-6 transform transition-all animate-in zoom-in-95 duration-200">
            <div class="flex justify-between items-center mb-5">
                <h3 class="text-lg font-bold text-slate-900">Pilih Alasan Penolakan</h3>
                <button wire:click="closeRejectModal" class="text-slate-400 hover:text-slate-500">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            
            <div class="space-y-3">
                <button wire:click="confirmReject('jarak')" class="w-full text-left p-4 rounded-lg border border-slate-200 hover:border-indigo-500 hover:bg-indigo-50 transition-all flex items-start gap-3 group">
                    <div class="mt-0.5 text-slate-400 group-hover:text-indigo-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path></svg>
                    </div>
                    <div>
                        <div class="font-bold text-slate-900 group-hover:text-indigo-900">Jarak Terlalu Jauh</div>
                        <div class="text-xs text-slate-500 mt-1 line-clamp-2">Alamat pengiriman berada di luar jangkauan pengiriman kami.</div>
                    </div>
                </button>
                
                <button wire:click="confirmReject('sedikit')" class="w-full text-left p-4 rounded-lg border border-slate-200 hover:border-indigo-500 hover:bg-indigo-50 transition-all flex items-start gap-3 group">
                    <div class="mt-0.5 text-slate-400 group-hover:text-indigo-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"></path></svg>
                    </div>
                    <div>
                        <div class="font-bold text-slate-900 group-hover:text-indigo-900">Item Terlalu Sedikit</div>
                        <div class="text-xs text-slate-500 mt-1 line-clamp-2">Pesanan di bawah batas minimal untuk produk yang dipilih.</div>
                    </div>
                </button>
                
                <button wire:click="confirmReject('banyak')" class="w-full text-left p-4 rounded-lg border border-slate-200 hover:border-indigo-500 hover:bg-indigo-50 transition-all flex items-start gap-3 group">
                    <div class="mt-0.5 text-slate-400 group-hover:text-indigo-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 11l7-7 7 7M5 19l7-7 7 7"></path></svg>
                    </div>
                    <div>
                        <div class="font-bold text-slate-900 group-hover:text-indigo-900">Item Terlalu Banyak</div>
                        <div class="text-xs text-slate-500 mt-1 line-clamp-2">Pesanan di atas batas maksimal untuk produk yang dipilih.</div>
                    </div>
                </button>
            </div>
        </div>
    </div>
    @endif

    {{-- PAYMENT MODAL (Cicilan / Lunas) --}}
    @if($showPaymentModal)
    <div class="fixed inset-0 z-[60] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm"
         x-data @keydown.escape.window="$wire.closePaymentModal()">
        <div @click.away="$wire.closePaymentModal()" class="bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden animate-in zoom-in-95 duration-200">
            <div class="bg-indigo-600 px-6 py-5 flex items-center justify-between text-white">
                <div>
                    <h3 class="text-lg font-black tracking-tight">Konfirmasi Pembayaran</h3>
                    <p class="text-[11px] text-indigo-200 uppercase tracking-widest font-bold mt-0.5">Pesanan #{{ $paymentNomor }}</p>
                </div>
                <button wire:click="closePaymentModal" class="p-2 hover:bg-white/10 rounded-xl transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            
            <form wire:submit="simpanPembayaran" class="p-6 space-y-5">
                <div class="bg-slate-50 p-4 rounded-xl border border-slate-100 flex items-center justify-between">
                    <div>
                        @if($paymentKekurangan < $paymentTotalHarga)
                            <p class="text-[10px] font-bold text-rose-500 uppercase">Sisa Kekurangan</p>
                            <p class="text-lg font-black text-rose-600">Rp{{ number_format($paymentKekurangan, 0, ',', '.') }}</p>
                            <p class="text-[9px] font-bold text-slate-400 uppercase mt-0.5">Dari Total: Rp{{ number_format($paymentTotalHarga, 0, ',', '.') }}</p>
                        @else
                            <p class="text-[10px] font-bold text-slate-400 uppercase">Total Tagihan</p>
                            <p class="text-lg font-black text-slate-800">Rp{{ number_format($paymentTotalHarga, 0, ',', '.') }}</p>
                        @endif
                    </div>
                    <div class="text-right">
                        <p class="text-[10px] font-bold text-slate-400 uppercase">Tipe</p>
                        <p class="text-xs font-bold text-indigo-600 uppercase">{{ $paymentTipe === 'cod' ? 'Cash on Delivery' : 'Bayar di Kantor' }}</p>
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="text-xs font-bold text-slate-700 uppercase tracking-wide">Nominal yang Dibayar (Rp)</label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 font-medium">Rp</span>
                        <input type="number" wire:model="paymentJumlah" placeholder="Masukkan jumlah uang" class="w-full pl-10 pr-4 py-3 text-lg font-black text-slate-800 rounded-xl border border-slate-300 focus:ring-2 focus:ring-indigo-500 outline-none" min="1" autofocus>
                    </div>
                    <p class="text-[10px] text-slate-500 font-medium mt-1">
                        Jika kurang dari total, akan dicatat sebagai <strong>cicilan/DP</strong>. Jika sama atau lebih, pesanan dianggap <strong>lunas</strong>.
                    </p>
                    @error('paymentJumlah') <p class="text-xs text-rose-500 font-bold mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="pt-4 flex gap-3">
                    <button type="button" wire:click="closePaymentModal" class="flex-1 py-3 text-sm font-bold text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-xl transition-all">Batal</button>
                    <button type="submit" class="flex-1 py-3 text-sm font-bold text-white bg-indigo-600 hover:bg-indigo-700 rounded-xl transition-all shadow-md">Simpan Pembayaran</button>
                </div>
            </form>
        </div>
    </div>
    @endif

    {{-- DETAIL MODAL --}}
    @if($this->selectedPesanan)
    @php
        $sp = $this->selectedPesanan;
        $statusMap = [
            'pending'       => ['label' => 'Menunggu Konfirmasi', 'class' => 'bg-amber-100 text-amber-700'],
            'dalam_antrian' => ['label' => 'Dalam Antrian',       'class' => 'bg-blue-100 text-blue-700'],
            'diproses'      => ['label' => 'Sedang Diproses',     'class' => 'bg-indigo-100 text-indigo-700'],
            'terkirim'      => ['label' => 'Terkirim',            'class' => 'bg-teal-100 text-teal-700'],
            'rejected'      => ['label' => 'Ditolak',             'class' => 'bg-rose-100 text-rose-700'],
        ];
        $payMap = [
            'belum_dibayar' => ['label' => 'Belum Dibayar', 'class' => 'bg-orange-100 text-orange-700'],
            'telah_dibayar' => ['label' => 'Telah Dibayar', 'class' => 'bg-emerald-100 text-emerald-700'],
        ];
        $statusInfo = $statusMap[$sp->status] ?? ['label' => $sp->status, 'class' => 'bg-slate-100 text-slate-700'];
        $payInfo    = $payMap[$sp->payment_status] ?? null;
    @endphp
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-8"
         x-data @keydown.escape.window="$wire.closeDetail()">
        {{-- Backdrop --}}
        <div wire:click="closeDetail" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm animate-in fade-in duration-200"></div>

        {{-- Modal --}}
        <div id="detail-pesanan-card" class="relative bg-white w-full max-w-2xl rounded-2xl shadow-2xl overflow-hidden animate-in zoom-in-95 duration-200 max-h-[90vh] overflow-y-auto">
            {{-- Header --}}
            <div class="bg-slate-900 px-8 py-6 text-white flex items-start justify-between gap-4">
                <div>
                    <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-slate-400 mb-1">Detail Pesanan</p>
                    <h2 class="text-2xl font-black tracking-tight">#{{ $sp->nomor }}</h2>
                    <div class="flex flex-wrap gap-2 mt-3">
                        <span class="inline-flex items-center px-3 py-1 rounded-lg text-[10px] font-bold uppercase tracking-wider {{ $statusInfo['class'] }}">
                            {{ $statusInfo['label'] }}
                        </span>
                        @if($payInfo)
                            <span class="inline-flex items-center px-3 py-1 rounded-lg text-[10px] font-bold uppercase tracking-wider {{ $payInfo['class'] }}">
                                {{ $payInfo['label'] }}
                            </span>
                        @endif
                    </div>
                </div>
                <button id="detail-modal-close-btn" wire:click="closeDetail" class="p-2 hover:bg-white/10 rounded-xl transition-colors shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            {{-- Body --}}
            <div class="p-8 space-y-6">

                {{-- Alasan Penolakan --}}
                @if($sp->status === 'rejected' && $sp->alasan_penolakan)
                <div class="bg-rose-50 border border-rose-200 rounded-xl p-4 flex items-start gap-3">
                    <svg class="w-5 h-5 text-rose-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <div>
                        <p class="text-xs font-bold text-rose-800 mb-1">Alasan Penolakan</p>
                        <p class="text-xs text-rose-700 whitespace-pre-line leading-relaxed">{{ $sp->alasan_penolakan }}</p>
                    </div>
                </div>
                @endif

                {{-- Grid Info --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">

                    {{-- Pelanggan --}}
                    <div class="space-y-3">
                        <h3 class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Informasi Pelanggan</h3>
                        <div class="bg-slate-50 rounded-xl p-4 space-y-2">
                            <div>
                                <p class="text-[10px] text-slate-400 font-bold uppercase">Nama</p>
                                <p class="text-sm font-bold text-slate-800">{{ $sp->nama }}</p>
                            </div>
                            <div>
                                <p class="text-[10px] text-slate-400 font-bold uppercase">WhatsApp</p>
                                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $sp->no_whatsapp) }}" target="_blank"
                                   class="text-sm font-bold text-emerald-600 hover:text-emerald-700 flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"></path></svg>
                                    {{ $sp->no_whatsapp }}
                                </a>
                            </div>
                            @if($sp->user)
                            <div>
                                <p class="text-[10px] text-slate-400 font-bold uppercase">Akun</p>
                                <p class="text-xs text-slate-600 font-medium">{{ $sp->user->name }} ({{ $sp->user->email }})</p>
                            </div>
                            @endif
                        </div>
                    </div>

                    {{-- Produk --}}
                    <div class="space-y-3">
                        <h3 class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Informasi Produk</h3>
                        <div class="bg-slate-50 rounded-xl p-4 space-y-2">
                            <div class="flex items-center gap-3">
                                @if($sp->produk && $sp->produk->gambar)
                                    <img src="{{ asset('storage/' . $sp->produk->gambar) }}" class="w-12 h-12 rounded-lg object-cover border border-slate-200">
                                @else
                                    <div class="w-12 h-12 rounded-lg bg-slate-200 flex items-center justify-center text-slate-400">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                                    </div>
                                @endif
                                <div>
                                    <p class="text-sm font-bold text-slate-800">{{ $sp->produk ? $sp->produk->nama : $sp->nama }}</p>
                                    <p class="text-[10px] font-bold text-indigo-600 uppercase">{{ $sp->tipe }}</p>
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-2 pt-2 border-t border-slate-100">
                                <div>
                                    <p class="text-[10px] text-slate-400 font-bold uppercase">Jumlah</p>
                                    <p class="text-sm font-bold text-slate-800">{{ number_format($sp->jumlah) }} {{ $sp->produk->satuan ?? 'unit' }}</p>
                                </div>
                                <div>
                                    <p class="text-[10px] text-slate-400 font-bold uppercase">Harga Satuan</p>
                                    <p class="text-sm font-bold text-slate-800">Rp{{ number_format($sp->harga ?? 0, 0, ',', '.') }}</p>
                                </div>
                                @if($sp->durasi)
                                <div>
                                    <p class="text-[10px] text-slate-400 font-bold uppercase">Durasi</p>
                                    <p class="text-sm font-bold text-slate-800">{{ $sp->durasi }} hari</p>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Pengiriman --}}
                    <div class="space-y-3">
                        <h3 class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Pengiriman</h3>
                        <div class="bg-slate-50 rounded-xl p-4 space-y-2">
                            <div>
                                <p class="text-[10px] text-slate-400 font-bold uppercase">Alamat Tujuan</p>
                                <p class="text-xs font-medium text-slate-700 leading-relaxed">{{ $sp->alamat_pengiriman }}</p>
                            </div>
                            @if($sp->jarak)
                            <div>
                                <p class="text-[10px] text-slate-400 font-bold uppercase">Jarak</p>
                                <p class="text-xs font-bold text-slate-800">{{ $sp->jarak }} km</p>
                            </div>
                            @endif
                        </div>
                    </div>

                    {{-- Pembayaran --}}
                    <div class="space-y-3">
                        <h3 class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Pembayaran</h3>
                        <div class="bg-slate-50 rounded-xl p-4 space-y-2">
                            <div>
                                <p class="text-[10px] text-slate-400 font-bold uppercase">Total Tagihan</p>
                                <p class="text-2xl font-black text-indigo-600">
                                    Rp{{ number_format($sp->total_harga ?? 0, 0, ',', '.') }}
                                </p>
                            </div>
                            @if($sp->paid_at)
                            <div>
                                <p class="text-[10px] text-slate-400 font-bold uppercase">Dibayar Pada</p>
                                <p class="text-xs font-bold text-emerald-600">{{ $sp->paid_at->format('d M Y, H:i') }}</p>
                            </div>
                            @endif
                            <div>
                                <p class="text-[10px] text-slate-400 font-bold uppercase">Tanggal Pesanan</p>
                                <p class="text-xs font-medium text-slate-600">{{ $sp->created_at->format('d M Y, H:i') }}</p>
                            </div>
                        </div>
                    </div>

                </div>

                {{-- Catatan --}}
                @if($sp->catatan)
                <div class="space-y-2">
                    <h3 class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Catatan</h3>
                    <div class="bg-amber-50 border border-amber-100 rounded-xl p-4">
                        <p class="text-xs text-slate-700 whitespace-pre-line leading-relaxed font-medium">{{ $sp->catatan }}</p>
                    </div>
                </div>
                @endif

            </div>

            {{-- Footer --}}
            <div id="detail-modal-footer" class="px-8 py-5 bg-slate-50 border-t border-slate-100 flex items-center justify-between gap-3">
                <button
                    id="btn-download-detail"
                    type="button"
                    onclick="downloadDetailPNG('{{ $sp->nomor }}')"
                    class="inline-flex items-center gap-2 px-5 py-2.5 bg-indigo-600 text-white text-sm font-bold rounded-xl hover:bg-indigo-700 transition-all shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                              d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                    </svg>
                    Download PNG
                </button>
                <button wire:click="closeDetail" class="px-6 py-2.5 bg-slate-900 text-white text-sm font-bold rounded-xl hover:bg-slate-700 transition-all">
                    Tutup
                </button>
            </div>
        </div>
    </div>
    @endif


{{-- html2canvas: download detail pesanan sebagai PNG --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js" integrity="sha512-BNaRQnYJYiPSqHHDb58B0yaPfCu+Wgds8Gp/gU33kqBtgNS4tSPHuGibyoeqMV/TJlSKda6FXzoEyYGjTe+vXA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
async function downloadDetailPNG(nomor) {
    const card   = document.getElementById('detail-pesanan-card');
    const footer = document.getElementById('detail-modal-footer');
    const closeX = document.getElementById('detail-modal-close-btn');
    if (!card) return;

    // 1. Sembunyikan elemen yang tidak boleh ikut ter-capture
    if (footer) footer.style.display = 'none';
    if (closeX) closeX.style.display = 'none';

    // 2. Hapus batasan tinggi & scroll agar seluruh konten ter-render penuh
    const prevMaxH    = card.style.maxHeight;
    const prevOverflow = card.style.overflow;
    const prevHeight  = card.style.height;
    card.style.maxHeight = 'none';
    card.style.overflow  = 'visible';
    card.style.height    = 'auto';

    // Tunggu layout reflow selesai
    await new Promise(r => setTimeout(r, 80));

    try {
        const W = card.scrollWidth;
        const H = card.scrollHeight;

        const canvas = await html2canvas(card, {
            scale           : 2,
            useCORS         : true,
            allowTaint      : true,
            logging         : false,
            backgroundColor : '#ffffff',
            width           : W,
            height          : H,
            scrollX         : 0,
            scrollY         : 0,
            windowWidth     : W,
            windowHeight    : H,
        });

        const link    = document.createElement('a');
        link.download = `pesanan-${nomor}.png`;
        link.href     = canvas.toDataURL('image/png');
        link.click();
    } finally {
        // 3. Kembalikan semua ke kondisi semula
        card.style.maxHeight = prevMaxH;
        card.style.overflow  = prevOverflow;
        card.style.height    = prevHeight;
        if (footer) footer.style.display = '';
        if (closeX) closeX.style.display = '';
    }
}
</script>

</div>