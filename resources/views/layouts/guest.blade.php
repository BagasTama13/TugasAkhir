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
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gradient-to-br from-blue-50 via-blue-100 to-blue-50">
            <!-- Form Card -->
            <div class="w-full sm:max-w-md px-6 py-8 bg-white shadow-2xl overflow-hidden sm:rounded-2xl border-t-4 border-blue-600">
                {{ $slot }}
            </div>
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
