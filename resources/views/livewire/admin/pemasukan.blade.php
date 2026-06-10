<div class="min-h-screen bg-[#F8FAFC] pb-12">
    <!-- Premium Header -->
    <div class="bg-white border-b border-slate-200 sticky top-0 z-30 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="py-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-slate-900 tracking-tight">
                        {{ isset($isOwnerView) ? 'Dashboard Pemasukan Owner' : 'Laporan Pemasukan' }}
                    </h1>
                    <p class="text-sm text-slate-500 mt-1">Kelola dan pantau arus pendapatan bisnis Anda</p>
                </div>
                
                <div class="flex items-center gap-3">
                    <button wire:click="resetFilters" class="inline-flex items-center px-4 py-2 text-sm font-medium text-slate-700 bg-white border border-slate-300 rounded-lg hover:bg-slate-50 transition-all duration-200 shadow-sm">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                        Reset Filter
                    </button>
                </div>
            </div>

            <!-- Quick Stats Summary -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 pb-6">
                <!-- Total Card -->
                <div class="bg-gradient-to-br from-indigo-600 to-indigo-700 rounded-xl p-5 text-white shadow-lg shadow-indigo-200">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-indigo-100 text-xs font-semibold uppercase tracking-wider">Total Pendapatan</p>
                            <h3 class="text-2xl font-bold mt-1">Rp {{ number_format($this->totalPemasukan, 0, ',', '.') }}</h3>
                        </div>
                        <div class="p-2 bg-indigo-500/30 rounded-lg">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                    </div>
                    <div class="mt-4 flex items-center text-xs text-indigo-100">
                        <span class="bg-indigo-500/40 px-2 py-0.5 rounded mr-2">Status Terbayar</span>
                        <span>Total dari seluruh pesanan lunas</span>
                    </div>
                </div>

                <!-- Monthly Card -->
                <div class="bg-white rounded-xl p-5 border border-slate-200 shadow-sm">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-slate-500 text-xs font-semibold uppercase tracking-wider">Pendapatan Bulan Ini</p>
                            <h3 class="text-2xl font-bold text-slate-900 mt-1">Rp {{ number_format($this->pemasukaBulanIni, 0, ',', '.') }}</h3>
                        </div>
                        <div class="p-2 bg-blue-50 rounded-lg text-blue-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        </div>
                    </div>
                    <div class="mt-4 flex items-center text-xs text-slate-500">
                        <span class="text-blue-600 font-medium mr-1">{{ now()->format('F Y') }}</span>
                        <span>• Pesanan lunas bulan ini</span>
                    </div>
                </div>

                <!-- Pending Card -->
                <div class="bg-white rounded-xl p-5 border border-slate-200 shadow-sm">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-slate-500 text-xs font-semibold uppercase tracking-wider">Potensi Pendapatan</p>
                            <h3 class="text-2xl font-bold text-amber-600 mt-1">Rp {{ number_format($this->pemasukanPending, 0, ',', '.') }}</h3>
                        </div>
                        <div class="p-2 bg-amber-50 rounded-lg text-amber-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                    </div>
                    <div class="mt-4 flex items-center text-xs text-slate-500">
                        <span class="text-amber-600 font-medium mr-1">Belum Dibayar</span>
                        <span>• Pesanan diterima, belum dibayar</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Area -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-8">
        <!-- Filter Bar -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-4 mb-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                    <input wire:model.live.debounce.300ms="search" type="text" placeholder="Cari keterangan..." class="block w-full pl-10 pr-3 py-2 border border-slate-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                </div>

                <div class="flex gap-2">
                    <div class="w-1/2">
                        <input wire:model.live="startDate" type="date" class="block w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                    </div>
                    <div class="w-1/2">
                        <input wire:model.live="endDate" type="date" class="block w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                    </div>
                </div>

                <div>
                    <select wire:model.live="statusFilter" class="block w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                        <option value="">Semua Status</option>
                        <option value="pending">⏳ Pending</option>
                        <option value="confirmed">✅ Confirmed</option>
                        <option value="rejected">❌ Rejected</option>
                    </select>
                </div>

                <div class="flex justify-end">
                    <div class="text-xs text-slate-400 flex items-center">
                        Menampilkan {{ count($this->pemasukans) }} data
                    </div>
                </div>
            </div>
        </div>

        <!-- Table Container -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/50">
                            <th class="px-6 py-4 text-xs font-semibold text-slate-600 uppercase tracking-wider border-b border-slate-100">Tanggal</th>
                            <th class="px-6 py-4 text-xs font-semibold text-slate-600 uppercase tracking-wider border-b border-slate-100">Keterangan & Catatan</th>
                            <th class="px-6 py-4 text-xs font-semibold text-slate-600 uppercase tracking-wider border-b border-slate-100">Kategori</th>
                            <th class="px-6 py-4 text-xs font-semibold text-slate-600 uppercase tracking-wider border-b border-slate-100 text-right">Jumlah</th>
                            <th class="px-6 py-4 text-xs font-semibold text-slate-600 uppercase tracking-wider border-b border-slate-100">Status</th>
                            <th class="px-6 py-4 text-xs font-semibold text-slate-600 uppercase tracking-wider border-b border-slate-100">Petugas</th>
                            <th class="px-6 py-4 text-xs font-semibold text-slate-600 uppercase tracking-wider border-b border-slate-100 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($this->pemasukans as $pemasukan)
                            <tr class="group hover:bg-slate-50/80 transition-colors duration-150">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex flex-col">
                                        <span class="text-sm font-semibold text-slate-900">{{ $pemasukan->tanggal->format('d M Y') }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex flex-col max-w-xs">
                                        <span class="text-sm font-medium text-slate-800 line-clamp-1">{{ $pemasukan->keterangan }}</span>
                                        @if($pemasukan->catatan)
                                            <span class="text-xs text-slate-500 italic mt-0.5 line-clamp-1">{{ $pemasukan->catatan }}</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-50 text-indigo-700 border border-indigo-100">
                                        {{ ucfirst($pemasukan->kategori) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right">
                                    <span class="text-sm font-bold text-slate-900">Rp {{ number_format($pemasukan->jumlah, 0, ',', '.') }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $statusClasses = [
                                            'pending' => 'bg-amber-50 text-amber-700 border-amber-200',
                                            'confirmed' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                            'rejected' => 'bg-rose-50 text-rose-700 border-rose-200',
                                        ][$pemasukan->status] ?? 'bg-slate-50 text-slate-700 border-slate-200';
                                        
                                        $statusIcons = [
                                            'pending' => '⏳',
                                            'confirmed' => '✅',
                                            'rejected' => '❌',
                                        ][$pemasukan->status] ?? '•';
                                    @endphp
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold border {{ $statusClasses }}">
                                        <span class="mr-1.5">{{ $statusIcons }}</span>
                                        {{ strtoupper($pemasukan->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="h-7 w-7 rounded-full bg-slate-200 flex items-center justify-center text-[10px] font-bold text-slate-500 mr-2 uppercase">
                                            {{ substr($pemasukan->user?->name ?? '?', 0, 2) }}
                                        </div>
                                        <span class="text-sm text-slate-600 font-medium">{{ $pemasukan->user?->name ?? '-' }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    @if(!$this->readonly)
                                        <div class="flex items-center justify-center gap-2">
                                            @if($pemasukan->status === 'pending')
                                                <button wire:click="confirmPemasukan({{ $pemasukan->id }})" 
                                                        class="p-1.5 bg-emerald-50 text-emerald-600 rounded-lg hover:bg-emerald-600 hover:text-white transition-all shadow-sm border border-emerald-100"
                                                        title="Konfirmasi">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                                </button>
                                                <button wire:click="rejectPemasukan({{ $pemasukan->id }})" 
                                                        class="p-1.5 bg-rose-50 text-rose-600 rounded-lg hover:bg-rose-600 hover:text-white transition-all shadow-sm border border-rose-100"
                                                        title="Tolak">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                                </button>
                                            @endif
                                            <button wire:click="deletePemasukan({{ $pemasukan->id }})" 
                                                    onclick="return confirm('Yakin ingin menghapus data ini?')"
                                                    class="p-1.5 bg-slate-50 text-slate-400 rounded-lg hover:bg-slate-900 hover:text-white transition-all shadow-sm border border-slate-200"
                                                    title="Hapus">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        </div>
                                    @else
                                        <span class="text-xs text-slate-400 italic">Read-only</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-20 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <div class="bg-slate-50 p-4 rounded-full mb-4">
                                            <svg class="w-12 h-12 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                        </div>
                                        <h3 class="text-lg font-bold text-slate-900">Belum Ada Data</h3>
                                        <p class="text-slate-500 max-w-xs mx-auto mt-1 text-sm">Tidak ditemukan data pemasukan untuk filter yang Anda pilih saat ini.</p>
                                        <button wire:click="resetFilters" class="mt-4 text-indigo-600 font-semibold text-sm hover:underline">Reset pencarian</button>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if(count($this->pemasukans) > 0)
                <div class="bg-slate-50 px-6 py-4 border-t border-slate-100 flex justify-between items-center">
                    <p class="text-xs text-slate-500 italic">
                        * Semua data di atas diambil dari basis data sistem secara real-time.
                    </p>
                    <div class="flex items-center gap-2">
                        <span class="h-2 w-2 rounded-full bg-emerald-500 animate-pulse"></span>
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Sistem Aktif</span>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>