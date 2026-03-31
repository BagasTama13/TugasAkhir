<div class="min-h-screen bg-gray-50 p-8">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="sticky top-0 bg-gray-50 z-30 pb-4 mb-0">
            <h1 class="text-3xl font-bold text-gray-900">Daftar Pesanan</h1>
            <p class="text-gray-500 mt-2">Kelola semua pesanan dari pelanggan</p>
        </div>

        <!-- Summary Stats -->
        <div class="sticky top-32 bg-gray-50 z-20 pt-4 pb-4 mb-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <!-- Total Pesanan -->
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="text-5xl font-bold text-gray-900">
                        {{ $this->pesanans->count() }}
                    </div>
                    <p class="text-gray-600 text-sm mt-2">Total Pesanan</p>
                </div>

                <!-- Pending -->
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="text-5xl font-bold text-yellow-600">
                        {{ $this->pesanans->where('status', 'pending')->count() }}
                    </div>
                    <p class="text-gray-600 text-sm mt-2">Menunggu</p>
                </div>

                <!-- Accepted -->
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="text-5xl font-bold text-green-600">
                        {{ $this->pesanans->where('status', 'accepted')->count() }}
                    </div>
                    <p class="text-gray-600 text-sm mt-2">Diterima</p>
                </div>

                <!-- Delivered -->
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="text-5xl font-bold text-blue-600">
                        {{ $this->pesanans->where('status', 'delivered')->count() }}
                    </div>
                    <p class="text-gray-600 text-sm mt-2">Terkirim</p>
                </div>
            </div>
        </div>

        <!-- Add/Edit Pesanan Form -->
        @if($showForm)
            <div class="bg-white rounded-lg shadow p-6 mb-8">
                <h2 class="text-xl font-bold text-gray-900 mb-6">
                    {{ $editingId ? 'Edit Pesanan' : 'Tambah Pesanan Baru' }}
                </h2>

                <form wire:submit="tambahPesanan" class="space-y-6">
                    <!-- Nomor & Nama -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Nomor Pesanan
                            </label>
                            <input type="text" wire:model="nomor" placeholder="e.g., PSN-001"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('nomor') border-red-500 @enderror"
                                   @if($editingId) disabled @endif>
                            @error('nomor') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Nama Pemesan
                            </label>
                            <input type="text" wire:model="nama" placeholder="Nama pelanggan"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('nama') border-red-500 @enderror">
                            @error('nama') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <!-- Tipe & Jumlah -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Tipe Pesanan
                            </label>
                            <input type="text" wire:model="tipe" placeholder="e.g., reguler, express"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('tipe') border-red-500 @enderror">
                            @error('tipe') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Jumlah
                            </label>
                            <input type="number" wire:model="jumlah" placeholder="Jumlah barang" min="1"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('jumlah') border-red-500 @enderror">
                            @error('jumlah') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <!-- Alamat Penjemputan -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Alamat Penjemputan
                        </label>
                        <textarea wire:model="alamat_penjemputan" placeholder="Alamat lengkap penjemputan" rows="3"
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('alamat_penjemputan') border-red-500 @enderror"></textarea>
                        @error('alamat_penjemputan') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                    </div>

                    <!-- Alamat Pengiriman -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Alamat Pengiriman
                        </label>
                        <textarea wire:model="alamat_pengiriman" placeholder="Alamat lengkap pengiriman" rows="3"
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('alamat_pengiriman') border-red-500 @enderror"></textarea>
                        @error('alamat_pengiriman') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                    </div>

                    <!-- Status & Deskripsi -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Status
                            </label>
                            <select wire:model="status"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('status') border-red-500 @enderror">
                                <option value="pending">Pending</option>
                                <option value="accepted">Diterima</option>
                                <option value="rejected">Ditolak</option>
                                <option value="delivered">Terkirim</option>
                            </select>
                            @error('status') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Deskripsi (Opsional)
                            </label>
                            <input type="text" wire:model="description" placeholder="Catatan tambahan"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                    </div>

                    <!-- Buttons -->
                    <div class="flex gap-4 justify-end">
                        <button type="button" wire:click="closeForm"
                                class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition font-medium">
                            Batal
                        </button>
                        <button type="submit"
                                class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium">
                            {{ $editingId ? 'Update' : 'Simpan' }} Pesanan
                        </button>
                    </div>
                </form>
            </div>
        @endif

        <!-- Pesanan Table -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-gray-100 border-b border-gray-200">
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Nomor</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Nama</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Tipe</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Jumlah</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Penjemputan</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Pengiriman</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Status</th>
                            <th class="px-6 py-3 text-center text-sm font-semibold text-gray-700">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($this->pesanans as $pesanan)
                            <tr class="hover:bg-gray-50 transition">
                                <!-- Nomor -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                        {{ $pesanan->nomor }}
                                    </span>
                                </td>

                                <!-- Nama -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $pesanan->nama }}
                                    </div>
                                </td>

                                <!-- Tipe -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-purple-100 text-purple-800">
                                        {{ ucfirst($pesanan->tipe) }}
                                    </span>
                                </td>

                                <!-- Jumlah -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-700 font-medium">
                                        {{ $pesanan->jumlah }}
                                    </div>
                                </td>

                                <!-- Alamat Penjemputan -->
                                <td class="px-6 py-4">
                                    <details class="inline-block">
                                        <summary class="cursor-pointer text-blue-600 hover:text-blue-800 text-sm font-medium">
                                            Lihat
                                        </summary>
                                        <div class="absolute bg-white p-3 rounded-lg shadow mt-1 w-64 z-10 border border-gray-200 text-xs">
                                            {{ $pesanan->alamat_penjemputan }}
                                        </div>
                                    </details>
                                </td>

                                <!-- Alamat Pengiriman -->
                                <td class="px-6 py-4">
                                    <details class="inline-block">
                                        <summary class="cursor-pointer text-blue-600 hover:text-blue-800 text-sm font-medium">
                                            Lihat
                                        </summary>
                                        <div class="absolute bg-white p-3 rounded-lg shadow mt-1 w-64 z-10 border border-gray-200 text-xs">
                                            {{ $pesanan->alamat_pengiriman }}
                                        </div>
                                    </details>
                                </td>

                                <!-- Status -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium
                                        @if($pesanan->status === 'pending') bg-yellow-100 text-yellow-800
                                        @elseif($pesanan->status === 'accepted') bg-green-100 text-green-800
                                        @elseif($pesanan->status === 'rejected') bg-red-100 text-red-800
                                        @elseif($pesanan->status === 'delivered') bg-blue-100 text-blue-800
                                        @endif
                                    ">
                                        @if($pesanan->status === 'pending')
                                            ⏳ Menunggu
                                        @elseif($pesanan->status === 'accepted')
                                            ✅ Diterima
                                        @elseif($pesanan->status === 'rejected')
                                            ❌ Ditolak
                                        @elseif($pesanan->status === 'delivered')
                                            🚚 Terkirim
                                        @endif
                                    </span>
                                </td>

                                <!-- Aksi -->
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <div class="flex justify-center gap-2">
                                        @if($pesanan->status === 'pending')
                                            <button wire:click="acceptPesanan({{ $pesanan->id }})"
                                                    class="px-3 py-1 text-xs bg-green-500 text-white rounded hover:bg-green-600 transition font-medium">
                                                Terima
                                            </button>
                                            <button wire:click="rejectPesanan({{ $pesanan->id }})"
                                                    class="px-3 py-1 text-xs bg-red-500 text-white rounded hover:bg-red-600 transition font-medium">
                                                Tolak
                                            </button>
                                        @elseif($pesanan->status === 'accepted')
                                            <button wire:click="markDelivered({{ $pesanan->id }})"
                                                    class="px-3 py-1 text-xs bg-blue-500 text-white rounded hover:bg-blue-600 transition font-medium">
                                                Terkirim
                                            </button>
                                        @endif

                                        <button wire:click="editPesanan({{ $pesanan->id }})"
                                                class="px-3 py-1 text-xs bg-yellow-500 text-white rounded hover:bg-yellow-600 transition font-medium">
                                            Edit
                                        </button>

                                        <button wire:click="deletePesanan({{ $pesanan->id }})"
                                                onclick="return confirm('Yakin ingin menghapus pesanan ini?')"
                                                class="px-3 py-1 text-xs bg-red-600 text-white rounded hover:bg-red-700 transition font-medium">
                                            Hapus
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-6 py-12 text-center">
                                    <div class="text-gray-500">
                                        <p class="text-lg font-medium">Tidak ada pesanan</p>
                                        <p class="text-sm mt-1">Mulai dengan menambahkan pesanan baru</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>