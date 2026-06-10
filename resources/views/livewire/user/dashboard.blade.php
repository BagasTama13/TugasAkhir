<div class="space-y-8">
    <!-- Clean Welcome Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Halo, {{ explode(' ', auth()->user()->name)[0] }}!</h1>
            <p class="text-sm text-slate-500">Temukan material konstruksi terbaik untuk kebutuhan proyek Anda.</p>
        </div>
        <div class="flex items-center gap-4">
            <a href="{{ route('user.pesanan') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-50 text-indigo-600 rounded-lg border border-indigo-100 font-semibold text-sm hover:bg-indigo-100 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 11-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                Pesanan Saya ({{ $this->activeOrdersCount }})
            </a>
        </div>
    </div>

<!-- Simple Search & Filter -->
<div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm flex flex-col lg:flex-row gap-4 items-center">
    <div class="flex flex-wrap gap-2">
        <button wire:click="$set('category','all')" class="px-4 py-2 text-xs font-semibold rounded-lg border {{ $category === 'all' ? 'bg-indigo-600 text-white border-indigo-600' : 'bg-white text-slate-600 border-slate-300 hover:bg-slate-50' }} transition-all">
            Semua
        </button>
        <button wire:click="$set('category','bahan_bakar')" class="px-4 py-2 text-xs font-semibold rounded-lg border {{ $category === 'bahan_bakar' ? 'bg-indigo-600 text-white border-indigo-600' : 'bg-white text-slate-600 border-slate-300 hover:bg-slate-50' }} transition-all">
            Bahan Bakar
        </button>
        <button wire:click="$set('category','sewa_mobil')" class="px-4 py-2 text-xs font-semibold rounded-lg border {{ $category === 'sewa_mobil' ? 'bg-indigo-600 text-white border-indigo-600' : 'bg-white text-slate-600 border-slate-300 hover:bg-slate-50' }} transition-all">
            Sewa Mobil
        </button>
        <button wire:click="$set('category','bahan_bangunan')" class="px-4 py-2 text-xs font-semibold rounded-lg border {{ $category === 'bahan_bangunan' ? 'bg-indigo-600 text-white border-indigo-600' : 'bg-white text-slate-600 border-slate-300 hover:bg-slate-50' }} transition-all">
            Bahan Bangunan
        </button>
    </div>
</div>

    <!-- Product Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @forelse($this->products as $etalase)
            <div wire:key="{{ $etalase->id }}" class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden hover:shadow-md transition-shadow flex flex-col">
                <div class="h-48 bg-slate-100 relative">
                    @if($etalase->gambar)
                        <img src="{{ asset('storage/' . $etalase->gambar) }}" alt="{{ $etalase->nama }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-slate-400">
                            <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        </div>
                    @endif
                    <div class="absolute bottom-2 right-2 bg-indigo-600 text-white px-2 py-1 rounded text-sm font-bold shadow-sm">
                        Rp {{ number_format($etalase->harga, 0, ',', '.') }}/{{ $etalase->satuan }}
                    </div>
                </div>
                <div class="p-4 flex-1 flex flex-col">
                    <h3 class="font-bold text-slate-900 text-lg mb-1 capitalize">{{ $etalase->nama }}</h3>
                    <p class="text-xs text-slate-500 mb-4 line-clamp-2">
                        {{ $etalase->deskripsi ?? 'Material konstruksi berkualitas tinggi untuk menjamin kekokohan proyek bangunan Anda.' }}
                    </p>
                    <div class="mt-auto">
                        <a href="{{ route('user.pesanan.detail', ['produk' => $etalase->id]) }}" class="block w-full text-center py-2 bg-indigo-600 text-white text-sm font-bold rounded-lg hover:bg-indigo-700 transition-colors">
                            Pesan Sekarang
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full py-20 text-center">
                <p class="text-slate-400 italic">Produk tidak ditemukan.</p>
            </div>
        @endforelse
    </div>
</div>
