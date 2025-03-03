{{-- resources/views/errors/403.blade.php --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ __('Access Denied') }} - {{ config('app.name', 'GasByGas') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased">
    <div class="relative flex items-top justify-center min-h-screen bg-gray-100 sm:items-center sm:pt-0">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
            <div class="flex items-center pt-8 sm:justify-start sm:pt-0">
                <div class="px-4 text-lg text-gray-500 border-r border-gray-400 tracking-wider">
                    403
                </div>

                <div class="ml-4 text-lg text-gray-500 uppercase tracking-wider">
                    Access Denied
                </div>
            </div>

            <div class="mt-8 bg-white overflow-hidden shadow sm:rounded-lg">
                <div class="p-6">
                    <div class="flex">
                        <svg class="h-8 w-8 text-red-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        <div class="ml-3">
                            <h3 class="text-lg font-medium text-gray-900">
                                You don't have permission to access this page
                            </h3>
                            <div class="mt-2 text-gray-600">
                                {{ $exception->getMessage() ?: 'Sorry, you do not have the required permissions to access this resource.' }}
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-6">
                        @auth
                            @if(auth()->user()->isAdmin())
                                <a href="{{ route('filament.admin.pages.dashboard') }}" class="inline-block bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold py-2 px-4 rounded">
                                    Go to Admin Dashboard
                                </a>
                            @elseif(auth()->user()->isPersonalCustomer())
                                <a href="{{ route('personal.dashboard') }}" class="inline-block bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold py-2 px-4 rounded">
                                    Go to Personal Dashboard
                                </a>
                            @elseif(auth()->user()->isBusinessCustomer())
                                <a href="{{ route('business.dashboard') }}" class="inline-block bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold py-2 px-4 rounded">
                                    Go to Business Dashboard
                                </a>
                            @endif
                        @else
                            <a href="{{ route('login') }}" class="inline-block bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold py-2 px-4 rounded">
                                Log in
                            </a>
                        @endauth

                        <a href="{{ route('home') }}" class="ml-2 inline-block bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 px-4 rounded">
                            Return to Home
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>