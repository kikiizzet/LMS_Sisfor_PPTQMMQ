<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>
        <link rel="icon" type="image/png" href="{{ asset('images/logoo.png') }}">
        <meta name="description" content="Sistem Informasi Laporan Hasil Belajar (Rapor) Santri Pondok Pesantren Tahfidzul Qur'an Makkah Madinatul Qur'an Pacitan.">

        <!-- Open Graph / Facebook -->
        <meta property="og:type" content="website">
        <meta property="og:url" content="{{ url()->current() }}">
        <meta property="og:title" content="{{ config('app.name', 'Laravel') }}">
        <meta property="og:description" content="Sistem Informasi Laporan Hasil Belajar (Rapor) Santri Pondok Pesantren Tahfidzul Qur'an Makkah Madinatul Qur'an Pacitan.">
        <meta property="og:image" content="{{ asset('images/logoo.png') }}">
        <meta property="og:site_name" content="MMQ Digital">

        <!-- Twitter -->
        <meta property="twitter:card" content="summary">
        <meta property="twitter:url" content="{{ url()->current() }}">
        <meta property="twitter:title" content="{{ config('app.name', 'Laravel') }}">
        <meta property="twitter:description" content="Sistem Informasi Laporan Hasil Belajar (Rapor) Santri Pondok Pesantren Tahfidzul Qur'an Makkah Madinatul Qur'an Pacitan.">
        <meta property="twitter:image" content="{{ asset('images/logoo.png') }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100">
            <div>
                <a href="/">
                    <x-application-logo class="w-20 h-20 fill-current text-gray-500" />
                </a>
            </div>

            <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
