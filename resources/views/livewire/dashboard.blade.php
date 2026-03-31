<div class="min-h-screen bg-gray-50 p-8">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Dashboard</h1>
            <p class="text-gray-500 mt-2">Selamat datang di panel admin BPTrans</p>
        </div>

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Total Pesanan -->
            <div class="bg-white rounded-lg shadow p-6 hover:shadow-lg transition">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm font-medium">Total Pesanan</p>
                        <p class="text-4xl font-bold text-gray-900 mt-2">{{ $this->totalPesanan }}</p>
                    </div>
                    <div class="text-5xl text-blue-500 opacity-20">📦</div>
                </div>
                <p class="text-gray-500 text-xs mt-4">Semua pesanan masuk</p>
            </div>

            <!-- Pesanan Pending -->
            <div class="bg-white rounded-lg shadow p-6 hover:shadow-lg transition">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm font-medium">Menunggu</p>
                        <p class="text-4xl font-bold text-yellow-600 mt-2">{{ $this->pesananPending }}</p>
                    </div>
                    <div class="text-5xl opacity-20">⏳</div>
                </div>
                <p class="text-yellow-600 text-xs mt-4">Pesanan pending</p>
            </div>

            <!-- Pesanan Accepted -->
            <div class="bg-white rounded-lg shadow p-6 hover:shadow-lg transition">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm font-medium">Diterima</p>
                        <p class="text-4xl font-bold text-green-600 mt-2">{{ $this->pesananAccepted }}</p>
                    </div>
                    <div class="text-5xl opacity-20">✅</div>
                </div>
                <p class="text-green-600 text-xs mt-4">Pesanan diterima</p>
            </div>

            <!-- Pesanan Delivered -->
            <div class="bg-white rounded-lg shadow p-6 hover:shadow-lg transition">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm font-medium">Terkirim</p>
                        <p class="text-4xl font-bold text-blue-600 mt-2">{{ $this->pesananDelivered }}</p>
                    </div>
                    <div class="text-5xl opacity-20">🚚</div>
                </div>
                <p class="text-blue-600 text-xs mt-4">Pesanan terkirim</p>
            </div>
        </div>

        <!-- Secondary Stats -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <!-- Total Produk -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm font-medium">Total Produk</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">{{ $this->totalProduk }}</p>
                    </div>
                    <div class="text-4xl text-purple-500 opacity-20">🏪</div>
                </div>
            </div>

            <!-- Aktivitas Hari Ini -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm font-medium">Aktivitas Hari Ini</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">{{ $this->todayActivities }}</p>
                    </div>
                    <div class="text-4xl text-orange-500 opacity-20">📊</div>
                </div>
            </div>

            <!-- Status Sidebar -->
            <div class="bg-white rounded-lg shadow p-6">
                <p class="text-gray-500 text-sm font-medium mb-3">Status Sistem</p>
                <div class="space-y-2">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Database</span>
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            ✓ Connected
                        </span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Storage</span>
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            ✓ Active
                        </span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Cache</span>
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            ✓ Ready
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activities -->
        <div class="bg-white rounded-lg shadow">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-xl font-bold text-gray-900">Aktivitas Terbaru</h2>
            </div>

            <div class="divide-y divide-gray-200">
                @forelse($this->recentActivities as $activity)
                    <div class="p-6 hover:bg-gray-50 transition">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <div class="flex items-center gap-3">
                                    <!-- Action Badge -->
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium
                                        @if($activity->action === 'create') bg-green-100 text-green-800
                                        @elseif($activity->action === 'update') bg-blue-100 text-blue-800
                                        @elseif($activity->action === 'delete') bg-red-100 text-red-800
                                        @elseif($activity->action === 'accept') bg-green-100 text-green-800
                                        @elseif($activity->action === 'reject') bg-orange-100 text-orange-800
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
                                        @else
                                            {{ ucfirst($activity->action) }}
                                        @endif
                                    </span>

                                    <span class="text-sm font-medium text-gray-900">
                                        {{ $activity->description }}
                                    </span>
                                </div>

                                <div class="flex items-center gap-4 mt-2 text-xs text-gray-500">
                                    <span>{{ $activity->user?->name ?? 'System' }}</span>
                                    <span>{{ $activity->entity_type }} #{{ $activity->entity_id }}</span>
                                    <span>{{ $activity->created_at->diffForHumans() }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="p-6 text-center text-gray-500">
                        <p class="text-sm">Tidak ada aktivitas</p>
                    </div>
                @endforelse
            </div>

            <div class="p-6 border-t border-gray-200 text-center">
                <a href="/activity" class="text-blue-600 hover:text-blue-800 font-medium text-sm">
                    Lihat semua aktivitas →
                </a>
            </div>
        </div>

        <!-- Quick Links -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mt-8">
            <a href="/pesanan" class="bg-gradient-to-br from-blue-500 to-blue-600 text-white p-6 rounded-lg shadow hover:shadow-lg transition text-center">
                <div class="text-3xl mb-2">📦</div>
                <p class="font-semibold">Pesanan</p>
                <p class="text-xs mt-1 text-blue-100">Kelola pesanan</p>
            </a>

            <a href="/etalase" class="bg-gradient-to-br from-purple-500 to-purple-600 text-white p-6 rounded-lg shadow hover:shadow-lg transition text-center">
                <div class="text-3xl mb-2">🏪</div>
                <p class="font-semibold">Etalase</p>
                <p class="text-xs mt-1 text-purple-100">Produk & Barang</p>
            </a>

            <a href="/pemasukan" class="bg-gradient-to-br from-green-500 to-green-600 text-white p-6 rounded-lg shadow hover:shadow-lg transition text-center">
                <div class="text-3xl mb-2">💰</div>
                <p class="font-semibold">Pemasukan</p>
                <p class="text-xs mt-1 text-green-100">Laporan keuangan</p>
            </a>

            <a href="/activity" class="bg-gradient-to-br from-orange-500 to-orange-600 text-white p-6 rounded-lg shadow hover:shadow-lg transition text-center">
                <div class="text-3xl mb-2">📋</div>
                <p class="font-semibold">Activity</p>
                <p class="text-xs mt-1 text-orange-100">Riwayat aksi</p>
            </a>
        </div>
    </div>
</div>