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
                <div class="bg-blue-50/50 p-6 rounded-2xl border border-blue-100 mb-6">
                    <div class="flex items-center justify-between mb-4">
                        <label class="text-sm font-bold text-gray-700 uppercase tracking-wider">
                            @if($productCategory)
                                Jenis Produk: <span class="text-blue-600 ml-1">{{ ucwords($productCategory) }}</span>
                            @else
                                Pilih Produk
                            @endif
                        </label>
                        @if($this->selectedProduk)
                            <span class="text-[10px] font-bold bg-blue-600 text-white px-2 py-0.5 rounded-full">Terpilih</span>
                        @endif
                    </div>

                    <input type="text"
                           value="{{ $this->selectedProduk ? ucwords($this->selectedProduk->nama) . ' - ' . $this->selectedProduk->jenis : 'Silakan pilih varian di bawah' }}"
                           class="w-full bg-white px-4 py-3 rounded-xl border border-gray-200 text-gray-700 font-bold mb-5 shadow-sm focus:ring-0 focus:border-blue-300 outline-none transition-all"
                           readonly>
                    
                    @error('selectedProdukId') <span class="text-red-500 text-xs mt-1 block mb-3 font-medium">{{ $message }}</span> @enderror

                    <p class="text-[11px] text-gray-400 font-bold uppercase mb-3 px-1">Pilih Varian / Jenis:</p>
                    
                    <!-- Product Carousel -->
                    <div class="flex gap-4 overflow-x-auto pb-4 -mx-2 px-2 scrollbar-hide">
                        @foreach($this->produks as $produk)
                            <button type="button"
                                    wire:click="selectProduk({{ $produk->id }})"
                                    class="flex-shrink-0 w-28 rounded-2xl border-2 transition-all duration-500 overflow-hidden group
                                           {{ $selectedProdukId == $produk->id
                                               ? 'border-blue-600 bg-white shadow-xl shadow-blue-100 ring-4 ring-blue-50 scale-105'
                                               : 'border-white bg-white/50 hover:border-gray-200 hover:bg-white shadow-sm' }}">
                                <!-- Product Thumbnail -->
                                <div class="h-20 bg-gray-50 flex items-center justify-center overflow-hidden relative">
                                    @if($produk->gambar)
                                        <img src="{{ asset('storage/' . $produk->gambar) }}"
                                             alt="{{ $produk->nama }}"
                                             class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                                    @else
                                        <div class="h-full w-full flex items-center justify-center bg-gray-100">
                                            <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                        </div>
                                    @endif
                                    
                                    @if($selectedProdukId == $produk->id)
                                        <div class="absolute inset-0 bg-blue-600/10 flex items-center justify-center">
                                            <div class="bg-blue-600 text-white p-1 rounded-full shadow-lg">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                                                </svg>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                                <!-- Product Info -->
                                <div class="p-3 text-center">
                                    <p class="text-[10px] font-extrabold text-gray-900 truncate mb-1">{{ $produk->jenis }}</p>
                                    <p class="text-[11px] font-black {{ $selectedProdukId == $produk->id ? 'text-blue-600' : 'text-gray-500' }}">
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

                <!-- Alamat Pengiriman (Google Maps Integration) -->
                <div class="space-y-4">
                    <label class="form-label flex items-center justify-between">
                        <span>Alamat Pengiriman</span>
                        <span class="text-[10px] text-blue-600 font-bold uppercase tracking-wider bg-blue-50 px-2 py-0.5 rounded">Pin Lokasi di Peta</span>
                    </label>
                    
                    <!-- Search Box -->
                    <div class="relative group">
                        <div class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-blue-500 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                        <input type="text" id="map-search" 
                               class="form-input pl-12 pr-4 py-3 bg-white border-2 border-gray-100 focus:border-blue-500 rounded-2xl shadow-sm transition-all"
                               placeholder="Cari lokasi atau alamat...">
                    </div>

                    <!-- Map Container -->
                    <div id="map-container" class="w-full h-72 rounded-[2rem] border-4 border-white shadow-xl overflow-hidden bg-gray-100 relative z-0">
                        <div id="map" class="w-full h-full"></div>
                        <!-- Overlay loader for map -->
                        <div id="map-placeholder" class="absolute inset-0 bg-gray-50 flex flex-col items-center justify-center text-center p-6 transition-opacity duration-500">
                            <div class="w-12 h-12 border-4 border-blue-100 border-t-blue-600 rounded-full animate-spin mb-4"></div>
                            <p class="text-sm font-bold text-gray-400 uppercase tracking-widest">Menyiapkan Peta...</p>
                        </div>
                    </div>

                    <!-- Selected Address Textarea (Keep for manual adjustments/viewing) -->
                    <div class="relative">
                        <textarea id="alamat-textarea" wire:model="alamat" 
                                  class="form-input bg-gray-50/50 border-gray-100 text-sm font-medium text-gray-600 min-h-[80px] resize-none" 
                                  placeholder="Detail alamat akan muncul di sini setelah memilih di peta"
                                  readonly></textarea>
                        <div class="absolute right-4 bottom-4">
                            <div class="bg-blue-600 text-white p-1.5 rounded-lg shadow-lg">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                    @error('alamat') <span class="text-red-500 text-xs mt-1 block font-medium">{{ $message }}</span> @enderror
                </div>

                <!-- Catatan -->
                <div>
                    <label class="form-label">Catatan Tambahan</label>
                    <textarea wire:model="catatan" class="form-input" rows="2" placeholder="Contoh: Titipkan di satpam, pagar warna biru, dll (opsional)"></textarea>
                </div>

                <!-- No. WhatsApp -->
                <div class="bg-emerald-50/50 p-6 rounded-2xl border border-emerald-100">
                    <label class="form-label text-emerald-700">No. WhatsApp Konfirmasi</label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-emerald-600 font-bold">+62</span>
                        <input type="text" wire:model="no_whatsapp" class="form-input pl-14 bg-white border-emerald-100 focus:border-emerald-500" placeholder="8xxxxxxxxxx">
                    </div>
                    @error('no_whatsapp') <span class="text-red-500 text-xs mt-1 block font-medium">{{ $message }}</span> @enderror
                </div>

                <!-- Submit Button -->
                <div class="pt-4">
                    <button type="submit" class="w-full bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-black text-lg py-5 rounded-[2rem] shadow-xl shadow-blue-200 transition-all duration-300 hover:-translate-y-1 flex items-center justify-center gap-3 group">
                        <span>Kirim Pesanan Sekarang</span>
                        <svg class="w-6 h-6 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 5l7 7-7 7M5 5l7 7-7 7"></path>
                        </svg>
                    </button>
                    <p class="text-center text-[10px] text-gray-400 mt-4 font-bold uppercase tracking-widest italic">Pesanan akan langsung diproses oleh admin BPTrans</p>
                </div>
            </form>
        </div>
    </div>

    <!-- Google Maps Scripts -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let map, marker, autocomplete, geocoder;
            const defaultLocation = { lat: -6.65, lng: 110.75 }; // Default to Jepara area
            
            function initMap() {
                // Hide placeholder
                document.getElementById('map-placeholder').style.opacity = '0';
                setTimeout(() => document.getElementById('map-placeholder').style.display = 'none', 500);

                geocoder = new google.maps.Geocoder();
                map = new google.maps.Map(document.getElementById('map'), {
                    center: defaultLocation,
                    zoom: 13,
                    mapTypeControl: false,
                    streetViewControl: false,
                    fullscreenControl: false,
                    styles: [
                        { "featureType": "poi", "stylers": [{ "visibility": "off" }] }
                    ]
                });

                marker = new google.maps.Marker({
                    map: map,
                    draggable: true,
                    animation: google.maps.Animation.DROP,
                    position: defaultLocation
                });

                // Init Autocomplete
                const input = document.getElementById('map-search');
                autocomplete = new google.maps.places.Autocomplete(input);
                autocomplete.bindTo('bounds', map);

                // Handle place selection from search
                autocomplete.addListener('place_changed', function() {
                    const place = autocomplete.getPlace();
                    if (!place.geometry) return;

                    if (place.geometry.viewport) {
                        map.fitBounds(place.geometry.viewport);
                    } else {
                        map.setCenter(place.geometry.location);
                        map.setZoom(17);
                    }
                    marker.setPosition(place.geometry.location);
                    updateAddress(place.geometry.location, place.formatted_address);
                });

                // Handle click on map
                map.addListener('click', function(event) {
                    marker.setPosition(event.latLng);
                    getAddressFromLatLng(event.latLng);
                });

                // Handle marker drag
                marker.addListener('dragend', function(event) {
                    getAddressFromLatLng(event.latLng);
                });
            }

            function getAddressFromLatLng(latLng) {
                geocoder.geocode({ 'location': latLng }, function(results, status) {
                    if (status === 'OK' && results[0]) {
                        updateAddress(latLng, results[0].formatted_address);
                    }
                });
            }

            function updateAddress(latLng, address) {
                // Set the value in the hidden/readonly textarea
                const textarea = document.getElementById('alamat-textarea');
                textarea.value = address;
                
                // Trigger Livewire update
                @this.set('alamat', address);
                
                // Update search input as well
                document.getElementById('map-search').value = address;
            }

            // Load map with a small delay to ensure script is ready
            if (typeof google !== 'undefined') {
                initMap();
            } else {
                // Fallback check
                const checkGoogle = setInterval(() => {
                    if (typeof google !== 'undefined') {
                        initMap();
                        clearInterval(checkGoogle);
                    }
                }, 500);
            }
        });
    </script>
</div>

