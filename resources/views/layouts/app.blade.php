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
    <meta name="keywords" content="linkverse, url shortener, free url shortener, link shortener, shorten link, shorten url">
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
    html, body {
        height: 100%;
        margin: 0;
    }

    .bg-grid-dark {
        /* Increased stroke opacity for a more visible grid */
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='40' height='40' viewBox='0 0 40 40'%3E%3Crect width='40' height='40' fill='none' stroke='%23777777' stroke-opacity='0.15' stroke-width='1'/%3E%3C/svg%3E");
        background-size: 40px 40px;
        background-repeat: repeat;
    }
    </style>
</head>
<body class="font-sans antialiased bg-gray-950 text-gray-200 min-h-screen bg-grid-dark">
    <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-12">
        @yield('content')
    </div>
    <footer class="mt-16 pb-8 text-center text-gray-500 text-sm">
        <p>&copy; {{ date('Y') }} Linkverse - Free URL shortening service</p>
    </footer>
    <script>
        // Simple parallax effect for the background
        window.addEventListener('scroll', function() {
            var scrollPos = window.pageYOffset;
            document.querySelector('.bg-grid-dark').style.backgroundPositionY = -(scrollPos * 0.25) + "px";
        });
    </script>
</body>
</html>