<div class="min-h-screen bg-[#F8FAFC] pb-12">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 mt-10">
        <div class="bg-white rounded-[3rem] shadow-xl border border-slate-200 overflow-hidden animate-in fade-in slide-in-from-bottom-8 duration-700">
            <div class="flex flex-col lg:flex-row">
                <!-- Left Column: Form -->
                <div class="lg:w-2/3 p-8 sm:p-12 border-r border-slate-100">
                    <div class="mb-10">
                        <h1 class="text-3xl font-display font-black text-slate-900 tracking-tight">Form Pemesanan</h1>
                        <p class="text-sm text-slate-500 mt-2 font-medium">Lengkapi rincian berikut untuk memesan material bangunan Anda.</p>
                    </div>

                    <form wire:submit.prevent="kirimPesanan" class="space-y-8">
                        <!-- Buyer Name -->
                        <div class="space-y-2">
                            <label class="text-xs font-bold text-slate-400 uppercase tracking-widest ml-1">Nama Lengkap Pembeli</label>
                            <input type="text" wire:model="nama_pembeli" class="w-full px-5 py-4 rounded-2xl border border-slate-200 focus:ring-2 focus:ring-indigo-500 outline-none transition-all font-medium text-slate-700 bg-slate-50 focus:bg-white" placeholder="Sesuai KTP / Identitas">
                            @error('nama_pembeli') <p class="text-[10px] text-rose-500 font-bold uppercase mt-1 ml-1">{{ $message }}</p> @enderror
                        </div>

                        <!-- Product Selection Context -->
                        <div class="bg-indigo-50/50 p-8 rounded-[2.5rem] border border-indigo-100/50">
                            <div class="flex items-center justify-between mb-6">
                                <label class="text-xs font-bold text-indigo-400 uppercase tracking-widest">Pilih Varian Produk</label>
                                @if($this->selectedProduk)
                                    <span class="px-2 py-0.5 bg-indigo-600 text-white text-[9px] font-bold rounded-md uppercase tracking-wider">Terpilih</span>
                                @endif
                            </div>

                            <!-- Horizontal Varian Selector -->
                            <div class="flex gap-4 overflow-x-auto pb-4 scrollbar-hide">
                                @forelse($this->produks as $produk)
                                    <button type="button"
                                            wire:key="variant-{{ $produk->id }}"
                                            wire:click="selectProduk({{ $produk->id }})"
                                            class="flex-shrink-0 w-32 rounded-2xl border-2 transition-all duration-300 overflow-hidden group bg-white
                                                   {{ $selectedProdukId == $produk->id
                                                       ? 'border-indigo-600 shadow-lg shadow-indigo-100 scale-105'
                                                       : 'border-transparent shadow-sm hover:border-slate-200' }}">
                                        <div class="h-20 bg-slate-100 overflow-hidden relative pointer-events-none">
                                            @if($produk->gambar)
                                                <img src="{{ asset('storage/' . $produk->gambar) }}" class="w-full h-full object-cover transition-transform group-hover:scale-110">
                                            @else
                                                <div class="h-full w-full flex items-center justify-center text-slate-300">
                                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                                </div>
                                            @endif
                                            @if($selectedProdukId == $produk->id)
                                                <div class="absolute inset-0 bg-indigo-600/10 flex items-center justify-center">
                                                    <div class="bg-indigo-600 text-white p-1 rounded-full shadow-lg">
                                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="p-3 text-center pointer-events-none">
                                            <p class="text-[10px] font-black text-slate-900 truncate mb-1">{{ $produk->jenis }}</p>
                                            <p class="text-[10px] font-bold text-indigo-600">Rp{{ number_format($produk->harga, 0, ',', '.') }}</p>
                                        </div>
                                    </button>
                                @empty
                                    <p class="text-xs font-bold text-indigo-400/80 italic text-center w-full py-4 uppercase tracking-widest">Silakan pilih jenis produk terlebih dahulu</p>
                                @endforelse
                            </div>
                            @error('selectedProdukId') <p class="text-[10px] text-rose-500 font-bold uppercase mt-3 text-center">{{ $message }}</p> @enderror
                        </div>

                        <!-- Quantity -->
                        <div class="space-y-2">
                            <label class="text-xs font-bold text-slate-400 uppercase tracking-widest ml-1">Jumlah Pemesanan ({{ $this->selectedProduk?->satuan ?? 'unit' }})</label>
                            <input type="number" wire:model="jumlah" class="w-full px-5 py-4 rounded-2xl border border-slate-200 focus:ring-2 focus:ring-indigo-500 outline-none transition-all font-bold text-slate-900 bg-slate-50 focus:bg-white" placeholder="0">
                            @error('jumlah') <p class="text-[10px] text-rose-500 font-bold uppercase mt-1 ml-1">{{ $message }}</p> @enderror
                        </div>

                        <!-- Logistics -->
                        <div class="space-y-6">
                            <label class="text-xs font-bold text-slate-400 uppercase tracking-widest ml-1">Logistik & Lokasi</label>
                            
                            <!-- Search & Map -->
                            <div class="space-y-4" wire:ignore>
                                <div class="relative group">
                                    <div class="absolute left-5 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-indigo-600 transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                                    </div>
                                    <input type="text" id="map-search" class="w-full pl-14 pr-5 py-4 rounded-2xl border border-slate-200 focus:ring-2 focus:ring-indigo-500 outline-none transition-all font-medium text-slate-700 bg-slate-50 focus:bg-white shadow-sm" placeholder="Cari alamat atau pin di peta...">
                                </div>

                                <div id="map-container" class="w-full h-80 rounded-[2.5rem] border-8 border-slate-50 shadow-inner overflow-hidden relative z-0">
                                    <div id="map" class="w-full h-full"></div>
                                    <div id="map-placeholder" class="absolute inset-0 bg-slate-100 flex flex-col items-center justify-center text-center p-6 transition-opacity duration-500">
                                        <div class="w-10 h-10 border-4 border-indigo-100 border-t-indigo-600 rounded-full animate-spin mb-4"></div>
                                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em]">Menyiapkan Navigasi...</p>
                                    </div>
                                </div>

                                <textarea id="alamat-textarea" wire:model="alamat" rows="3" class="w-full px-5 py-4 rounded-2xl border border-slate-200 focus:ring-2 focus:ring-indigo-500 outline-none transition-all font-medium text-slate-700 bg-slate-50 focus:bg-white" placeholder="Ketik alamat pengiriman lengkap Anda di sini..."></textarea>
                            </div>
                            @error('alamat') <p class="text-[10px] text-rose-500 font-bold uppercase mt-1 ml-1">{{ $message }}</p> @enderror
                        </div>

                        <!-- WhatsApp -->
                        <div class="bg-emerald-50/50 p-8 rounded-[2.5rem] border border-emerald-100/50 space-y-4">
                            <label class="text-xs font-bold text-emerald-600 uppercase tracking-widest ml-1">No. WhatsApp Konfirmasi</label>
                            <div class="relative">
                                <span class="absolute left-5 top-1/2 -translate-y-1/2 text-emerald-600 font-black">WA +62</span>
                                <input type="text" wire:model="no_whatsapp" class="w-full pl-24 pr-5 py-4 rounded-2xl border border-emerald-100 focus:ring-2 focus:ring-emerald-500 outline-none transition-all font-bold text-slate-800 bg-white" placeholder="8xxxxxxxxx">
                            </div>
                            @error('no_whatsapp') <p class="text-[10px] text-rose-500 font-bold uppercase mt-1 ml-1">{{ $message }}</p> @enderror
                        </div>

                        <!-- Submit -->
                        <div class="pt-6">
                            <button type="submit" class="w-full bg-slate-900 text-white font-black text-xl py-6 rounded-[2.5rem] shadow-2xl shadow-slate-200 hover:bg-indigo-600 hover:-translate-y-1 transition-all duration-300 flex items-center justify-center gap-4 group">
                                <span>Kirim Pesanan</span>
                                <svg class="w-6 h-6 group-hover:translate-x-2 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7l5 5m0 0l-5 5m5-5H6"></path></svg>
                            </button>
                            <p class="text-center text-[10px] text-slate-400 mt-6 font-bold uppercase tracking-[0.2em] italic">Pesanan akan divalidasi oleh admin pusat BPTrans</p>
                        </div>
                    </form>
                </div>

                <!-- Right Column: Sidebar Summary -->
                <div class="lg:w-1/3 bg-slate-50/50 p-8 sm:p-12">
                    <div class="sticky top-12 space-y-10">
                        <!-- Preview Card -->
                        <div class="space-y-6">
                            <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest">Ringkasan Pesanan</h3>
                            <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-100 space-y-6">
                                <div class="h-40 bg-slate-50 rounded-2xl overflow-hidden border border-slate-100">
                                    @if($this->selectedProduk?->gambar)
                                        <img src="{{ asset('storage/' . $this->selectedProduk->gambar) }}" class="w-full h-full object-cover">
                                    @endif
                                </div>
                                <div>
                                    <h4 class="text-xl font-black text-slate-900 capitalize">{{ $this->selectedProduk?->nama ?? 'Pilih Produk' }}</h4>
                                    <p class="text-xs font-bold text-indigo-600 uppercase tracking-widest mt-1">{{ $this->selectedProduk?->jenis ?? '-' }}</p>
                                </div>
                                <div class="pt-4 border-t border-slate-50 space-y-3">
                                    <div class="flex justify-between items-center text-xs">
                                        <span class="font-bold text-slate-400 uppercase tracking-widest">Harga Satuan</span>
                                        <span class="font-bold text-slate-700">Rp{{ number_format($this->selectedProduk?->harga ?? 0, 0, ',', '.') }}</span>
                                    </div>
                                    <div class="flex justify-between items-center text-xs">
                                        <span class="font-bold text-slate-400 uppercase tracking-widest">Jumlah</span>
                                        <span class="font-black text-slate-900">{{ number_format((float)($jumlah ?: 0)) }} {{ $this->selectedProduk?->satuan ?? 'unit' }}</span>
                                    </div>
                                </div>
                                <div class="pt-6 border-t border-slate-100 flex flex-col items-center text-center">
                                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Total Estimasi</span>
                                    <p class="text-3xl font-black text-indigo-600">Rp{{ number_format(($this->selectedProduk?->harga ?? 0) * (float)($jumlah ?: 0), 0, ',', '.') }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Info Tip -->
                        <div class="bg-indigo-600 rounded-3xl p-8 text-white shadow-xl shadow-indigo-100">
                            <svg class="w-8 h-8 mb-4 text-indigo-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <h5 class="text-lg font-bold mb-2">Bantuan Cepat</h5>
                            <p class="text-xs text-indigo-100 leading-relaxed font-medium">Jika Anda mengalami kendala saat mengisi alamat di peta, pastikan izin lokasi browser Anda sudah aktif atau gunakan fitur pencarian alamat.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Google Maps Scripts -->
    <script src="https://maps.googleapis.com/maps/api/js?key=&libraries=places" async defer></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let map, marker, autocomplete, geocoder;
            const defaultLocation = { lat: -6.65, lng: 110.75 };

            function initMap() {
                if (typeof google === 'undefined' || typeof google.maps === 'undefined') {
                    console.error('Google Maps API not loaded.');
                    return;
                }
                
                const placeholder = document.getElementById('map-placeholder');
                if (placeholder) {
                    placeholder.style.opacity = '0';
                    setTimeout(() => placeholder.style.display = 'none', 500);
                }

                geocoder = new google.maps.Geocoder();
                
                const mapEl = document.getElementById('map');
                if (mapEl) {
                    map = new google.maps.Map(mapEl, {
                        center: defaultLocation,
                        zoom: 13,
                        mapTypeControl: false,
                        streetViewControl: false,
                        fullscreenControl: false,
                        styles: [
                            { "featureType": "poi", "stylers": [{ "visibility": "off" }] },
                            { "featureType": "administrative", "elementType": "labels.text.fill", "stylers": [{ "color": "#444444" }] },
                            { "featureType": "water", "elementType": "all", "stylers": [{ "color": "#cce3f5" }, { "visibility": "on" }] }
                        ]
                    });

                    marker = new google.maps.Marker({
                        map: map,
                        draggable: true,
                        position: defaultLocation,
                        icon: {
                            url: 'https://maps.google.com/mapfiles/ms/icons/indigo-dot.png'
                        }
                    });

                    map.addListener('click', function(event) {
                        marker.setPosition(event.latLng);
                        getAddressFromLatLng(event.latLng);
                    });

                    marker.addListener('dragend', function(event) {
                        getAddressFromLatLng(event.latLng);
                    });
                }

                const input = document.getElementById('map-search');
                if (input && typeof google.maps.places !== 'undefined') {
                    autocomplete = new google.maps.places.Autocomplete(input);
                    autocomplete.bindTo('bounds', map);

                    autocomplete.addListener('place_changed', function() {
                        const place = autocomplete.getPlace();
                        if (!place.geometry) return;
                        if (place.geometry.viewport) { map.fitBounds(place.geometry.viewport); } 
                        else { map.setCenter(place.geometry.location); map.setZoom(17); }
                        marker.setPosition(place.geometry.location);
                        updateAddress(place.geometry.location, place.formatted_address);
                    });
                }
            }

            function getAddressFromLatLng(latLng) {
                if (!geocoder) return;
                geocoder.geocode({ 'location': latLng }, function(results, status) {
                    if (status === 'OK' && results[0]) {
                        updateAddress(latLng, results[0].formatted_address);
                    }
                });
            }

            function updateAddress(latLng, address) {
                const textarea = document.getElementById('alamat-textarea');
                textarea.value = address;
                @this.set('alamat', address);
                document.getElementById('map-search').value = address;
            }

            if (typeof google !== 'undefined') { initMap(); } 
            else {
                const checkGoogle = setInterval(() => {
                    if (typeof google !== 'undefined') { initMap(); clearInterval(checkGoogle); }
                }, 500);
            }
        });
    </script>
</div>
