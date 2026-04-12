<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name') }} – @yield('title', 'Dashboard')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .gradient-bg{background:linear-gradient(135deg,#667eea 0%,#764ba2 100%)}
        /* Make default white cards slightly translucent on the gradient background */
        .gradient-bg .bg-white {
            background-color: rgba(255,255,255,0.85) !important;
            border: 1px solid rgba(255,255,255,0.12);
            backdrop-filter: blur(6px);
        }
        /* Make larger shadows subtler on gradient */
        .gradient-bg .shadow-2xl { box-shadow: 0 10px 25px rgba(8,7,13,0.18) !important; }
    </style>
</head>
<body class="gradient-bg min-h-screen">
    <div class="flex">
        @include('layouts.sidebar')
        <div class="flex-1 max-w-7xl mx-auto px-4 py-6">
            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-800 px-4 py-3 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif
            @yield('content')
        </div>
    </div>
</body>
</html>