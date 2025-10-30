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
</head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-sky-500 via-indigo-600 to-purple-700">
            <div class="container mx-auto px-4">
                <div class="max-w-4xl mx-auto bg-white/90 backdrop-blur-md rounded-2xl shadow-2xl overflow-hidden grid grid-cols-1 md:grid-cols-2">
                    <!-- Left: Illustration / brand -->
                    <div class="hidden md:flex items-center justify-center p-8 bg-gradient-to-b from-indigo-700 to-purple-800 text-white">
                        <div class="text-center">
                            <h2 class="text-3xl font-extrabold mb-4">Admin Portal</h2>
                            <p class="opacity-90">management tools and analytics at your fingertips.</p>
                                <!-- DNHS logo below admin portal (circular) -->
                                <div class="mx-auto mt-6 w-32 h-32 rounded-full overflow-hidden bg-white p-1 flex items-center justify-center">
                                    <img src="{{ asset('images/dnhs_logo.jpg') }}" alt="DNHS logo" class="w-full h-full object-cover" />
                                </div>
                        </div>
                    </div>

                    <!-- Right: slot (forms) -->
                    <div class="p-8 md:p-10">
                        {{ $slot }}
                    </div>
                </div>
                <p class="text-center text-sm text-white/90 mt-6">&copy; {{ date('Y') }} TuroTugma</p>
            </div>
        </div>
    </body>
</html>
