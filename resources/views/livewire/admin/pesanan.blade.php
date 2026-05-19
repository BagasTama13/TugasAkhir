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
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Total Pesanan</p>
            <p class="text-2xl font-bold text-slate-900 mt-1">{{ $this->pesanans->count() }}</p>
        </div>
        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm border-l-4 border-l-amber-500">
            <p class="text-[10px] font-bold text-amber-500 uppercase tracking-widest">Menunggu</p>
            <p class="text-2xl font-bold text-slate-900 mt-1">{{ $this->pesanans->where('status', 'pending')->count() }}</p>
        </div>
        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm border-l-4 border-l-emerald-500">
            <p class="text-[10px] font-bold text-emerald-500 uppercase tracking-widest">Diterima</p>
            <p class="text-2xl font-bold text-slate-900 mt-1">{{ $this->pesanans->where('status', 'accepted')->count() }}</p>
        </div>
        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm border-l-4 border-l-blue-500">
            <p class="text-[10px] font-bold text-blue-500 uppercase tracking-widest">Terkirim</p>
            <p class="text-2xl font-bold text-slate-900 mt-1">{{ $this->pesanans->where('status', 'delivered')->count() }}</p>
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
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div class="space-y-1.5">
                        <label class="text-xs font-bold text-slate-700 uppercase tracking-wide">Nomor Pesanan</label>
                        <input type="text" wire:model="nomor" placeholder="PSN-XXXX" class="w-full px-4 py-2 text-sm rounded-lg border border-slate-300 focus:ring-2 focus:ring-indigo-500 outline-none" @if($editingId) disabled @endif>
                        @error('nomor') <p class="text-xs text-rose-500 font-medium">{{ $message }}</p> @enderror
                    </div>
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
                                <option value="{{ $produk->id }}">{{ $produk->nama }}</option>
                            @endforeach
                        </select>
                        @error('produk_id') <p class="text-xs text-rose-500 font-medium">{{ $message }}</p> @enderror
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-xs font-bold text-slate-700 uppercase tracking-wide">Jumlah / Volume</label>
                        <input type="number" wire:model="jumlah" class="w-full px-4 py-2 text-sm rounded-lg border border-slate-300 focus:ring-2 focus:ring-indigo-500 outline-none">
                        @error('jumlah') <p class="text-xs text-rose-500 font-medium">{{ $message }}</p> @enderror
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-xs font-bold text-slate-700 uppercase tracking-wide">Tipe Layanan</label>
                        <input type="text" wire:model="tipe" placeholder="Reguler/Express" class="w-full px-4 py-2 text-sm rounded-lg border border-slate-300 focus:ring-2 focus:ring-indigo-500 outline-none">
                        @error('tipe') <p class="text-xs text-rose-500 font-medium">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-1.5">
                        <label class="text-xs font-bold text-slate-700 uppercase tracking-wide">Lokasi Penjemputan</label>
                        <textarea wire:model="alamat_penjemputan" rows="2" class="w-full px-4 py-2 text-sm rounded-lg border border-slate-300 focus:ring-2 focus:ring-indigo-500 outline-none resize-none"></textarea>
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-xs font-bold text-slate-700 uppercase tracking-wide">Alamat Pengiriman</label>
                        <textarea wire:model="alamat_pengiriman" rows="2" class="w-full px-4 py-2 text-sm rounded-lg border border-slate-300 focus:ring-2 focus:ring-indigo-500 outline-none resize-none"></textarea>
                    </div>
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
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-slate-50 border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-500 uppercase tracking-widest">Nomor & Pelanggan</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-500 uppercase tracking-widest">Produk & Qty</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-500 uppercase tracking-widest">Alamat Pengiriman</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-500 uppercase tracking-widest text-center">Status</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-500 uppercase tracking-widest text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($this->pesanans as $pesanan)
                        <tr wire:key="p-{{ $pesanan->id }}" class="hover:bg-slate-50 transition-colors {{ $pesanan->status !== 'pending' ? 'bg-slate-50/50 opacity-80' : '' }}">
                            <td class="px-6 py-4">
                                <div class="font-bold text-slate-900">#{{ $pesanan->nomor }}</div>
                                <div class="text-xs text-slate-500 mt-0.5">{{ $pesanan->nama }}</div>
                                <div class="mt-2">
                                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $pesanan->no_whatsapp) }}" target="_blank" class="inline-flex items-center text-[10px] font-bold text-emerald-600 hover:text-emerald-700 gap-1">
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.479-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"></path></svg>
                                        {{ $pesanan->no_whatsapp }}
                                    </a>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-semibold text-slate-700">{{ $pesanan->produk ? $pesanan->produk->nama : '-' }}</div>
                                <div class="text-[10px] font-bold text-slate-400 mt-0.5 uppercase">{{ $pesanan->jumlah }} Units | {{ $pesanan->tipe }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-xs text-slate-600 line-clamp-2 max-w-[200px]">{{ $pesanan->alamat_pengiriman }}</div>
                            </td>
                            <td class="px-6 py-4 text-center">
                                @php
                                    $statusMap = [
                                        'pending' => ['label' => 'Menunggu', 'class' => 'bg-amber-100 text-amber-700 border-amber-200'],
                                        'accepted' => ['label' => 'Diterima', 'class' => 'bg-emerald-100 text-emerald-700 border-emerald-200'],
                                        'rejected' => ['label' => 'Ditolak', 'class' => 'bg-rose-100 text-rose-700 border-rose-200'],
                                        'delivered' => ['label' => 'Terkirim', 'class' => 'bg-blue-100 text-blue-700 border-blue-200'],
                                    ];
                                    $currentStatus = $statusMap[$pesanan->status] ?? ['label' => $pesanan->status, 'class' => 'bg-slate-100 text-slate-600 border-slate-200'];
                                @endphp
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold border {{ $currentStatus['class'] }} uppercase tracking-wider">
                                    {{ $currentStatus['label'] }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex justify-center items-center gap-2">
                                    @if(!$this->readonly && !isset($isWorkerView))
                                        {{-- Admin Actions --}}
                                        @if($pesanan->status === 'pending')
                                            <button type="button" title="Terima Pesanan" wire:click="acceptPesanan({{ $pesanan->id }})" wire:key="btn-accept-{{ $pesanan->id }}" class="p-2 bg-emerald-50 text-emerald-600 rounded-lg hover:bg-emerald-600 hover:text-white transition-all shadow-sm border border-emerald-100 group">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                            </button>
                                            <button type="button" title="Tolak Pesanan" wire:click="rejectPesanan({{ $pesanan->id }})" wire:key="btn-reject-{{ $pesanan->id }}" class="p-2 bg-rose-50 text-rose-600 rounded-lg hover:bg-rose-600 hover:text-white transition-all shadow-sm border border-rose-100 group">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                            </button>
                                        @endif
                                    @elseif(isset($isWorkerView))
                                        {{-- Worker Actions --}}
                                        @if($pesanan->status === 'accepted')
                                            <button type="button" wire:click="markDelivered({{ $pesanan->id }})" wire:key="btn-kirim-{{ $pesanan->id }}" class="px-4 py-1.5 bg-indigo-600 text-white text-[10px] font-bold rounded-lg hover:bg-indigo-700 transition-all uppercase tracking-widest shadow-sm">Kirim</button>
                                        @else
                                            <span class="text-[10px] font-black text-slate-400 uppercase italic tracking-wider">Selesai</span>
                                        @endif
                                    @else
                                        <span class="text-[10px] font-black text-slate-400 uppercase italic tracking-wider">Hanya Baca</span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-slate-400 italic font-medium">Belum ada pesanan yang masuk hari ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-3 bg-slate-50 border-t border-slate-100 flex items-center gap-2">
            <svg class="w-3 h-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <p class="text-[10px] text-slate-400 font-medium italic">Pesanan yang sudah terkirim akan otomatis dicatat dalam Laporan Pemasukan.</p>
        </div>
    </div>
</div>