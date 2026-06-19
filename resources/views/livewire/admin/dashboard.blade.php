<div class="space-y-6" wire:poll.5s>
    <!-- Clean Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Dashboard Utama</h1>
            <p class="text-sm text-slate-500">Selamat datang kembali di panel administrasi BPTrans.</p>
        </div>
        <div class="text-sm font-semibold text-indigo-600 bg-indigo-50 px-4 py-2 rounded-lg border border-indigo-100">
            {{ now()->translatedFormat('l, d F Y') }}
        </div>
    </div>

    <!-- Standard Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Orders -->
        <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <div class="p-2 bg-indigo-50 rounded-lg text-indigo-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 11-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                </div>
                <span class="text-xs font-bold text-emerald-600 bg-emerald-50 px-2 py-1 rounded">Aktif</span>
            </div>
            <p class="text-sm font-medium text-slate-500 uppercase tracking-wider">Total Pesanan</p>
            <h3 class="text-3xl font-bold text-slate-900 mt-1">{{ $this->totalPesanan }}</h3>
        </div>

        <!-- Pending Orders -->
        <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <div class="p-2 bg-amber-50 rounded-lg text-amber-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <span class="text-xs font-bold text-amber-600 bg-amber-50 px-2 py-1 rounded">Proses</span>
            </div>
            <p class="text-sm font-medium text-slate-500 uppercase tracking-wider">Menunggu Kirim</p>
            <h3 class="text-3xl font-bold text-slate-900 mt-1">{{ $this->pesananTertunda }}</h3>
        </div>

        <!-- Products -->
        <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <div class="p-2 bg-purple-50 rounded-lg text-purple-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                </div>
            </div>
            <p class="text-sm font-medium text-slate-500 uppercase tracking-wider">Katalog Produk</p>
            <h3 class="text-3xl font-bold text-slate-900 mt-1">{{ $this->totalEtalase }}</h3>
        </div>

        <!-- Users -->
        <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <div class="p-2 bg-emerald-50 rounded-lg text-emerald-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                </div>
            </div>
            <p class="text-sm font-medium text-slate-500 uppercase tracking-wider">Total Pengguna</p>
            <h3 class="text-3xl font-bold text-slate-900 mt-1">{{ $this->totalUser }}</h3>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Recent Activities -->
        <div class="lg:col-span-2 bg-white rounded-xl border border-slate-200 shadow-sm">
            <div class="p-4 border-b border-slate-100 flex items-center justify-between">
                <h2 class="font-bold text-slate-800">Aktivitas Terkini</h2>
                <a href="{{ $this->panelPrefix }}/activity" class="text-xs font-semibold text-indigo-600 hover:underline">Lihat Semua</a>
            </div>
            <div class="p-4">
                <div class="flow-root">
                    <ul role="list" class="-mb-8">
                        @forelse($this->recentActivities as $activity)
                            <li>
                                <div class="relative pb-8">
                                    @if (!$loop->last)
                                        <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-slate-200"></span>
                                    @endif
                                    <div class="relative flex space-x-3">
                                        <div>
                                            <span class="h-8 w-8 rounded-full bg-slate-100 flex items-center justify-center ring-8 ring-white">
                                                <span class="text-xs font-bold text-slate-500 uppercase">{{ substr($activity->user->name, 0, 1) }}</span>
                                            </span>
                                        </div>
                                        <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                            <div>
                                                <p class="text-sm text-slate-500"><span class="font-bold text-slate-900 capitalize">{{ $activity->user->name }}</span>: {{ $activity->description ?? $activity->activity }}</p>
                                            </div>
                                            <div class="text-right text-xs whitespace-nowrap text-slate-400 font-medium">
                                                {{ $activity->created_at->diffForHumans() }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        @empty
                            <p class="text-center py-10 text-slate-400 italic">Belum ada aktivitas.</p>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>

        <!-- Quick Status -->
        <div class="space-y-6">
            <div class="bg-indigo-600 rounded-xl p-6 text-white shadow-md">
                <h3 class="font-bold mb-4">Status Sistem</h3>
                <div class="space-y-4">
                    <div>
                        <div class="flex justify-between text-xs mb-1">
                            <span>Kapasitas Armada</span>
                            <span>85%</span>
                        </div>
                        <div class="w-full bg-indigo-700 rounded-full h-2">
                            <div class="bg-white h-2 rounded-full" style="width: 85%"></div>
                        </div>
                    </div>
                    <div>
                        <div class="flex justify-between text-xs mb-1">
                            <span>Efisiensi Operasional</span>
                            <span>92%</span>
                        </div>
                        <div class="w-full bg-indigo-700 rounded-full h-2">
                            <div class="bg-emerald-400 h-2 rounded-full" style="width: 92%"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl border border-slate-200 p-6 shadow-sm">
                <div class="flex items-center space-x-3 text-slate-500 mb-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span class="text-sm font-bold">Waktu Server</span>
                </div>
                <div class="text-2xl font-bold text-slate-900">
                    {{ now()->format('H:i') }} <span class="text-sm font-medium text-slate-400">WIB</span>
                </div>
            </div>
        </div>
    </div>
</div>