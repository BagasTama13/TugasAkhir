<div class="min-h-screen bg-[#F8FAFC] pb-12">
    <!-- Premium Header -->
    <div class="bg-white border-b border-slate-200 sticky top-0 z-30 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="py-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Riwayat Aktivitas</h1>
                    <p class="text-sm text-slate-500 mt-1">Log sistem untuk memantau setiap perubahan dan aksi pengguna</p>
                </div>
                
                <div class="flex items-center gap-3">
                    <button wire:click="resetFilters" class="inline-flex items-center px-4 py-2 text-sm font-bold text-slate-700 bg-white border border-slate-300 rounded-lg hover:bg-slate-50 transition-all duration-200 shadow-sm">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                        Bersihkan Filter
                    </button>
                </div>
            </div>

            <!-- Stats Overview -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 pb-6">
                <div class="bg-slate-50 rounded-xl p-4 border border-slate-100">
                    <div class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Total Log</div>
                    <div class="text-2xl font-bold text-slate-900">{{ $this->totalActivities }}</div>
                </div>
                <div class="bg-blue-50 rounded-xl p-4 border border-blue-100">
                    <div class="text-[10px] font-bold text-blue-500 uppercase tracking-widest mb-1">Hari Ini</div>
                    <div class="text-2xl font-bold text-blue-600">{{ $this->todayActivities }}</div>
                </div>
                <div class="bg-amber-50 rounded-xl p-4 border border-amber-100">
                    <div class="text-[10px] font-bold text-amber-500 uppercase tracking-widest mb-1">Minggu Ini</div>
                    <div class="text-2xl font-bold text-amber-600">{{ $this->weekActivities }}</div>
                </div>
                @if(!isset($isWorkerView))
                    <div class="bg-emerald-50 rounded-xl p-4 border border-emerald-100">
                        <div class="text-[10px] font-bold text-emerald-500 uppercase tracking-widest mb-1">Perubahan Produk</div>
                        <div class="text-2xl font-bold text-emerald-600">{{ $this->produkActivities }}</div>
                    </div>
                @else
                    <div class="bg-slate-50 rounded-xl p-4 border border-slate-100">
                        <div class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Uptime</div>
                        <div class="text-2xl font-bold text-slate-900">100%</div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-8">
        <!-- Filter Bar -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-4 mb-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Jenis Aksi</label>
                    <select wire:model.live="filterAction" class="w-full px-4 py-2 border border-slate-200 rounded-lg text-sm font-medium focus:ring-2 focus:ring-indigo-500 outline-none transition-all">
                        <option value="">Semua Aksi</option>
                        <option value="create">📝 Tambah</option>
                        <option value="update">✏️ Ubah</option>
                        <option value="delete">🗑️ Hapus</option>
                        <option value="accept">✅ Terima</option>
                        <option value="reject">❌ Tolak</option>
                        <option value="recap">📊 Rekap</option>
                    </select>
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Jenis Data</label>
                    <select wire:model.live="filterEntity" class="w-full px-4 py-2 border border-slate-200 rounded-lg text-sm font-medium focus:ring-2 focus:ring-indigo-500 outline-none transition-all">
                        <option value="">Semua Entitas</option>
                        @if(!isset($isWorkerView))
                            <option value="Produk">Produk (Etalase)</option>
                        @endif
                        <option value="Pesanan">Pesanan</option>
                        <option value="Pemasukan">Pemasukan</option>
                    </select>
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Spesifik Tanggal</label>
                    <input type="date" wire:model.live="filterDate" class="w-full px-4 py-2 border border-slate-200 rounded-lg text-sm font-medium focus:ring-2 focus:ring-indigo-500 outline-none transition-all">
                </div>
            </div>
        </div>

        <!-- Activity Log Table -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/50">
                            <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest border-b border-slate-100">Waktu & Tanggal</th>
                            <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest border-b border-slate-100">Pelaku</th>
                            <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest border-b border-slate-100 text-center">Aksi</th>
                            <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest border-b border-slate-100">Detail Entitas</th>
                            <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest border-b border-slate-100">Keterangan</th>
                            <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest border-b border-slate-100 text-center">Diff</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse($this->activities as $activity)
                            <tr class="group hover:bg-slate-50/50 transition-all duration-150">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex flex-col">
                                        <span class="text-sm font-bold text-slate-900">{{ $activity->created_at->format('d M Y') }}</span>
                                        <span class="text-[10px] text-slate-400 font-bold uppercase tracking-tight">{{ $activity->created_at->format('H:i:s') }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-3">
                                        <div class="h-8 w-8 bg-slate-100 rounded-full flex items-center justify-center text-[10px] font-bold text-slate-500 border border-slate-200">
                                            {{ substr($activity->user?->name ?? '?', 0, 2) }}
                                        </div>
                                        <div class="flex flex-col">
                                            <span class="text-sm font-semibold text-slate-700">{{ $activity->user?->name ?? 'System' }}</span>
                                            <span class="text-[10px] text-slate-400 font-medium">{{ $activity->user?->username ?? '-' }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    @php
                                        $actionStyles = [
                                            'create' => 'bg-emerald-50 text-emerald-600 border-emerald-100',
                                            'update' => 'bg-blue-50 text-blue-600 border-blue-100',
                                            'delete' => 'bg-rose-50 text-rose-600 border-rose-100',
                                            'accept' => 'bg-emerald-50 text-emerald-600 border-emerald-100',
                                            'reject' => 'bg-amber-50 text-amber-600 border-amber-100',
                                            'recap' => 'bg-purple-50 text-purple-600 border-purple-100',
                                        ][$activity->action] ?? 'bg-slate-50 text-slate-600 border-slate-100';
                                        
                                        $actionLabels = [
                                            'create' => 'Tambah',
                                            'update' => 'Ubah',
                                            'delete' => 'Hapus',
                                            'accept' => 'Terima',
                                            'reject' => 'Tolak',
                                            'recap' => 'Rekap',
                                        ][$activity->action] ?? ucfirst($activity->action);
                                    @endphp
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-[10px] font-bold border {{ $actionStyles }} uppercase tracking-wider">
                                        {{ $actionLabels }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex flex-col">
                                        <span class="text-xs font-bold text-slate-900">{{ $activity->entity_type }}</span>
                                        <span class="text-[10px] text-slate-400 font-medium">Ref ID: #{{ $activity->entity_id }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <p class="text-sm text-slate-600 font-medium line-clamp-1 max-w-xs">{{ $activity->description }}</p>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    @if($activity->old_values || $activity->new_values)
                                        <button x-on:click="alert('Fitur detail perbandingan akan segera hadir.')" class="p-1.5 bg-slate-50 text-slate-400 rounded-lg hover:bg-indigo-600 hover:text-white transition-all border border-slate-200">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                        </button>
                                    @else
                                        <span class="text-xs text-slate-300 italic">-</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-20 text-center">
                                    <div class="flex flex-col items-center">
                                        <div class="h-16 w-16 bg-slate-50 rounded-full flex items-center justify-center mb-4 text-slate-300">
                                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        </div>
                                        <p class="text-sm font-bold text-slate-900">Tidak ada log aktivitas</p>
                                        <p class="text-xs text-slate-400 mt-1">Coba sesuaikan filter pencarian Anda.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($this->activities->hasPages())
                <div class="px-6 py-4 bg-slate-50/50 border-t border-slate-100">
                    {{ $this->activities->links() }}
                </div>
            @endif
        </div>
    </div>
</div>