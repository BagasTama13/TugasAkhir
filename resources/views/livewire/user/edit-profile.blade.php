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
                <!-- Avatar Preview -->
                <div class="flex items-center gap-4 mb-2">
                    <div class="w-20 h-20 rounded-full bg-gradient-to-br from-emerald-400 to-teal-500 flex items-center justify-center shadow-lg">
                        <span class="text-2xl font-extrabold text-white">
                            {{ strtoupper(substr($name, 0, 1)) }}
                        </span>
                    </div>
                    <div>
                        <p class="font-bold text-gray-800 text-lg">{{ $name }}</p>
                        <p class="text-sm text-gray-400">Edit informasi profil Anda</p>
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
