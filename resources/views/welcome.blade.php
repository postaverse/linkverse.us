<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Linkverse</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
    <meta name="csrf-token" content="{{ csrf_token() }}"> <!-- CSRF Token for security -->

    <!-- SEO -->
    <meta name="description" content="Linkverse is a free URL shortening service.">
    <meta name="keywords" content="linkverse, url shortener, free url shortener, link shortener, shorten link, shorten url, zander, zander lewis, postaverse, tools, tool, postaverse tool, postaverse tools, zander tool, zander tools, zander lewis tools, zander lewis tool">
    <meta name="author" content="Zander Lewis">
    <meta name="robots" content="index, follow">
    <meta name="og:title" content="Linkverse">
    <meta name="og:description" content="Linkverse is a free URL shortening service.">
    <meta name="og:sitename" content="Linkverse">
    <meta name="og:type" content="website">
    <meta name="og:url" content="https://linkverse.us">
    <meta name="og:locale" content="en_US">

    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>

<body class="font-sans antialiased dark:bg-black text-white/50">
    <div class="bg-gray-800 text-black/50 dark:text-white/50">
        <img id="background" class="absolute -left-20 top-0 max-w-[877px]" src="https://laravel.com/assets/img/welcome/background.svg" />
        <div class="relative min-h-screen flex flex-col items-center justify-center selection:bg-[#FF2D20] selection:text-white">
            <div class="relative w-full max-w-2xl px-6 lg:max-w-7xl">
                <header class="grid grid-cols-2 items-center gap-2 py-10 lg:grid-cols-3">
                    <!-- Link Shortening Form -->
                    <div class="mt-8 text-center">
                        <h2 class="text-lg font-semibold">Shorten Your Link</h2>
                        <form id="shortenForm" action="/shorten" method="POST">
                            @csrf <!-- CSRF Token for security -->
                            <input type="url" name="original_url" placeholder="Enter your URL here" required class="border p-2 rounded text-black">
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Shorten
                            </button>
                        </form>
                        <div id="shortenedUrl" class="mt-4"></div>
                    </div>

                    <script>
                        document.getElementById('shortenForm').addEventListener('submit', function(e) {
                            e.preventDefault(); // Prevent the default form submission
                            const formData = new FormData(this);

                            fetch('/shorten', {
                                    method: 'POST',
                                    body: formData,
                                    headers: {
                                        'X-Requested-With': 'XMLHttpRequest', // For Laravel to recognize the request as AJAX
                                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') // CSRF token
                                    },
                                })
                                .then(response => response.json())
                                .then(data => {
                                    // Display the shortened URL and a Copy button
                                    const shortenedUrl = `/${data.short_code}`;
                                    const displayElement = document.getElementById('shortenedUrl');
                                    displayElement.innerHTML = `Shortened URL: <a href="https://linkverse.us${shortenedUrl}" target="_blank">https://linkverse.us${shortenedUrl}</a> <button id="copyButton">Copy</button>`;

                                    // Add click event listener for the Copy button
                                    document.getElementById('copyButton').addEventListener('click', function() {
                                        navigator.clipboard.writeText(shortenedUrl).then(() => {
                                            alert('Shortened URL copied to clipboard!');
                                        }).catch(err => {
                                            console.error('Error in copying text: ', err);
                                        });
                                    });
                                })
                                .catch(error => console.error('Error:', error));
                        });
                    </script>

                    <div class="top-visited">
                        <h2>Top Visited Short Codes</h2>
                        <ul>
                            @if(isset($topVisited) && $topVisited->count() > 0)
                            @foreach($topVisited as $url)
                            <li><a href="https://linkverse.us/{{ $url->short_code }}" target="_blank">{{ $url->short_code }} ({{ $url->original_url }}) - Visits: {{ $url->hits }}</a></li>
                            @endforeach
                            @else
                            <li>No data available.</li>
                            @endif
                        </ul>
                    </div>
                    <div class="recently-created">
                        <h2>Recently Created Short Codes</h2>
                        <ul>
                            @if(isset($recentlyCreated) && $recentlyCreated->count() > 0)
                            @foreach($recentlyCreated as $url)
                            <li><a href="https://linkverse.us/{{ $url->short_code }}" target="_blank">{{ $url->short_code }} ({{ $url->original_url }}) - Created:
                                @if($url->created_at instanceof \Carbon\Carbon)
                                {{ $url->created_at->diffForHumans() }}
                                @else
                                {{ $url->created_at }}
                                @endif
                    </a></li>
                            @endforeach
                            @else
                            <li>No data available.</li>
                            @endif
                        </ul>
                    </div>
                </header>
            </div>
        </div>
    </div>
</body>

</html>