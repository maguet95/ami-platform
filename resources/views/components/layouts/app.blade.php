<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ isset($title) ? $title . ' — AMI' : 'AMI — Alpha Markets Institute' }}</title>
    <meta name="description" content="{{ $description ?? 'Alpha Markets Institute — Instituto educativo de trading profesional.' }}">

    <!-- Open Graph -->
    <meta property="og:title" content="{{ isset($title) ? $title . ' — AMI' : 'AMI — Alpha Markets Institute' }}">
    <meta property="og:description" content="{{ $description ?? 'Instituto educativo de trading profesional.' }}">
    <meta property="og:type" content="website">
    <meta property="og:image" content="{{ asset('images/logos/logo-dark.jpg') }}">

    <!-- Favicon -->
    <link rel="icon" href="{{ asset('images/logos/isotipo.jpg') }}" type="image/jpeg">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700,800,900" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="min-h-screen bg-surface-950 text-surface-100 dark:bg-surface-950 dark:text-surface-100
             light:bg-surface-50 light:text-surface-900 antialiased">

    <x-public.navbar />

    <main>
        {{ $slot }}
    </main>

    <x-public.footer />

    @livewireScripts
</body>
</html>
