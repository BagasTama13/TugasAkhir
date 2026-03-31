<div class="min-h-screen bg-gray-50 p-8">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="sticky top-0 bg-gray-50 z-30 pb-4 mb-0">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Riwayat Aktivitas</h1>
            <p class="text-gray-500">Pantau semua aktivitas admin dalam sistem</p>
        </div>

        <!-- Summary Stats -->
        <div class="sticky top-32 bg-gray-50 z-20 pt-4 pb-4 mb-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <!-- Total Activities -->
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="text-5xl font-bold text-gray-900">
                        {{ $this->activities->total() }}
                    </div>
                    <p class="text-gray-600 text-sm mt-2">Total Aktivitas</p>
                </div>

                <!-- Today's Activities -->
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="text-5xl font-bold text-blue-600">
                        {{ \App\Models\Activity::whereDate('created_at', today())->count() }}
                    </div>
                    <p class="text-gray-600 text-sm mt-2">Aktivitas Hari Ini</p>
                </div>

                <!-- This Week -->
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="text-5xl font-bold text-orange-600">
                        {{ \App\Models\Activity::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count() }}
                    </div>
                    <p class="text-gray-600 text-sm mt-2">Aktivitas Minggu Ini</p>
                </div>

                <!-- Produk Changes -->
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="text-5xl font-bold text-green-600">
                        {{ \App\Models\Activity::where('entity_type', 'Produk')->count() }}
                    </div>
                    <p class="text-gray-600 text-sm mt-2">Perubahan Produk</p>
                </div>
            </div>
        </div>

        <!-- Filter Section -->
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <!-- Filter Action -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Aksi</label>
                    <select wire:model.live="filterAction" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">Semua Aksi</option>
                        <option value="create">Tambah</option>
                        <option value="update">Ubah</option>
                        <option value="delete">Hapus</option>
                        <option value="accept">Terima</option>
                        <option value="reject">Tolak</option>
                        <option value="recap">Rekap</option>
                    </select>
                </div>

                <!-- Filter Entity Type -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Data</label>
                    <select wire:model.live="filterEntity" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">Semua Data</option>
                        <option value="Produk">Produk (Etalase)</option>
                        <option value="Pesanan">Pesanan</option>
                        <option value="Pemasukan">Pemasukan</option>
                    </select>
                </div>

                <!-- Filter Date -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal</label>
                    <input type="date" wire:model.live="filterDate" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>

                <!-- Reset Button -->
                <div class="flex items-end">
                    <button wire:click="resetFilters" class="w-full px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition font-medium">
                        Reset Filter
                    </button>
                </div>
            </div>
        </div>

        <!-- Activity Table -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-gray-100 border-b border-gray-200">
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Waktu</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Admin</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Aksi</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Data</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Keterangan</th>
                            <th class="px-6 py-3 text-center text-sm font-semibold text-gray-700">Detail</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($this->activities->items() as $activity)
                            <tr class="hover:bg-gray-50 transition">
                                <!-- Waktu -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $activity->created_at->format('d/m/Y') }}
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        {{ $activity->created_at->format('H:i:s') }}
                                    </div>
                                </td>

                                <!-- Admin -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $activity->user?->name ?? 'System' }}
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        {{ $activity->user?->email ?? '-' }}
                                    </div>
                                </td>

                                <!-- Aksi -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                        @if($activity->action === 'create') bg-green-100 text-green-800
                                        @elseif($activity->action === 'update') bg-blue-100 text-blue-800
                                        @elseif($activity->action === 'delete') bg-red-100 text-red-800
                                        @elseif($activity->action === 'accept') bg-green-100 text-green-800
                                        @elseif($activity->action === 'reject') bg-orange-100 text-orange-800
                                        @elseif($activity->action === 'recap') bg-purple-100 text-purple-800
                                        @else bg-gray-100 text-gray-800
                                        @endif
                                    ">
                                        @if($activity->action === 'create')
                                            📝 Tambah
                                        @elseif($activity->action === 'update')
                                            ✏️ Ubah
                                        @elseif($activity->action === 'delete')
                                            🗑️ Hapus
                                        @elseif($activity->action === 'accept')
                                            ✅ Terima
                                        @elseif($activity->action === 'reject')
                                            ❌ Tolak
                                        @elseif($activity->action === 'recap')
                                            📊 Rekap
                                        @else
                                            {{ ucfirst($activity->action) }}
                                        @endif
                                    </span>
                                </td>

                                <!-- Data -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $activity->entity_type }}
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        ID: {{ $activity->entity_id }}
                                    </div>
                                </td>

                                <!-- Keterangan -->
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-700">
                                        {{ $activity->description }}
                                    </div>
                                </td>

                                <!-- Detail Button -->
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    @if($activity->old_values || $activity->new_values)
                                        <details class="inline-block">
                                            <summary class="cursor-pointer px-3 py-1 text-blue-600 hover:text-blue-800 font-medium text-sm">
                                                Lihat
                                            </summary>
                                            <div class="absolute right-0 mt-2 w-96 bg-white rounded-lg shadow-lg p-4 z-10 text-left border border-gray-200">
                                                @if($activity->old_values)
                                                    <div class="mb-4">
                                                        <h4 class="font-semibold text-gray-900 mb-2 text-sm">Data Lama:</h4>
                                                        <div class="bg-red-50 p-3 rounded text-xs text-gray-700 overflow-auto max-h-32">
                                                            @foreach($activity->old_values as $key => $value)
                                                                <div class="mb-1">
                                                                    <span class="font-medium">{{ $key }}:</span>
                                                                    <span class="text-red-600">{{ is_array($value) || is_object($value) ? json_encode($value) : $value }}</span>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                @endif

                                                @if($activity->new_values)
                                                    <div>
                                                        <h4 class="font-semibold text-gray-900 mb-2 text-sm">Data Baru:</h4>
                                                        <div class="bg-green-50 p-3 rounded text-xs text-gray-700 overflow-auto max-h-32">
                                                            @foreach($activity->new_values as $key => $value)
                                                                <div class="mb-1">
                                                                    <span class="font-medium">{{ $key }}:</span>
                                                                    <span class="text-green-600">{{ is_array($value) || is_object($value) ? json_encode($value) : $value }}</span>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        </details>
                                    @else
                                        <span class="text-gray-400 text-sm">-</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center">
                                    <div class="text-gray-500">
                                        <p class="text-lg font-medium">Tidak ada aktivitas</p>
                                        <p class="text-sm mt-1">Coba ubah filter Anda</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="bg-white px-6 py-4 border-t border-gray-200">
                {{ $this->activities->links() }}
            </div>
        </div>
    </div>
</div>