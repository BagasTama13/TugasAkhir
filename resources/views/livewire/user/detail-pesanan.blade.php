<div>
    <div class="user-card animate-fade-in-up">
        <div class="p-6 sm:p-8">
            <!-- Title -->
            <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-800 mb-8 tracking-tight">Form Pemesanan</h1>

            <form wire:submit.prevent="kirimPesanan" class="space-y-6">
                <!-- Nama Pembeli -->
                <div>
                    <label class="form-label">Nama Pembeli</label>
                    <input type="text" wire:model="nama_pembeli" class="form-input" placeholder="Masukkan nama lengkap">
                    @error('nama_pembeli') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                <!-- Product Selection -->
                <div>
                    <label class="form-label">Product</label>
                    <input type="text"
                           value="{{ $this->selectedProduk ? $this->selectedProduk->nama . ' - ' . $this->selectedProduk->jenis : '' }}"
                           class="form-input mb-3"
                           placeholder="Pilih produk dari daftar di bawah"
                           readonly>
                    @error('selectedProdukId') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror

                    <!-- Product Carousel -->
                    <div class="flex gap-3 overflow-x-auto pb-3 -mx-2 px-2 scrollbar-thin" style="scrollbar-width: thin;">
                        @foreach($this->produks as $produk)
                            <button type="button"
                                    wire:click="selectProduk({{ $produk->id }})"
                                    class="flex-shrink-0 w-24 rounded-xl border-2 transition-all duration-300 overflow-hidden
                                           {{ $selectedProdukId == $produk->id
                                               ? 'border-emerald-500 shadow-lg shadow-emerald-100 ring-2 ring-emerald-200 scale-105'
                                               : 'border-gray-200 hover:border-gray-300 hover:shadow-md' }}">
                                <!-- Product Thumbnail -->
                                <div class="h-16 bg-gradient-to-b from-gray-50 to-gray-100 flex items-center justify-center overflow-hidden">
                                    @if($produk->gambar)
                                        <img src="{{ asset('storage/' . $produk->gambar) }}"
                                             alt="{{ $produk->nama }}"
                                             class="w-full h-full object-cover">
                                    @else
                                        <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                    @endif
                                </div>
                                <!-- Product Info -->
                                <div class="p-2 text-center">
                                    <p class="text-[10px] font-bold text-gray-700 capitalize truncate">{{ $produk->nama }}</p>
                                    <p class="text-[9px] text-gray-500 truncate">{{ $produk->jenis }}</p>
                                    <p class="text-[9px] font-semibold {{ $selectedProdukId == $produk->id ? 'text-emerald-600' : 'text-gray-500' }}">
                                        Rp {{ number_format($produk->harga, 0, ',', '.') }}
                                    </p>
                                </div>
                            </button>
                        @endforeach
                    </div>
                </div>

                <!-- Jumlah Product -->
                <div>
                    <label class="form-label">Jumlah Product</label>
                    <input type="number" wire:model="jumlah" class="form-input" placeholder="Masukkan jumlah" min="1">
                    @error('jumlah') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                <!-- Alamat -->
                <div>
                    <label class="form-label">Alamat</label>
                    <textarea wire:model="alamat" class="form-input" rows="3" placeholder="Masukkan alamat lengkap pengiriman"></textarea>
                    @error('alamat') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                <!-- Catatan -->
                <div>
                    <label class="form-label">Catatan</label>
                    <textarea wire:model="catatan" class="form-input" rows="2" placeholder="Catatan tambahan (opsional)"></textarea>
                    @error('catatan') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                <!-- No. WhatsApp -->
                <div>
                    <label class="form-label">No. WhatsApp</label>
                    <input type="text" wire:model="no_whatsapp" class="form-input" placeholder="08xxxxxxxxxx">
                    @error('no_whatsapp') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn-primary w-full text-center text-base py-4 flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                    </svg>
                    Kirim Pesanan
                </button>
            </form>
        </div>
    </div>
</div>
