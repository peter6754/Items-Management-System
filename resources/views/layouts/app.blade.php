<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Items Management')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100">
    <div class="min-h-screen">
        <nav class="bg-white shadow-lg">
            <div class="max-w-7xl mx-auto px-4">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <a href="{{ route('items.index') }}" class="text-xl font-bold text-gray-800">
                            Items Management
                        </a>
                    </div>
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('items.index') }}" 
                           class="text-gray-600 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('items.*') ? 'text-blue-600' : '' }}">
                            Items
                        </a>
                        <a href="{{ route('settings.index') }}" 
                           class="text-gray-600 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('settings.*') ? 'text-blue-600' : '' }}">
                            Settings
                        </a>
                    </div>
                </div>
            </div>
        </nav>

        <main class="max-w-7xl mx-auto py-6 px-4">
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    {{ session('error') }}
                </div>
            @endif

            @if(session('warning'))
                <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded mb-4">
                    {{ session('warning') }}
                </div>
            @endif

            @yield('content')
        </main>
    </div>
</body>
</html>