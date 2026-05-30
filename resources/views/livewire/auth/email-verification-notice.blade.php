<div class="text-center">
    @if($verified)
        <div class="text-green-600 font-semibold mb-4">
            {{ __('Email terverifikasi') }}
        </div>
        <a href="{{ route('login') }}" class="inline-block bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700 transition">
            {{ __('Menuju Login') }}
        </a>
    @else
        <div class="flex items-center justify-center space-x-2 mb-4">
            <svg class="animate-spin h-5 w-5 text-gray-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
            </svg>
            <span>{{ __('Menunggu verifikasi...') }}</span>
        </div>
    @endif
</div>
