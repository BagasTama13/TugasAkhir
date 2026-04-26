<div>
    <div class="user-card animate-fade-in-up">
        <div class="p-6 sm:p-8">
            <div class="flex items-center gap-3 mb-8">
                <a href="{{ route('user.profile') }}" class="p-2 rounded-xl hover:bg-gray-100 transition-colors">
                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                </a>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-800 tracking-tight">Edit Profile</h1>
            </div>

            <form wire:submit.prevent="updateProfile" class="space-y-6 max-w-xl">
                <!-- Profile Photo Upload -->
                <div class="flex flex-col items-center sm:flex-row gap-6 mb-8 p-6 bg-gray-50 rounded-3xl border border-gray-100">
                    <div class="relative group">
                        <div class="w-28 h-28 rounded-full overflow-hidden shadow-2xl ring-4 ring-white relative bg-white">
                            @if ($photo)
                                <img src="{{ $photo->temporaryUrl() }}" class="w-full h-full object-cover">
                            @elseif ($currentAvatar)
                                <img src="{{ asset('storage/' . $currentAvatar) }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full bg-gradient-to-br from-blue-500 to-blue-700 flex items-center justify-center text-white text-3xl font-black">
                                    {{ strtoupper(substr($name, 0, 1)) }}
                                </div>
                            @endif
                            
                            <!-- Loading Overlay -->
                            <div wire:loading wire:target="photo" class="absolute inset-0 bg-gray-900/40 backdrop-blur-sm flex items-center justify-center">
                                <div class="w-6 h-6 border-2 border-white border-t-transparent rounded-full animate-spin"></div>
                            </div>
                        </div>
                        
                        <label class="absolute -bottom-2 -right-2 bg-blue-600 text-white p-2.5 rounded-2xl shadow-xl cursor-pointer hover:bg-blue-700 hover:scale-110 transition-all border-4 border-white">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            <input type="file" wire:model="photo" class="hidden" accept="image/*">
                        </label>
                    </div>
                    
                    <div class="text-center sm:text-left">
                        <h3 class="text-xl font-black text-gray-900">Foto Profil</h3>
                        <p class="text-sm text-gray-500 mt-1">Klik ikon kamera untuk mengunggah foto baru</p>
                        <p class="text-[10px] font-bold text-gray-400 uppercase mt-3 tracking-widest">Max 2MB (JPG, PNG)</p>
                        @error('photo') <span class="text-red-500 text-[10px] font-bold mt-1 block uppercase">{{ $message }}</span> @enderror
                    </div>
                </div>

                <!-- Nama Lengkap -->
                <div>
                    <label class="form-label">Nama Lengkap</label>
                    <input type="text" wire:model="name" class="form-input" placeholder="Nama lengkap">
                    @error('name') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                <!-- Alamat -->
                <div>
                    <label class="form-label">Alamat</label>
                    <textarea wire:model="alamat" class="form-input" rows="3" placeholder="Alamat lengkap"></textarea>
                    @error('alamat') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                <!-- No HP -->
                <div>
                    <label class="form-label">No HP</label>
                    <input type="text" wire:model="no_hp" class="form-input" placeholder="08xxxxxxxxxx">
                    @error('no_hp') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                <!-- Email -->
                <div>
                    <label class="form-label">Email</label>
                    <input type="email" wire:model="email" class="form-input" placeholder="email@example.com">
                    @error('email') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                <!-- Action Buttons -->
                <div class="flex gap-3 pt-2">
                    <button type="submit" class="btn-primary flex-1 text-center">
                        Simpan Perubahan
                    </button>
                    <a href="{{ route('user.profile') }}" class="px-6 py-3 border-2 border-gray-200 text-gray-600 font-semibold rounded-xl hover:bg-gray-50 transition-colors text-center">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
