<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Linkverse')</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- SEO tags -->
    <meta name="description" content="Linkverse is a free URL shortening service.">
    <meta name="keywords"
        content="linkverse, url shortener, free url shortener, link shortener, shorten link, shorten url">
    <meta name="author" content="Zander Lewis">
    <meta name="robots" content="index, follow">
    <meta property="og:title" content="Linkverse">
    <meta property="og:description" content="Linkverse is a free URL shortening service.">
    <meta property="og:sitename" content="Linkverse">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ request()->getSchemeAndHttpHost() }}">
    <meta property="og:locale" content="en_US">
    @vite('resources/css/app.css')
    <style>
        /* Muted grid background for dark mode */
        .bg-grid-dark {
            background-image: radial-gradient(circle, rgba(255, 255, 255, 0.03) 1px, transparent 1px);
            background-size: 25px 25px;
        }
    </style>
</head>

<body class="font-sans antialiased bg-gray-950 text-gray-200 min-h-screen">
    <div class="absolute inset-0 bg-grid-dark -z-10"></div>
    <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-12">
        @yield('content')
    </div>

    <footer class="mt-16 text-center text-gray-500 text-sm">
        <p>&copy; {{ date('Y') }} Linkverse - Free URL shortening service</p>
    </footer>
</body>

</html>
