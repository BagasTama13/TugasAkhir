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

            <!-- Admin Credentials Info Panel (Right Side) -->
            @if(request()->routeIs('login'))
            <div class="hidden lg:block w-full sm:max-w-sm px-6 py-8 bg-white shadow-xl sm:rounded-2xl border border-blue-100 relative overflow-hidden">
                <div class="absolute top-0 right-0 -mr-4 -mt-4 w-24 h-24 bg-blue-100 rounded-full opacity-50 blur-xl"></div>
                
                <h3 class="text-xl font-semibold text-gray-900 mb-6 flex items-center gap-2">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Informasi Login
                </h3>
                
                <div class="space-y-4">
                    <div class="bg-blue-50 p-4 rounded-xl border border-blue-100 hover:shadow-md transition">
                        <span class="text-xs font-semibold text-blue-600 uppercase tracking-wider">Admin Role</span>
                        <div class="mt-2 text-sm text-gray-800 font-mono font-medium">
                            Username : admin@bptrans.com <br>
                            atau <br>
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
