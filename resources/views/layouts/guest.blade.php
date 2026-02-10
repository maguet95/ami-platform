<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'AMI') }}</title>
    <link rel="icon" href="{{ asset('images/logos/isotipo.jpg') }}" type="image/jpeg">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700,800" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-surface-950 text-surface-100 antialiased">
    <div class="min-h-screen flex flex-col items-center justify-center px-4 py-12 relative">
        {{-- Background --}}
        <div class="absolute inset-0 opacity-[0.02]"
             style="background-image: linear-gradient(rgba(41,98,255,.3) 1px, transparent 1px), linear-gradient(90deg, rgba(41,98,255,.3) 1px, transparent 1px); background-size: 60px 60px;">
        </div>
        <div class="absolute top-1/4 -left-32 w-96 h-96 bg-ami-500/5 rounded-full blur-[128px]"></div>
        <div class="absolute bottom-1/4 -right-32 w-96 h-96 bg-ami-700/5 rounded-full blur-[128px]"></div>

        {{-- Logo --}}
        <div class="relative mb-8">
            <a href="{{ route('home') }}">
                <img src="{{ asset('images/logos/logo-dark.jpg') }}" alt="AMI" class="h-12 rounded dark:block hidden">
                <img src="{{ asset('images/logos/logo-light.jpg') }}" alt="AMI" class="h-12 rounded dark:hidden block">
            </a>
        </div>

        {{-- Card --}}
        <div class="relative w-full max-w-md">
            <div class="bg-surface-900/80 backdrop-blur-sm border border-surface-700/50 rounded-2xl p-8 shadow-2xl shadow-black/20">
                {{ $slot }}
            </div>
        </div>

        {{-- Back to home --}}
        <div class="relative mt-6">
            <a href="{{ route('home') }}" class="text-sm text-surface-500 hover:text-ami-400 transition-colors">
                &larr; Volver al inicio
            </a>
        </div>
    </div>
</body>
</html>
