<div>
    <h1 class="text-2xl font-bold mb-6">Etalase Produk</h1>

    <!-- ALERT -->
    @if (session()->has('success'))
        <div class="bg-green-100 text-green-700 p-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <!-- FORM (Hidden by default) -->
    @if ($showForm)
    <div class="bg-white p-6 rounded-2xl shadow mb-6">
        <div class="flex justify-between items-center mb-4">
            <h2 class="font-semibold">
                @if($editingId)
                    Edit Produk
                @else
                    Tambah Produk
                @endif
            </h2>
            <button wire:click="closeForm()" class="text-gray-500 hover:text-gray-700">
                ✕
            </button>
        </div>

        <div class="grid grid-cols-2 gap-4">

            <div>
                <input type="text" wire:model="nama" placeholder="Nama Produk"
                       class="border p-2 rounded w-full">
                @error('nama') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
            </div>

            <div>
                <input type="text" wire:model="jenis" placeholder="Jenis"
                       class="border p-2 rounded w-full">
                @error('jenis') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
            </div>

            <div>
                <input type="number" wire:model="harga" placeholder="Harga"
                       class="border p-2 rounded w-full">
                @error('harga') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
            </div>

            <div>
                <input type="text" wire:model="satuan" placeholder="Satuan"
                       class="border p-2 rounded w-full">
                @error('satuan') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
            </div>

            <div class="col-span-2">
                <label class="block text-sm font-medium mb-2">Gambar Produk</label>
                <input type="file" wire:model.live="gambar"
                       class="border p-2 rounded w-full">
                @error('gambar') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
                
                {{-- Preview gambar --}}
                @if ($gambar)
                    <div class="mt-3">
                        <img src="{{ $gambar->temporaryUrl() }}" 
                             class="w-24 h-24 object-cover rounded"
                             alt="Preview">
                        <p class="text-sm text-gray-500 mt-1">File: {{ $gambar->getClientOriginalName() }}</p>
                    </div>
                @endif
            </div>

            <div class="col-span-2">
                <textarea wire:model="deskripsi" placeholder="Deskripsi"
                          class="border p-2 rounded w-full"></textarea>
            </div>

        </div>

        <div class="flex gap-2 mt-4">
            <button wire:click="tambahProduk" wire:loading.attr="disabled"
                    class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded disabled:opacity-50">
                <span wire:loading.remove>
                    @if($editingId) Update @else Tambah @endif
                </span>
                <span wire:loading>Menyimpan...</span>
            </button>
            <button wire:click="closeForm()"
                    class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded">
                Batal
            </button>
        </div>

    </div>
    @endif

    <!-- TABEL dengan Button Tambah -->
    <div class="bg-white p-6 rounded-2xl shadow">
        <div class="flex justify-between items-center mb-4">
            <h2 class="font-semibold">Daftar Produk</h2>
            <button wire:click="toggleForm()"
                    class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded">
                + Tambah Barang
            </button>
        </div>

        <table class="w-full text-left">
            <thead>
                <tr class="border-b">
                    <th class="py-2">Gambar</th>
                    <th>Nama</th>
                    <th>Jenis</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($this->produk as $item)
                <tr class="border-b">
                    <td class="py-2">
                        @if($item->gambar)
                            <img src="{{ asset('storage/'.$item->gambar) }}"
                                 class="w-16 h-16 object-cover rounded">
                        @else
                            <span class="text-gray-400 text-sm">No Image</span>
                        @endif
                    </td>
                    <td>{{ $item->nama }}</td>
                    <td>{{ $item->jenis }}</td>
                    <td>Rp {{ number_format($item->harga, 0, ',', '.') }}</td>
                    <td>{{ $item->satuan }}</td>
                    <td class="text-center">
                        <div class="flex gap-2 justify-center">
                            <button wire:click="editProduk({{ $item->id }})"
                                    class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded text-sm">
                                Edit
                            </button>
                            <button wire:click="deleteProduk({{ $item->id }})"
                                    onclick="return confirm('Yakin ingin menghapus produk ini?')"
                                    class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm">
                                Hapus
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6
                <tr>
                    <td colspan="5" class="text-center text-gray-400 py-4">
                        Belum ada produk
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>