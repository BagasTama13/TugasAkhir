<div class="min-h-screen bg-[#F8FAFC] pb-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 mt-12">
        <div class="bg-white rounded-[3rem] shadow-xl border border-slate-200 overflow-hidden animate-in fade-in slide-in-from-bottom-6 duration-700">
            <!-- Header/Cover -->
            <div class="h-48 bg-gradient-to-r from-slate-900 to-indigo-900 relative">
                <div class="absolute -bottom-16 left-12">
                    <div class="h-32 w-32 rounded-3xl bg-white p-2 shadow-2xl">
                        <div class="h-full w-full rounded-2xl bg-slate-100 flex items-center justify-center text-3xl font-display font-black text-slate-400 border border-slate-100">
                            {{ substr(auth()->user()->name, 0, 2) }}
                        </div>
                    </div>
                </div>
                <div class="absolute bottom-4 right-8">
                    <a href="{{ route('user.profile.edit') }}" class="inline-flex items-center px-6 py-2.5 bg-white/10 backdrop-blur-md text-white text-xs font-bold rounded-xl hover:bg-white/20 transition-all border border-white/20 uppercase tracking-widest">
                        Edit Profil
                    </a>
                </div>
            </div>

            <!-- Content -->
            <div class="pt-20 px-12 pb-12">
                <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 pb-8 border-b border-slate-100">
                    <div>
                        <h1 class="text-3xl font-display font-black text-slate-900">{{ auth()->user()->name }}</h1>
                        <p class="text-sm text-slate-500 font-medium mt-1">Pelanggan Setia BPTrans Logistik</p>
                    </div>
                    <div class="flex gap-3">
                        <div class="px-4 py-2 bg-emerald-50 rounded-xl border border-emerald-100 flex items-center gap-2">
                            <span class="h-2 w-2 rounded-full bg-emerald-500 animate-pulse"></span>
                            <span class="text-[10px] font-bold text-emerald-600 uppercase tracking-wider">Akun Aktif</span>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-12 mt-10">
                    <!-- Basic Information -->
                    <div class="space-y-8">
                        <div>
                            <h3 class="text-xs font-bold text-slate-400 uppercase tracking-[0.2em] mb-6 flex items-center gap-2">
                                <span class="h-1 w-6 bg-indigo-600 rounded-full"></span>
                                Informasi Dasar
                            </h3>
                            <div class="space-y-6">
                                <div class="flex items-center gap-4 group">
                                    <div class="h-10 w-10 rounded-xl bg-slate-50 flex items-center justify-center text-slate-400 group-hover:bg-indigo-50 group-hover:text-indigo-600 transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                    </div>
                                    <div>
                                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Username</p>
                                        <p class="text-sm font-bold text-slate-700 italic">@ {{ auth()->user()->username }}</p>
                                    </div>
                                </div>

                                <div class="flex items-center gap-4 group">
                                    <div class="h-10 w-10 rounded-xl bg-slate-50 flex items-center justify-center text-slate-400 group-hover:bg-indigo-50 group-hover:text-indigo-600 transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                    </div>
                                    <div>
                                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Alamat Email</p>
                                        <p class="text-sm font-bold text-slate-700">{{ auth()->user()->email ?? 'Tidak ada email' }}</p>
                                    </div>
                                </div>
                                
                                <div class="flex items-center gap-4 group">
                                    <div class="h-10 w-10 rounded-xl bg-slate-50 flex items-center justify-center text-slate-400 group-hover:bg-indigo-50 group-hover:text-indigo-600 transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Alamat Domisili</p>
                                        <p class="text-sm font-bold text-slate-700">{{ auth()->user()->alamat ?? 'Belum diatur' }}</p>
                                        @if(auth()->user()->gmaps_link)
                                            <a href="{{ auth()->user()->gmaps_link }}" target="_blank" class="text-[10px] font-bold text-indigo-600 hover:text-indigo-700 mt-1 inline-block">Buka di Google Maps &rarr;</a>
                                        @elseif(auth()->user()->latitude && auth()->user()->longitude)
                                            <a href="https://www.google.com/maps?q={{ auth()->user()->latitude }},{{ auth()->user()->longitude }}" target="_blank" class="text-[10px] font-bold text-indigo-600 hover:text-indigo-700 mt-1 inline-block">Lihat Titik Peta &rarr;</a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Additional Details -->
                    <div class="space-y-8">
                        <div>
                            <h3 class="text-xs font-bold text-slate-400 uppercase tracking-[0.2em] mb-6 flex items-center gap-2">
                                <span class="h-1 w-6 bg-indigo-600 rounded-full"></span>
                                Detail Kontak
                            </h3>
                            <div class="space-y-6">
                                <div class="flex items-center gap-4 group">
                                    <div class="h-10 w-10 rounded-xl bg-slate-50 flex items-center justify-center text-slate-400 group-hover:bg-indigo-50 group-hover:text-indigo-600 transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 002-2H4a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                                    </div>
                                    <div>
                                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">No. WhatsApp</p>
                                        <p class="text-sm font-bold text-slate-700">{{ auth()->user()->no_hp ?? '-' }}</p>
                                    </div>
                                </div>

                                <div class="flex items-center gap-4 group">
                                    <div class="h-10 w-10 rounded-xl bg-slate-50 flex items-center justify-center text-slate-400 group-hover:bg-indigo-50 group-hover:text-indigo-600 transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    </div>
                                    <div>
                                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Bergabung Pada</p>
                                        <p class="text-sm font-bold text-slate-700">{{ auth()->user()->created_at->format('d F Y') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Footer Summary -->
                <div class="mt-12 pt-8 border-t border-slate-100 flex flex-col md:flex-row gap-6 justify-between items-center bg-slate-50/50 -mx-12 px-12 pb-12 rounded-b-[3rem]">
                    <div class="flex items-center gap-4">
                        <div class="h-12 w-12 rounded-2xl bg-white shadow-sm flex items-center justify-center text-indigo-600 border border-slate-200">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Total Pesanan</p>
                            <p class="text-lg font-black text-slate-900">{{ \App\Models\Pesanan::where('user_id', auth()->id())->count() }} <span class="text-xs font-bold text-slate-400 uppercase ml-1">Items</span></p>
                        </div>
                    </div>
                    
                    <a href="{{ route('user.pesanan') }}" class="inline-flex items-center px-8 py-3 bg-slate-900 text-white text-sm font-bold rounded-2xl hover:bg-indigo-600 transition-all duration-300 shadow-xl shadow-slate-200">
                        Lihat Riwayat Pesanan
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
