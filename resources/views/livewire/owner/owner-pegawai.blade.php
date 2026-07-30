<div>
    <div class="mb-8 flex flex-col md:flex-row md:items-end justify-between gap-4">
        <div>
            <h1 class="text-3xl font-black text-slate-800 tracking-tight">Manajemen Pegawai</h1>
            <p class="text-sm font-semibold text-slate-500 mt-1 flex items-center gap-2">
                Kelola data pekerja dan admin sistem
                @if($viewState === 'list')
                    <span class="text-slate-300">/</span>
                    <span class="text-indigo-600 uppercase tracking-widest text-xs font-bold">{{ $selectedRole === 'admin' ? 'Administrator' : 'Worker (Pekerja)' }}</span>
                @endif
            </p>
        </div>
        <div class="flex gap-3">
            @if($viewState === 'list')
                <button wire:click="backToCategory" class="px-5 py-2.5 bg-white text-slate-700 text-sm font-bold rounded-xl border border-slate-200 hover:bg-slate-50 hover:border-slate-300 transition-all shadow-sm flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Kembali
                </button>
            @endif
            <button wire:click="openFormModal" class="px-5 py-2.5 bg-indigo-600 text-white text-sm font-bold rounded-xl hover:bg-indigo-700 hover:shadow-lg hover:shadow-indigo-600/30 transition-all active:scale-95 flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Input Data Pegawai
            </button>
        </div>
    </div>

    @if (session()->has('success'))
        <div class="mb-6 p-4 rounded-2xl bg-emerald-50 border border-emerald-100 flex items-center gap-3 animate-in fade-in slide-in-from-top-4">
            <div class="h-8 w-8 rounded-full bg-emerald-500 flex items-center justify-center flex-shrink-0 shadow-lg shadow-emerald-500/20">
                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
            </div>
            <p class="text-sm font-bold text-emerald-800">{{ session('success') }}</p>
        </div>
    @endif

    @if($viewState === 'category')
        <!-- KATEGORI VIEW -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 animate-in fade-in zoom-in-95 duration-300">
            <!-- Kategori Worker -->
            <div wire:click="selectCategory('worker')" class="group cursor-pointer bg-white rounded-3xl p-8 border border-slate-200 hover:border-indigo-300 hover:shadow-2xl hover:shadow-indigo-600/10 transition-all duration-300 relative overflow-hidden">
                <div class="absolute top-0 right-0 -mr-8 -mt-8 w-40 h-40 bg-indigo-50 rounded-full group-hover:scale-150 transition-transform duration-700 ease-out z-0"></div>
                <div class="relative z-10 flex items-start gap-6">
                    <div class="h-16 w-16 bg-indigo-600 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-indigo-600/30 group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                    </div>
                    <div>
                        <h2 class="text-2xl font-black text-slate-800 tracking-tight group-hover:text-indigo-600 transition-colors">Worker (Pekerja)</h2>
                        <p class="text-sm font-medium text-slate-500 mt-2 line-clamp-2">Kelola data sopir, tenaga bongkar muat, dan staf lapangan lainnya yang menangani pengiriman dan operasional harian.</p>
                        <div class="mt-4 flex items-center text-indigo-600 text-sm font-bold">
                            Lihat Daftar Pekerja
                            <svg class="w-4 h-4 ml-1 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Kategori Admin -->
            <div wire:click="selectCategory('admin')" class="group cursor-pointer bg-white rounded-3xl p-8 border border-slate-200 hover:border-sky-300 hover:shadow-2xl hover:shadow-sky-600/10 transition-all duration-300 relative overflow-hidden">
                <div class="absolute top-0 right-0 -mr-8 -mt-8 w-40 h-40 bg-sky-50 rounded-full group-hover:scale-150 transition-transform duration-700 ease-out z-0"></div>
                <div class="relative z-10 flex items-start gap-6">
                    <div class="h-16 w-16 bg-sky-500 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-sky-500/30 group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                    </div>
                    <div>
                        <h2 class="text-2xl font-black text-slate-800 tracking-tight group-hover:text-sky-600 transition-colors">Administrator</h2>
                        <p class="text-sm font-medium text-slate-500 mt-2 line-clamp-2">Kelola akun staf admin yang bertugas melayani pesanan pelanggan, memverifikasi pembayaran, dan mengelola stok.</p>
                        <div class="mt-4 flex items-center text-sky-600 text-sm font-bold">
                            Lihat Daftar Admin
                            <svg class="w-4 h-4 ml-1 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if($viewState === 'list')
        <!-- LIST VIEW -->
        <div class="bg-white rounded-3xl border border-slate-200 overflow-hidden shadow-sm animate-in fade-in slide-in-from-bottom-4 duration-500">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm whitespace-nowrap">
                    <thead class="bg-slate-50/50 border-b border-slate-200">
                        <tr>
                            <th class="px-6 py-4 font-bold text-[11px] text-slate-400 uppercase tracking-widest w-16 text-center">No</th>
                            <th class="px-6 py-4 font-bold text-[11px] text-slate-400 uppercase tracking-widest">Nama Lengkap</th>
                            <th class="px-6 py-4 font-bold text-[11px] text-slate-400 uppercase tracking-widest">Jabatan</th>
                            <th class="px-6 py-4 font-bold text-[11px] text-slate-400 uppercase tracking-widest">No. Telepon</th>
                            <th class="px-6 py-4 font-bold text-[11px] text-slate-400 uppercase tracking-widest">Email</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($this->pegawais as $index => $pegawai)
                            <tr wire:click="showDetail({{ $pegawai->id }})" class="hover:bg-slate-50/80 cursor-pointer transition-colors group">
                                <td class="px-6 py-4 text-center">
                                    <span class="inline-flex items-center justify-center h-7 w-7 rounded-lg bg-slate-100 text-slate-500 font-bold text-xs group-hover:bg-indigo-100 group-hover:text-indigo-600 transition-colors">{{ $index + 1 }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="h-10 w-10 rounded-xl bg-gradient-to-br from-slate-200 to-slate-300 flex items-center justify-center text-slate-600 font-black shadow-sm group-hover:from-indigo-500 group-hover:to-purple-500 group-hover:text-white transition-all">
                                            {{ substr($pegawai->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <p class="font-black text-slate-800 text-[13px]">{{ $pegawai->name }}</p>
                                            <p class="font-medium text-slate-400 text-[11px] mt-0.5">{{ '@'.$pegawai->username }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-block px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-slate-100 text-slate-600 border border-slate-200">
                                        {{ $pegawai->jabatan ?: ($selectedRole === 'admin' ? 'Staf Admin' : 'Worker') }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 font-medium text-slate-600">{{ $pegawai->no_hp ?? '-' }}</td>
                                <td class="px-6 py-4 font-medium text-slate-500">{{ $pegawai->email }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-16 text-center">
                                    <div class="flex flex-col items-center justify-center text-slate-400">
                                        <div class="h-16 w-16 bg-slate-50 rounded-full flex items-center justify-center mb-4">
                                            <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                        </div>
                                        <p class="text-sm font-bold text-slate-500">Belum ada data pegawai.</p>
                                        <p class="text-xs font-medium mt-1">Silakan klik "Input Data Pegawai" untuk menambahkan.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    <!-- FORM MODAL (Input Data Pegawai) -->
    @if($showFormModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm"
         x-data @keydown.escape.window="$wire.closeFormModal()">
        <div @click.away="$wire.closeFormModal()" class="bg-white rounded-3xl shadow-2xl w-full max-w-3xl overflow-hidden animate-in zoom-in-95 duration-200 max-h-[90vh] flex flex-col">
            <div class="bg-slate-950 px-8 py-5 flex items-center justify-between text-white flex-shrink-0">
                <div>
                    <h3 class="text-xl font-black tracking-tight flex items-center gap-2">
                        <svg class="w-5 h-5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        {{ $pegawaiId ? 'Edit Data Pegawai' : 'Input Data Pegawai' }}
                    </h3>
                </div>
                <button wire:click="closeFormModal" class="p-2 hover:bg-white/10 rounded-xl transition-colors text-slate-400 hover:text-white">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            
            <form wire:submit="simpanPegawai" class="overflow-y-auto p-8 space-y-6 flex-1">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Column 1 -->
                    <div class="space-y-6">
                        <div>
                            <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-2">Role Akses Sistem *</label>
                            <select wire:model.live="role" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-bold text-slate-700 focus:ring-2 focus:ring-indigo-500 outline-none">
                                <option value="worker">Worker (Pekerja Lapangan)</option>
                                <option value="admin">Administrator</option>
                            </select>
                            @error('role') <p class="text-xs text-rose-500 mt-1 font-bold">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-2">Nama Lengkap *</label>
                            <input type="text" wire:model="nama" class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-sm font-bold text-slate-800 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition-all placeholder:font-medium placeholder:text-slate-400" placeholder="Masukkan nama lengkap">
                            @error('nama') <p class="text-xs text-rose-500 mt-1 font-bold">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-2">Username *</label>
                            <input type="text" wire:model="username" class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-sm font-bold text-slate-800 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition-all placeholder:font-medium placeholder:text-slate-400" placeholder="Username untuk login">
                            @error('username') <p class="text-xs text-rose-500 mt-1 font-bold">{{ $message }}</p> @enderror
                        </div>


                    </div>

                    <!-- Column 2 -->
                    <div class="space-y-6">
                        <div>
                            <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-2">Password Awal *</label>
                            <input type="password" wire:model="password" class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-sm font-bold text-slate-800 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition-all placeholder:font-medium placeholder:text-slate-400" placeholder="Minimal 8 karakter">
                            @error('password') <p class="text-xs text-rose-500 mt-1 font-bold">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-2">Jabatan (Opsional)</label>
                            <input type="text" wire:model="jabatan" class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-sm font-bold text-slate-800 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition-all placeholder:font-medium placeholder:text-slate-400" placeholder="Contoh: Supir Utama, Staf Gudang">
                            @error('jabatan') <p class="text-xs text-rose-500 mt-1 font-bold">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-2">Nomor Telepon / WhatsApp</label>
                            <input type="text" wire:model="no_hp" class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-sm font-bold text-slate-800 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition-all placeholder:font-medium placeholder:text-slate-400" placeholder="08xxxxxxxxxx">
                            @error('no_hp') <p class="text-xs text-rose-500 mt-1 font-bold">{{ $message }}</p> @enderror
                        </div>

                        @if($role === 'worker')
                        <div class="animate-in fade-in duration-300">
                            <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-2">Nomor Armada / Kendaraan (Opsional)</label>
                            <input type="text" wire:model="nomor_armada" class="w-full px-4 py-3 bg-amber-50 border border-amber-200 rounded-xl text-sm font-bold text-amber-900 focus:border-amber-500 focus:ring-2 focus:ring-amber-200 outline-none transition-all placeholder:font-medium placeholder:text-amber-400/50" placeholder="Contoh: K 1234 XY">
                            @error('nomor_armada') <p class="text-xs text-rose-500 mt-1 font-bold">{{ $message }}</p> @enderror
                        </div>
                        @endif
                    </div>
                </div>

                <div>
                    <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-2">Alamat Lengkap (Opsional)</label>
                    <textarea wire:model="alamat" rows="2" class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-sm font-bold text-slate-800 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition-all placeholder:font-medium placeholder:text-slate-400" placeholder="Alamat domisili pegawai"></textarea>
                    @error('alamat') <p class="text-xs text-rose-500 mt-1 font-bold">{{ $message }}</p> @enderror
                </div>
                
                <div class="pt-4 flex gap-3 border-t border-slate-100">
                    <button type="button" wire:click="closeFormModal" class="px-6 py-3 text-sm font-bold text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-xl transition-all">Batal</button>
                    <button type="submit" class="flex-1 py-3 text-sm font-bold text-white bg-indigo-600 hover:bg-indigo-700 rounded-xl transition-all shadow-md flex justify-center items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        {{ $pegawaiId ? 'Simpan Perubahan' : 'Simpan Data Pegawai' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif

    <!-- DETAIL MODAL -->
    @if($showDetailModal && $selectedPegawai)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm"
         x-data @keydown.escape.window="$wire.closeDetailModal()">
        <div @click.away="$wire.closeDetailModal()" class="bg-white rounded-3xl shadow-2xl w-full max-w-md overflow-hidden animate-in zoom-in-95 duration-200">
            <div class="bg-indigo-600 px-6 py-5 flex items-center justify-between text-white">
                <div>
                    <h3 class="text-lg font-black tracking-tight">Detail Pegawai</h3>
                </div>
                <button wire:click="closeDetailModal" class="p-2 hover:bg-white/10 rounded-xl transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            
            <div class="p-6">
                <div class="flex items-center gap-4 mb-6 pb-6 border-b border-slate-100">
                    <div class="h-16 w-16 rounded-2xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white text-2xl font-black shadow-lg shadow-indigo-500/20">
                        {{ substr($selectedPegawai->name, 0, 1) }}
                    </div>
                    <div>
                        <h4 class="text-xl font-black text-slate-800">{{ $selectedPegawai->name }}</h4>
                        <span class="inline-block mt-1 px-3 py-1 rounded-md text-[10px] font-bold uppercase tracking-wider bg-slate-100 text-slate-500 border border-slate-200">
                            {{ $selectedPegawai->jabatan ?: ($selectedPegawai->hasRole('admin') ? 'Administrator' : 'Worker') }}
                        </span>
                    </div>
                </div>

                <div class="space-y-4">
                    <div>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Username & Email</p>
                        <p class="text-sm font-bold text-slate-700 mt-1">{{ '@'.$selectedPegawai->username }} <span class="text-slate-300 mx-1">|</span> {{ $selectedPegawai->email }}</p>
                    </div>

                    <div>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Nomor Telepon / WhatsApp</p>
                        <p class="text-sm font-bold text-slate-700 mt-1">{{ $selectedPegawai->no_hp ?? '-' }}</p>
                    </div>

                    <div>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Alamat</p>
                        <p class="text-sm font-bold text-slate-700 mt-1 leading-relaxed">{{ $selectedPegawai->alamat ?? '-' }}</p>
                    </div>

                    @if($selectedPegawai->hasRole('worker') && $selectedPegawai->nomor_armada)
                    <div class="bg-amber-50 p-4 rounded-xl border border-amber-100">
                        <p class="text-[10px] font-bold text-amber-600/70 uppercase tracking-wider">Nomor Armada / Kendaraan</p>
                        <p class="text-lg font-black text-amber-900 mt-1">{{ $selectedPegawai->nomor_armada }}</p>
                    </div>
                    @endif
                </div>

                <div class="mt-8 pt-4 flex gap-3">
                    <button type="button" wire:click="closeDetailModal" class="flex-1 py-3 text-sm font-bold text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-xl transition-all">Tutup Detail</button>
                    <button type="button" wire:click="editPegawai" class="flex-1 py-3 text-sm font-bold text-white bg-indigo-600 hover:bg-indigo-700 rounded-xl transition-all flex justify-center items-center gap-2 shadow-md">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                        Edit Data
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
