<div class="min-h-screen bg-[#F8FAFC] pb-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 mt-12">
        <div class="bg-white rounded-[3rem] shadow-xl border border-slate-200 overflow-hidden animate-in fade-in slide-in-from-bottom-6 duration-700">
            <!-- Top Navigation -->
            <div class="px-10 py-6 border-b border-slate-100 flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <a href="{{ route('user.profile') }}" class="h-10 w-10 rounded-xl bg-slate-50 flex items-center justify-center text-slate-400 hover:bg-slate-900 hover:text-white transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"></path></svg>
                    </a>
                    <h1 class="text-xl font-display font-black text-slate-900 uppercase tracking-tight">Pengaturan Profil</h1>
                </div>
                <div class="h-2 w-2 rounded-full bg-indigo-600"></div>
            </div>

            <div class="p-10 lg:p-14">
                <form wire:submit.prevent="updateProfile" class="space-y-10">
                    <!-- Profile Photo Selection -->
                    <div class="flex flex-col md:flex-row items-center gap-10 p-8 bg-slate-50 rounded-[2.5rem] border border-slate-100/50">
                        <div class="relative group">
                            <div class="h-32 w-32 rounded-[2rem] overflow-hidden shadow-2xl ring-4 ring-white relative bg-white">
                                @if ($photo)
                                    <img src="{{ $photo->temporaryUrl() }}" class="w-full h-full object-cover">
                                @elseif ($currentAvatar)
                                    <img src="{{ asset('storage/' . $currentAvatar) }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full bg-slate-200 flex items-center justify-center text-slate-400 text-3xl font-black">
                                        {{ strtoupper(substr($name, 0, 2)) }}
                                    </div>
                                @endif
                                
                                <div wire:loading wire:target="photo" class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm flex items-center justify-center">
                                    <div class="w-6 h-6 border-2 border-white border-t-transparent rounded-full animate-spin"></div>
                                </div>
                            </div>
                            
                            <label class="absolute -bottom-2 -right-2 h-10 w-10 bg-indigo-600 text-white flex items-center justify-center rounded-xl shadow-xl cursor-pointer hover:bg-slate-900 hover:scale-110 transition-all border-4 border-white">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                <input type="file" wire:model="photo" class="hidden" accept="image/*">
                            </label>
                        </div>
                        
                        <div class="flex-1 text-center md:text-left space-y-2">
                            <h3 class="text-xl font-bold text-slate-900">Ubah Foto Profil</h3>
                            <p class="text-xs text-slate-500 font-medium leading-relaxed">Gunakan foto asli Anda agar admin dapat mengenali Anda lebih mudah saat pengiriman material.</p>
                            <p class="text-[10px] font-bold text-slate-400 uppercase mt-4 tracking-[0.2em]">Format: JPG, PNG (Maks 2MB)</p>
                            @error('photo') <p class="text-[10px] text-rose-500 font-bold uppercase mt-2">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <!-- Input Fields Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="space-y-2">
                            <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest ml-1">Nama Lengkap</label>
                            <input type="text" wire:model="name" class="w-full px-5 py-4 rounded-2xl border border-slate-200 focus:ring-2 focus:ring-indigo-500 outline-none transition-all font-medium text-slate-700 bg-slate-50 focus:bg-white" placeholder="Nama Anda">
                            @error('name') <p class="text-[10px] text-rose-500 font-bold uppercase mt-1 ml-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="space-y-2">
                            <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest ml-1">Alamat Email</label>
                            <input type="email" wire:model="email" class="w-full px-5 py-4 rounded-2xl border border-slate-200 focus:ring-2 focus:ring-indigo-500 outline-none transition-all font-medium text-slate-700 bg-slate-50 focus:bg-white" placeholder="email@example.com">
                            @error('email') <p class="text-[10px] text-rose-500 font-bold uppercase mt-1 ml-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="space-y-2">
                            <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest ml-1">Nomor WhatsApp</label>
                            <div class="relative">
                                <span class="absolute left-5 top-1/2 -translate-y-1/2 text-slate-400 font-bold">+62</span>
                                <input type="text" wire:model="no_hp" class="w-full pl-14 pr-5 py-4 rounded-2xl border border-slate-200 focus:ring-2 focus:ring-indigo-500 outline-none transition-all font-bold text-slate-900 bg-slate-50 focus:bg-white" placeholder="8xxxxxxxxxx">
                            </div>
                            @error('no_hp') <p class="text-[10px] text-rose-500 font-bold uppercase mt-1 ml-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest ml-1">Alamat Domisili</label>
                        <textarea wire:model="alamat" rows="3" class="w-full px-5 py-4 rounded-2xl border border-slate-200 focus:ring-2 focus:ring-indigo-500 outline-none transition-all font-medium text-slate-700 bg-slate-50 focus:bg-white resize-none" placeholder="Tulis alamat lengkap Anda..."></textarea>
                        @error('alamat') <p class="text-[10px] text-rose-500 font-bold uppercase mt-1 ml-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Action Buttons -->
                    <div class="pt-10 flex flex-col sm:flex-row gap-4 border-t border-slate-100">
                        <button type="submit" class="flex-1 bg-slate-900 text-white font-bold text-sm py-4 rounded-2xl shadow-xl shadow-slate-200 hover:bg-indigo-600 transition-all duration-300">
                            Simpan Perubahan
                        </button>
                        <a href="{{ route('user.profile') }}" class="flex-1 bg-slate-100 text-slate-600 font-bold text-sm py-4 rounded-2xl text-center hover:bg-slate-200 transition-all duration-300">
                            Batal & Kembali
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
