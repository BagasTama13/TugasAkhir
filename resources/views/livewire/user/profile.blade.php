<div>
    <div class="user-card animate-fade-in-up">
        <div class="p-6 sm:p-8">
            <!-- Edit Button -->
            <div class="flex justify-end mb-4">
                <a href="{{ route('user.profile.edit') }}"
                   class="p-2 rounded-xl hover:bg-gray-100 transition-colors duration-200 group"
                   title="Edit Profile">
                    <svg class="w-6 h-6 text-gray-400 group-hover:text-emerald-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                </a>
            </div>

            <!-- Profile Content -->
            <div class="flex flex-col md:flex-row gap-8 md:gap-12 items-center md:items-start">
                <!-- Avatar + Name -->
                <div class="flex flex-col items-center gap-4">
                    <div class="w-36 h-36 rounded-full bg-gradient-to-br from-emerald-400 to-teal-500 flex items-center justify-center shadow-xl shadow-emerald-100">
                        <span class="text-5xl font-extrabold text-white">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </span>
                    </div>
                    <div class="bg-gray-100 px-6 py-2.5 rounded-xl text-center">
                        <p class="font-bold text-gray-800 text-lg">{{ $user->name }}</p>
                    </div>
                </div>

                <!-- Info Card -->
                <div class="flex-1 w-full">
                    <div class="bg-gradient-to-br from-gray-50 to-gray-100 rounded-2xl p-6 space-y-4">
                        <!-- Nama Lengkap -->
                        <div class="bg-white rounded-xl p-4 shadow-sm">
                            <p class="text-xs text-gray-400 font-medium mb-1">Nama Lengkap</p>
                            <p class="text-gray-800 font-semibold text-lg">{{ $user->name }}</p>
                        </div>

                        <!-- Alamat -->
                        <div class="bg-white rounded-xl p-4 shadow-sm">
                            <p class="text-xs text-gray-400 font-medium mb-1">Alamat</p>
                            <p class="text-gray-800 font-semibold text-lg">{{ $user->alamat ?? '-' }}</p>
                        </div>

                        <!-- NO HP -->
                        <div class="bg-white rounded-xl p-4 shadow-sm">
                            <p class="text-xs text-gray-400 font-medium mb-1">No HP</p>
                            <p class="text-gray-800 font-semibold text-lg">{{ $user->no_hp ?? '-' }}</p>
                        </div>

                        <!-- Email -->
                        <div class="bg-white rounded-xl p-4 shadow-sm">
                            <p class="text-xs text-gray-400 font-medium mb-1">Email</p>
                            <p class="text-gray-800 font-semibold text-lg">{{ $user->email }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Logout -->
            <div class="mt-8 flex justify-center">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                            class="px-8 py-3 bg-gradient-to-r from-red-500 to-red-600 text-white font-bold rounded-xl
                                   hover:from-red-600 hover:to-red-700 transition-all duration-300
                                   shadow-lg shadow-red-100 hover:shadow-red-200 hover:-translate-y-0.5">
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
