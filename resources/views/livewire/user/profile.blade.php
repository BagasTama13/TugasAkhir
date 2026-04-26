<div>
    <div class="user-card animate-fade-in-up">
        <div class="p-6 sm:p-8">
            <!-- Edit Button (Mobile Top / Desktop Hidden) -->
            <div class="flex justify-end lg:hidden mb-6">
                <a href="{{ route('user.profile.edit') }}" class="bg-blue-50 text-blue-600 px-4 py-2 rounded-xl text-xs font-bold uppercase">Edit Profil</a>
            </div>

            <!-- Profile Content -->
            <div class="flex flex-col lg:flex-row gap-12 items-center lg:items-start pt-4">
                <!-- Avatar Section -->
                <div class="flex flex-col items-center gap-6">
                    <div class="w-48 h-48 rounded-[3rem] overflow-hidden shadow-2xl ring-8 ring-gray-50 bg-white relative group">
                        @if ($user->avatar)
                            <img src="{{ asset('storage/' . $user->avatar) }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                        @else
                            <div class="w-full h-full bg-gradient-to-br from-blue-600 to-indigo-700 flex items-center justify-center text-white text-6xl font-black">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                        @endif
                        <div class="absolute inset-0 bg-black/5 group-hover:bg-transparent transition-colors"></div>
                    </div>
                    
                    <div class="text-center">
                        <h2 class="text-2xl font-black text-gray-900 leading-tight">{{ $user->name }}</h2>
                        <p class="text-blue-600 font-bold text-xs uppercase tracking-widest mt-2">Member Aktif BPTrans</p>
                    </div>

                    <a href="{{ route('user.profile.edit') }}"
                       class="hidden lg:flex items-center gap-2 bg-white border-2 border-gray-100 px-6 py-2.5 rounded-2xl text-sm font-bold text-gray-600 hover:border-blue-600 hover:text-blue-600 hover:shadow-xl hover:shadow-blue-50 transition-all group">
                        <svg class="w-4 h-4 text-gray-400 group-hover:text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                        </svg>
                        Edit Profil
                    </a>
                </div>

                <!-- Info Grid -->
                <div class="flex-1 w-full grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Nama Lengkap -->
                    <div class="bg-white rounded-[2rem] p-6 border border-gray-100 shadow-sm hover:shadow-md transition-shadow group">
                        <div class="flex items-center gap-3 mb-3">
                            <div class="w-8 h-8 bg-blue-50 rounded-lg flex items-center justify-center text-blue-600 group-hover:scale-110 transition-transform">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                            </div>
                            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">Nama Lengkap</p>
                        </div>
                        <p class="text-gray-900 font-black text-lg ml-1">{{ $user->name }}</p>
                    </div>

                    <!-- Email -->
                    <div class="bg-white rounded-[2rem] p-6 border border-gray-100 shadow-sm hover:shadow-md transition-shadow group">
                        <div class="flex items-center gap-3 mb-3">
                            <div class="w-8 h-8 bg-purple-50 rounded-lg flex items-center justify-center text-purple-600 group-hover:scale-110 transition-transform">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">Email Address</p>
                        </div>
                        <p class="text-gray-900 font-black text-lg ml-1">{{ $user->email }}</p>
                    </div>

                    <!-- No HP -->
                    <div class="bg-white rounded-[2rem] p-6 border border-gray-100 shadow-sm hover:shadow-md transition-shadow group">
                        <div class="flex items-center gap-3 mb-3">
                            <div class="w-8 h-8 bg-emerald-50 rounded-lg flex items-center justify-center text-emerald-600 group-hover:scale-110 transition-transform">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                </svg>
                            </div>
                            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">Nomor WhatsApp</p>
                        </div>
                        <p class="text-gray-900 font-black text-lg ml-1">{{ $user->no_hp ?? '-' }}</p>
                    </div>

                    <!-- Alamat -->
                    <div class="bg-white rounded-[2rem] p-6 border border-gray-100 shadow-sm hover:shadow-md transition-shadow group md:col-span-2">
                        <div class="flex items-center gap-3 mb-3">
                            <div class="w-8 h-8 bg-orange-50 rounded-lg flex items-center justify-center text-orange-600 group-hover:scale-110 transition-transform">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                            </div>
                            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">Alamat Lengkap</p>
                        </div>
                        <p class="text-gray-900 font-bold text-base leading-relaxed ml-1">{{ $user->alamat ?? 'Belum mengatur alamat' }}</p>
                    </div>
                </div>
            </div>

            <!-- Logout -->
            <div class="mt-12 flex justify-center">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                            class="px-10 py-4 bg-gray-900 text-white font-bold rounded-2xl hover:bg-red-600 transition-all duration-300 shadow-xl shadow-gray-200 flex items-center gap-2 group">
                        <svg class="w-5 h-5 group-hover:rotate-12 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                        Logout Session
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
