<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="min-h-screen flex flex-col lg:flex-row justify-center items-center pt-6 sm:pt-0 bg-gradient-to-br from-blue-50 via-blue-100 to-blue-50 px-4 gap-8">
            <!-- Form Card -->
            <div class="w-full sm:max-w-md px-6 py-8 bg-white shadow-2xl overflow-hidden sm:rounded-2xl border-t-4 border-blue-600">
                {{ $slot }}
            </div>

            <!-- Dynamic Credentials Info Panel (Right Side) -->
            @php $currentRole = $role ?? request()->query('role'); @endphp
            @if(request()->routeIs('login*'))
                @if($currentRole === 'admin')
                    <!-- Admin/Staff Panel -->
                    <div class="hidden lg:block w-full sm:max-w-sm px-6 py-8 bg-white shadow-xl sm:rounded-2xl border border-red-100 relative overflow-hidden">
                        <div class="absolute top-0 right-0 -mr-4 -mt-4 w-24 h-24 bg-red-100 rounded-full opacity-50 blur-xl"></div>
                        
                        <h3 class="text-xl font-semibold text-gray-900 mb-6 flex items-center gap-2">
                            <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                            Informasi Login Admin
                        </h3>
                        
                        <div class="space-y-4">
                            <div class="bg-red-50 p-4 rounded-xl border border-red-100 hover:shadow-md transition">
                                <span class="text-xs font-semibold text-red-600 uppercase tracking-wider">Admin Role</span>
                                <div class="mt-2 text-sm text-gray-800 font-mono font-medium">
                                    Username : Admin <br>
                                    Password : BPTrans
                                </div>
                            </div>
                            
                            <div class="bg-gray-50 p-4 rounded-xl border border-gray-100 hover:shadow-md transition">
                                <span class="text-xs font-semibold text-gray-600 uppercase tracking-wider">Owner Role</span>
                                <div class="mt-2 text-sm text-gray-800 font-mono font-medium">
                                    Username : owner <br>
                                    Password : bptrans
                                </div>
                            </div>

                            <div class="bg-gray-50 p-4 rounded-xl border border-gray-100 hover:shadow-md transition">
                                <span class="text-xs font-semibold text-gray-600 uppercase tracking-wider">Worker Role</span>
                                <div class="mt-2 text-sm text-gray-800 font-mono font-medium">
                                    Username : worker <br>
                                    Password : bptrans
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <!-- User Panel (Default) -->
                    <div class="hidden lg:block w-full sm:max-w-sm px-6 py-8 bg-white shadow-xl sm:rounded-2xl border border-blue-100 relative overflow-hidden">
                        <div class="absolute top-0 right-0 -mr-4 -mt-4 w-24 h-24 bg-blue-100 rounded-full opacity-50 blur-xl"></div>
                        
                        <h3 class="text-xl font-semibold text-gray-900 mb-6 flex items-center gap-2">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                            Informasi Login Member
                        </h3>
                        
                        <div class="space-y-4">
                            <div class="bg-blue-50 p-6 rounded-xl border border-blue-100 hover:shadow-md transition">
                                <span class="text-xs font-semibold text-blue-600 uppercase tracking-wider">User Role</span>
                                <div class="mt-4 text-base text-gray-800 font-mono font-medium">
                                    <div class="flex justify-between items-center mb-2">
                                        <span class="text-gray-500">Username :</span>
                                        <span class="text-blue-700 font-bold">wamilo</span>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <span class="text-gray-500">Password :</span>
                                        <span class="text-blue-700 font-bold">wamilo123</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <p class="mt-6 text-xs text-gray-500 text-center italic">
                            Gunakan akun di atas untuk mencoba fitur sebagai Member.
                        </p>
                    </div>
                @endif
            @endif
        </div>

        <!-- Auto reload script for development -->
        @if(app()->environment('local'))
        <script>
            // Auto reload when files change (for development)
            if (typeof EventSource !== 'undefined') {
                const evtSource = new EventSource('http://localhost:5174/');
                evtSource.onmessage = function(event) {
                    if (event.data === 'connected') {
                        console.log('🔄 Auto-reload connected');
                    }
                };
                evtSource.addEventListener('reload', function(event) {
                    console.log('🔄 Reloading page due to file changes...');
                    window.location.reload();
                });
                evtSource.onerror = function() {
                    // Fallback: check for changes every 2 seconds
                    setInterval(() => {
                        fetch(window.location.href, { method: 'HEAD' })
                            .then(response => {
                                if (response.status !== 200) {
                                    window.location.reload();
                                }
                            })
                            .catch(() => {
                                // Ignore errors in development
                            });
                        }, 2000);
                };
            }
        </script>
        @endif
    </body>
</html>
