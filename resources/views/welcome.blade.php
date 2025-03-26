<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Linkverse</title>
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
        /* Muted grid background for dark mode */
        .bg-grid-dark {
            background-image: radial-gradient(circle, rgba(255,255,255,0.03) 1px, transparent 1px);
            background-size: 25px 25px;
        }
    </style>
</head>

<body class="font-sans antialiased bg-gray-950 text-gray-200 min-h-screen">
    <div class="absolute inset-0 bg-grid-dark -z-10"></div>
    <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-12">
        <header class="flex justify-center items-center mb-12">
            <h1 class="text-5xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-blue-400 to-purple-500">
                Linkverse
            </h1>
        </header>
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Link Shortening Form -->
            <div class="col-span-1 lg:col-span-3">
                <div class="bg-gray-900/80 backdrop-blur-lg rounded-xl p-8 border border-gray-800 shadow-2xl">
                    <h2 class="text-2xl font-semibold text-center mb-6">Shorten Your Link</h2>
                    <form id="shortenForm" action="/shorten" method="POST" class="flex flex-col sm:flex-row gap-4">
                        @csrf
                        <input type="url" name="original_url" placeholder="Enter your URL here" required 
                               class="flex-grow px-4 py-3 rounded-lg bg-gray-800 border border-gray-700 text-gray-100 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all">
                        <button type="submit" 
                                class="px-6 py-3 bg-gradient-to-r from-blue-500 to-purple-600 rounded-lg font-medium text-white shadow-lg hover:shadow-blue-500/20 transition-all duration-300 hover:-translate-y-0.5">
                            Shorten URL
                        </button>
                    </form>
                    <div id="shortenedUrl" class="mt-6 text-center"></div>
                </div>
            </div>
            
            <!-- Top Visited Short Codes -->
            <div class="col-span-1 lg:col-span-2">
                <div class="bg-gray-900/80 backdrop-blur-lg rounded-xl p-6 border border-gray-800 shadow-2xl">
                    <h2 class="text-xl font-semibold mb-5 text-blue-400">Top Visited Links</h2>
                    <ul class="space-y-3">
                        @if(isset($topVisited) && $topVisited->count() > 0)
                            @foreach($topVisited as $url)
                                <li class="p-3.5 bg-gray-800/50 rounded-lg hover:bg-gray-700 transition-colors">
                                <a href="{{ request()->getSchemeAndHttpHost() . '/' . $url->short_code }}" target="_blank" class="flex flex-col">
                                        <span class="font-medium text-white">{{ request()->getSchemeAndHttpHost() }}/{{ $url->short_code }}</span>
                                        <span class="text-sm text-gray-400 truncate">{{ $url->original_url }}</span>
                                        <span class="text-xs mt-1.5 text-blue-400 font-medium">{{ $url->hits }} visits</span>
                                    </a>
                                </li>
                            @endforeach
                        @else
                            <li class="p-4 bg-gray-800/50 rounded-lg text-gray-400">No data available.</li>
                        @endif
                    </ul>
                </div>
            </div>
            
            <!-- Recently Created Short Codes -->
            <div class="col-span-1">
                <div class="bg-gray-900/80 backdrop-blur-lg rounded-xl p-6 border border-gray-800 shadow-2xl">
                    <h2 class="text-xl font-semibold mb-5 text-purple-400">Recently Created</h2>
                    <ul class="space-y-3">
                        @if(isset($recentlyCreated) && $recentlyCreated->count() > 0)
                            @foreach($recentlyCreated as $url)
                                <li class="p-3.5 bg-gray-800/50 rounded-lg hover:bg-gray-700 transition-colors">
                                <a href="{{ request()->getSchemeAndHttpHost() . '/' . $url->short_code }}" target="_blank" class="flex flex-col">
                                        <span class="font-medium text-white">{{ request()->getSchemeAndHttpHost() }}/{{ $url->short_code }}</span>
                                        <span class="text-sm text-gray-400 truncate">{{ $url->original_url }}</span>
                                        <span class="text-xs mt-1.5 text-purple-400">
                                            @if($url->created_at instanceof \Carbon\Carbon)
                                                {{ $url->created_at->diffForHumans() }}
                                            @else
                                                {{ $url->created_at }}
                                            @endif
                                        </span>
                                    </a>
                                </li>
                            @endforeach
                        @else
                            <li class="p-4 bg-gray-800/50 rounded-lg text-gray-400">No data available.</li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>
        
        <footer class="mt-16 text-center text-gray-500 text-sm">
            <p>© {{ date('Y') }} Linkverse - Free URL shortening service</p>
        </footer>
    </div>

    <script>
        document.getElementById('shortenForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);

            fetch('/shorten', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
            })
            .then(response => response.json())
            .then(data => {
                const shortenedUrl = `/${data.short_code}`;
                const fullUrl = `${location.origin}${shortenedUrl}`;
                const displayElement = document.getElementById('shortenedUrl');
                displayElement.innerHTML = `
                    <div class="bg-gray-800 p-4 rounded-lg inline-flex items-center gap-4">
                        <span class="text-green-400 font-medium">Link created!</span>
                        <a href="${fullUrl}" target="_blank" class="text-white hover:text-blue-300 hover:underline">${fullUrl}</a>
                        <button id="copyButton" class="px-3 py-1.5 bg-blue-600 hover:bg-blue-700 rounded text-white text-sm transition-colors">
                            Copy
                        </button>
                    </div>
                `;

                document.getElementById('copyButton').addEventListener('click', function() {
                    navigator.clipboard.writeText(fullUrl).then(() => {
                        this.textContent = 'Copied!';
                        setTimeout(() => {
                            this.textContent = 'Copy';
                        }, 2000);
                    }).catch(err => {
                        console.error('Error copying text: ', err);
                    });
                });
            })
            .catch(error => console.error('Error:', error));
        });
    </script>
</body>
</html>