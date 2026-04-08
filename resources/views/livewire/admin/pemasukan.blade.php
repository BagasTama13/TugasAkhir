<div class="min-h-screen bg-gray-50 p-8">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="sticky top-0 bg-gray-50 z-30 pb-4 mb-0">
            <h1 class="text-3xl font-bold text-gray-900">Laporan Pemasukan</h1>
            <p class="text-gray-500 mt-2">Kelola semua pemasukan dan pendapatan</p>
        </div>

        <!-- Summary Stats -->
        <div class="sticky top-32 bg-gray-50 z-20 pt-4 pb-4 mb-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <!-- Total Pemasukan -->
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="text-sm font-medium text-gray-500 mb-2">Total Pemasukan</div>
                    <div class="text-4xl font-bold text-green-600">
                        Rp {{ number_format($this->totalPemasukan, 0, ',', '.') }}
                    </div>
                    <p class="text-gray-600 text-xs mt-2">Semua pemasukan konfirmasi</p>
                </div>

                <!-- Pemasukan Bulan Ini -->
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="text-sm font-medium text-gray-500 mb-2">Pemasukan Bulan Ini</div>
                    <div class="text-4xl font-bold text-blue-600">
                        Rp {{ number_format($this->pemasukaBulanIni, 0, ',', '.') }}
                    </div>
                    <p class="text-gray-600 text-xs mt-2">{{ now()->format('F Y') }}</p>
                </div>

                <!-- Pemasukan Pending -->
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="text-sm font-medium text-gray-500 mb-2">Pemasukan Menunggu</div>
                    <div class="text-4xl font-bold text-yellow-600">
                        Rp {{ number_format($this->pemasukanPending, 0, ',', '.') }}
                    </div>
                    <p class="text-gray-600 text-xs mt-2">Belum dikonfirmasi</p>
                </div>
            </div>
        </div>



        <!-- Pemasukan Table -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-gray-100 border-b border-gray-200">
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Tanggal</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Keterangan</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Kategori</th>
                            <th class="px-6 py-3 text-right text-sm font-semibold text-gray-700">Jumlah</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Status</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Admin</th>
                            @if(!$this->readonly)
                                <th class="px-6 py-3 text-center text-sm font-semibold text-gray-700">Aksi</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($this->pemasukans as $pemasukan)
                            <tr class="hover:bg-gray-50 transition">
                                <!-- Tanggal -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $pemasukan->tanggal->format('d/m/Y') }}
                                    </div>
                                </td>

                                <!-- Keterangan -->
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900">
                                        {{ $pemasukan->keterangan }}
                                    </div>
                                    @if($pemasukan->catatan)
                                        <div class="text-xs text-gray-500 mt-1">
                                            {{ $pemasukan->catatan }}
                                        </div>
                                    @endif
                                </td>

                                <!-- Kategori -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-purple-100 text-purple-800">
                                        {{ ucfirst($pemasukan->kategori) }}
                                    </span>
                                </td>

                                <!-- Jumlah -->
                                <td class="px-6 py-4 whitespace-nowrap text-right">
                                    <div class="text-sm font-semibold text-gray-900">
                                        Rp {{ number_format($pemasukan->jumlah, 0, ',', '.') }}
                                    </div>
                                </td>

                                <!-- Status -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium
                                        @if($pemasukan->status === 'pending') bg-yellow-100 text-yellow-800
                                        @elseif($pemasukan->status === 'confirmed') bg-green-100 text-green-800
                                        @elseif($pemasukan->status === 'rejected') bg-red-100 text-red-800
                                        @endif
                                    ">
                                        @if($pemasukan->status === 'pending')
                                            ⏳ Pending
                                        @elseif($pemasukan->status === 'confirmed')
                                            ✅ Confirmed
                                        @elseif($pemasukan->status === 'rejected')
                                            ❌ Rejected
                                        @endif
                                    </span>
                                </td>

                                <!-- Admin -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        {{ $pemasukan->user?->name ?? '-' }}
                                    </div>
                                </td>

                                <!-- Aksi -->
                                @if(!$this->readonly)
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <div class="flex justify-center gap-2">
                                            @if($pemasukan->status === 'pending')
                                                <button wire:click="confirmPemasukan({{ $pemasukan->id }})"
                                                        class="px-3 py-1 text-xs bg-green-500 text-white rounded hover:bg-green-600 transition font-medium">
                                                    Konfirmasi
                                                </button>
                                                <button wire:click="rejectPemasukan({{ $pemasukan->id }})"
                                                        class="px-3 py-1 text-xs bg-red-500 text-white rounded hover:bg-red-600 transition font-medium">
                                                    Tolak
                                                </button>
                                            @endif

                                            <button wire:click="deletePemasukan({{ $pemasukan->id }})"
                                                    onclick="return confirm('Yakin ingin menghapus pemasukan ini?')"
                                                    class="px-3 py-1 text-xs bg-red-600 text-white rounded hover:bg-red-700 transition font-medium">
                                                Hapus
                                            </button>
                                        </div>
                                    </td>
                                @endif
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center">
                                    <div class="text-gray-500">
                                        <p class="text-lg font-medium">Tidak ada pemasukan</p>
                                        <p class="text-sm mt-1">Mulai dengan menambahkan pemasukan baru</p>
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