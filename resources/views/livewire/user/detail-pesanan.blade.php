@push('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
@endpush

@push('scripts')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
@endpush

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

                        <!-- Logistics (Map & Address) -->
                        <div class="space-y-4" wire:ignore>
                            <div class="flex items-center justify-between ml-1">
                                <label class="text-xs font-bold text-slate-400 uppercase tracking-widest">Titik Pengiriman (Peta)</label>
                                <button type="button" id="btn-gps" class="text-xs font-bold text-indigo-600 bg-indigo-50 hover:bg-indigo-100 px-3 py-1.5 rounded-lg flex items-center transition-colors">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                    GPS
                                </button>
                            </div>
                            <div id="map" class="w-full h-80 rounded-2xl border border-slate-200 z-10"></div>

                            {{-- Input paste Google Maps link --}}
                            <div class="relative">
                                <div class="absolute inset-y-0 left-4 flex items-center pointer-events-none">
                                    <svg class="w-4 h-4 text-rose-400" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/></svg>
                                </div>
                                <input
                                    type="text"
                                    id="gmaps-paste-input"
                                    class="w-full pl-10 pr-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-rose-400 outline-none transition-all text-sm font-medium text-slate-700 bg-slate-50 focus:bg-white placeholder-slate-400"
                                    placeholder="Paste link / Plus Code / nama tempat (contoh: 7Q4H+2P3, Jepara)"
                                    autocomplete="off"
                                >
                            </div>
                            <p class="text-xs text-slate-500 ml-1">Geser pin lokasi atau paste link Google Maps untuk menghitung jarak secara otomatis.</p>
                        </div>
                        
                        <div class="space-y-4">
                            <div class="flex items-center justify-between ml-1">
                                <label class="text-xs font-bold text-slate-400 uppercase tracking-widest">Alamat Lengkap</label>
                            </div>
                            <div class="space-y-4">
                                <textarea wire:model="alamat" id="alamat" rows="4" class="w-full px-5 py-4 rounded-2xl border border-slate-200 focus:ring-2 focus:ring-indigo-500 outline-none transition-all font-medium text-slate-700 bg-slate-50 focus:bg-white shadow-sm" placeholder="Masukkan alamat pengiriman lengkap (jalan, nomor rumah, RT/RW, kecamatan, kabupaten/kota)..."></textarea>
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
                                    <div class="flex justify-between items-center text-xs pt-2 border-t border-dashed border-slate-100">
                                        <span class="font-bold text-slate-400 uppercase tracking-widest">Total Material</span>
                                        <span class="font-bold text-slate-700">Rp{{ number_format(($this->selectedProduk?->harga ?? 0) * (float)($jumlah ?: 0), 0, ',', '.') }}</span>
                                    </div>
                                    @if($jarak)
                                        <div class="flex justify-between items-center text-xs">
                                            <span class="font-bold text-slate-400 uppercase tracking-widest">Jarak</span>
                                            <span class="font-bold text-slate-700">{{ $jarak }} km</span>
                                        </div>
                                        <div class="flex justify-between items-center text-xs">
                                            <span class="font-bold text-slate-400 uppercase tracking-widest">Ongkos Kirim</span>
                                            <span class="font-bold text-emerald-600">Rp{{ number_format($ongkos_kirim, 0, ',', '.') }}</span>
                                        </div>
                                    @endif
                                </div>
                                <div class="pt-6 border-t border-slate-100 flex flex-col items-center text-center">
                                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Total Estimasi Pembayaran</span>
                                    <p class="text-3xl font-black text-indigo-600">Rp{{ number_format((($this->selectedProduk?->harga ?? 0) * (float)($jumlah ?: 0)) + $ongkos_kirim, 0, ',', '.') }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Info Tip -->
                        <div class="bg-indigo-600 rounded-3xl p-8 text-white shadow-xl shadow-indigo-100">
                            <svg class="w-8 h-8 mb-4 text-indigo-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <h5 class="text-lg font-bold mb-2">Bantuan Cepat</h5>
                            <p class="text-xs text-indigo-100 leading-relaxed font-medium">Tuliskan alamat pengiriman Anda selengkap mungkin (termasuk kelurahan, kecamatan, nomor rumah, atau patokan jalan) untuk memudahkan verifikasi pesanan oleh admin kami.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

@script
<script>
    const warehouseLat = -6.5888;
    const warehouseLng = 110.6684;

    const userLat = $wire.get('latitude');
    const userLng = $wire.get('longitude');

    const initLat = userLat ? userLat : warehouseLat;
    const initLng = userLng ? userLng : warehouseLng;

    const map = L.map('map').setView([initLat, initLng], 12);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors',
        maxZoom: 19
    }).addTo(map);

    // Satu marker pengiriman (merah)
    const redIcon = L.divIcon({
        html: '<div style="width: 28px; height: 28px; background-color: #ef4444; border: 3px solid white; border-radius: 50%; box-shadow: 0 3px 8px rgba(0,0,0,0.4); cursor: grab;"></div>',
        iconSize: [28, 28],
        iconAnchor: [14, 14],
        popupAnchor: [0, -14]
    });

    const marker = L.marker([initLat, initLng], { draggable: true, icon: redIcon }).addTo(map);
    marker.bindPopup('<b>📍 Titik Pengiriman</b><br>Geser ke lokasi tujuan pengiriman');
    marker.openPopup();

    function updateDistanceAndAddress(lat, lng) {
        fetch(`https://router.project-osrm.org/route/v1/driving/${warehouseLng},${warehouseLat};${lng},${lat}?overview=false`)
            .then(response => response.json())
            .then(data => {
                if (data.routes && data.routes.length > 0) {
                    const distanceInKm = (data.routes[0].distance / 1000).toFixed(1);
                    $wire.set('jarak', distanceInKm);
                    $wire.set('latitude', lat);
                    $wire.set('longitude', lng);
                }
            })
            .catch(err => console.error("OSRM Error:", err));

        fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}`)
            .then(response => response.json())
            .then(data => {
                if (data && data.display_name) {
                    $wire.set('alamat', data.display_name);
                }
            })
            .catch(err => console.error("Nominatim Error:", err));
    }

    // Jika sudah ada koordinat user, hitung jarak awal
    if (userLat && userLng) {
        updateDistanceAndAddress(userLat, userLng);
        map.setView([userLat, userLng], 14);
    }

    marker.on('dragend', function (e) {
        const position = marker.getLatLng();
        updateDistanceAndAddress(position.lat, position.lng);
    });

    // Jika belum punya koordinat, coba ambil dari GPS browser
    if (!userLat && navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function (position) {
            const gpsLat = position.coords.latitude;
            const gpsLng = position.coords.longitude;
            map.setView([gpsLat, gpsLng], 14);
            marker.setLatLng([gpsLat, gpsLng]);
            updateDistanceAndAddress(gpsLat, gpsLng);
        }, function (error) {
            console.log("Geolocation disabled or error", error);
        });
    }

    // GPS Button Handler
    document.getElementById('btn-gps').addEventListener('click', function () {
        const btn = this;
        const originalText = btn.innerHTML;
        btn.innerHTML = '<svg class="w-4 h-4 animate-spin inline mr-1" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg> Mencari...';
        btn.disabled = true;

        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function (position) {
                const gpsLat = position.coords.latitude;
                const gpsLng = position.coords.longitude;
                map.setView([gpsLat, gpsLng], 14);
                marker.setLatLng([gpsLat, gpsLng]);
                updateDistanceAndAddress(gpsLat, gpsLng);
                btn.innerHTML = originalText;
                btn.disabled = false;
            }, function (error) {
                alert("Gagal mendapatkan lokasi. Pastikan GPS aktif dan diizinkan oleh browser.");
                btn.innerHTML = originalText;
                btn.disabled = false;
                console.log("Geolocation disabled or error", error);
            });
        } else {
            alert("Browser Anda tidak mendukung fitur GPS.");
            btn.innerHTML = originalText;
            btn.disabled = false;
        }
    });

    // ─── Location Input Handler ─────────────────────────────────────────────────
    // Mendukung berbagai format input:
    // 1. URL Google Maps (google.com/maps, maps.app.goo.gl)
    // 2. Plus Code global: "8FX22222+22"
    // 3. Plus Code lokal + konteks: "7Q4H+2P3, Jepara, Jawa Tengah"
    // 4. Nama tempat / alamat bebas
    // 5. Koordinat langsung: "-6.123, 110.456"

    // Regex deteksi Plus Code: X+XX pattern (huruf kapital + angka)
    const PLUS_CODE_RE = /^[23456789CFGHJMPQRVWX]{4,8}\+[23456789CFGHJMPQRVWX]{2,}/i;

    function parseGoogleMapsUrl(input) {
        input = input.trim();

        // Format: koordinat langsung "lat,lng" atau "lat, lng"
        const coordDirect = input.match(/^(-?\d+\.\d+)[,\s]+(-?\d+\.\d+)$/);
        if (coordDirect) return { lat: parseFloat(coordDirect[1]), lng: parseFloat(coordDirect[2]) };

        // Format: ?q=lat,lng
        const qParam = input.match(/[?&]q=(-?\d+\.\d+)[,+](-?\d+\.\d+)/);
        if (qParam) return { lat: parseFloat(qParam[1]), lng: parseFloat(qParam[2]) };

        // Format: /@lat,lng,zoom atau /place/.../@lat,lng
        const atSign = input.match(/\/@(-?\d+\.\d+),(-?\d+\.\d+)/);
        if (atSign) return { lat: parseFloat(atSign[1]), lng: parseFloat(atSign[2]) };

        // Format: /search/lat,lng
        const searchCoord = input.match(/\/search\/(-?\d+\.\d+),(-?\d+\.\d+)/);
        if (searchCoord) return { lat: parseFloat(searchCoord[1]), lng: parseFloat(searchCoord[2]) };

        return null;
    }

    /**
     * Geocode menggunakan Nominatim — mendukung Plus Code, nama tempat, dan alamat bebas.
     */
    async function geocodeWithNominatim(query) {
        const url = 'https://nominatim.openstreetmap.org/search?format=json&limit=1&q=' + encodeURIComponent(query);
        const res = await fetch(url, { headers: { 'Accept-Language': 'id,en' } });
        const data = await res.json();
        if (data && data.length > 0) {
            return { lat: parseFloat(data[0].lat), lng: parseFloat(data[0].lon) };
        }
        return null;
    }

    async function handleGmapsInput(value) {
        const pasteInput = document.getElementById('gmaps-paste-input');
        const defaultPlaceholder = pasteInput.placeholder;
        pasteInput.style.borderColor = '#f59e0b'; // kuning = sedang memproses

        let coords = null;

        // 1. Coba parse sebagai URL Google Maps (koordinat langsung dari URL)
        coords = parseGoogleMapsUrl(value);

        // 2. Jika short link goo.gl → resolve redirect dulu
        if (!coords && value.includes('goo.gl')) {
            pasteInput.placeholder = 'Memuat dari link pendek...';
            try {
                const proxyUrl = 'https://api.allorigins.win/get?url=' + encodeURIComponent(value);
                const res = await fetch(proxyUrl);
                const data = await res.json();
                const finalUrl = data.status?.url || '';
                coords = parseGoogleMapsUrl(finalUrl);
            } catch (e) {
                console.warn('Gagal resolve short link:', e);
            }
        }

        // 3. Deteksi Plus Code (7Q4H+2P3 atau dengan konteks "7Q4H+2P3, Jepara, ...")
        if (!coords) {
            const firstPart = value.split(',')[0].trim();
            if (PLUS_CODE_RE.test(firstPart)) {
                pasteInput.placeholder = 'Mencari Plus Code...';
                try {
                    // Coba geocode seluruh string (Plus Code + konteks lokasi)
                    coords = await geocodeWithNominatim(value);

                    // Jika gagal, coba hanya konteks lokasi lalu skip (Plus Code butuh referensi)
                    if (!coords && value.includes(',')) {
                        const contextOnly = value.split(',').slice(1).join(',').trim();
                        coords = await geocodeWithNominatim(contextOnly);
                    }
                } catch (e) {
                    console.warn('Gagal geocode Plus Code:', e);
                }
            }
        }

        // 4. Fallback: coba geocode sebagai nama tempat / alamat bebas
        if (!coords && !value.startsWith('http')) {
            pasteInput.placeholder = 'Mencari lokasi...';
            try {
                coords = await geocodeWithNominatim(value);
            } catch (e) {
                console.warn('Gagal geocode teks:', e);
            }
        }

        pasteInput.placeholder = defaultPlaceholder;

        if (coords) {
            map.setView([coords.lat, coords.lng], 17);
            marker.setLatLng([coords.lat, coords.lng]);
            updateDistanceAndAddress(coords.lat, coords.lng);
            pasteInput.style.borderColor = '#22c55e'; // hijau = sukses
            setTimeout(() => { pasteInput.style.borderColor = ''; }, 2500);
        } else {
            pasteInput.style.borderColor = '#ef4444'; // merah = gagal
            setTimeout(() => { pasteInput.style.borderColor = ''; }, 2500);
            console.warn('Tidak bisa mengenali lokasi:', value);
        }
    }

    document.getElementById('gmaps-paste-input').addEventListener('paste', function (e) {
        const pastedText = (e.clipboardData || window.clipboardData).getData('text');
        if (pastedText) {
            e.preventDefault();
            this.value = pastedText;
            handleGmapsInput(pastedText);
        }
    });

    // Handle jika user mengetik/input manual lalu tekan Enter atau blur
    document.getElementById('gmaps-paste-input').addEventListener('keydown', function (e) {
        if (e.key === 'Enter') { e.preventDefault(); if (this.value.trim()) handleGmapsInput(this.value.trim()); }
    });
    document.getElementById('gmaps-paste-input').addEventListener('change', function () {
        if (this.value.trim()) handleGmapsInput(this.value.trim());
    });
</script>
@endscript
