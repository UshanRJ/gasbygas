<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="mobile-web-app-capable" content="yes">

    <title>{{ $title ?? 'GasByGas' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles

    <!-- PWA Meta Tags -->
    <meta name="theme-color" content="#000000">
    <meta name="description" content="Your app description">
    <link rel="manifest" href="{{ asset('manifest.json') }}">

    <!-- iOS Support -->
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="apple-mobile-web-app-title" content="{{ config('app.name', 'Laravel') }}">
    <link rel="apple-touch-icon" href="/images/icons/icon-152x152.png">

    <!-- PWA Script -->
    <script src="{{ asset('js/pwa.js') }}" defer></script>
</head>

<body class="bg-slate-200 dark:bg-slate-700">
    @livewire('partials.navbar')
    <main>
        {{ $slot }}
    </main>
    @livewire('partials.footer')
    @livewireScripts

    <button id="pwa-install-button"
        style="display: none; position: fixed; bottom: 20px; right: 20px; background-color: #3490dc; color: white; border: none; border-radius: 5px; padding: 10px 15px; font-weight: bold; box-shadow: 0 2px 5px rgba(0,0,0,0.2); z-index: 9999;">
        Add to Home Screen
    </button>
    <!-- IndexedDB Promises library -->
    <script src="https://cdn.jsdelivr.net/npm/idb@7/build/umd.js"></script>
    <script src="{{ asset('js/db.js') }}" defer></script>

    <script>
        // Debug PWA installation
        window.addEventListener('beforeinstallprompt', (e) => {
            console.log('Install prompt triggered!');
        });

        // Check if service worker is supported
        if ('serviceWorker' in navigator) {
            console.log('Service worker is supported');
        } else {
            console.log('Service worker is NOT supported');
        }
    </script>
</body>

</html>