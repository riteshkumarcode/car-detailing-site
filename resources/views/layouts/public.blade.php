<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'The Drive Clinic — Your Car\'s Healthcare Centre in Jammu')</title>
    <meta name="description" content="@yield('meta_description', 'Premium car wash, detailing studio and digital health check diagnostics in Nanak Nagar, Jammu.')">
    <link rel="canonical" href="{{ url()->current() }}">

    {{-- Favicon & App Icons --}}
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <meta name="theme-color" content="#163F3A">

    {{-- Open Graph / Meta --}}
    <meta property="og:title" content="@yield('title', 'The Drive Clinic — Your Car\'s Healthcare Centre')">
    <meta property="og:description" content="@yield('meta_description', 'Premium car wash and detailing studio launching in Nanak Nagar, Jammu.')">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">

    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Archivo:wdth,wght@62..125,400..900&family=Barlow:ital,wght@0,400;0,500;0,600;0,700;1,400;1,600&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="bg-mist text-ink font-body antialiased selection:bg-amber selection:text-ink min-h-screen flex flex-col">
    {{-- Global Site Header --}}
    <x-header />

    {{-- Page Content --}}
    <main class="flex-grow">
        @yield('content')
        {{ $slot ?? '' }}
    </main>

    {{-- Global Site Footer --}}
    <x-footer />

    {{-- Global Floating WhatsApp Action --}}
    <x-whatsapp-fab />

    @livewireScripts
</body>
</html>
