@extends('layouts.app')

@section('title', 'Linkverse')

@section('content')
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
                @php
                    $topVisitedThisWeek = $topVisited
                        ->filter(function ($url) {
                            return \Carbon\Carbon::parse($url->created_at)->greaterThan(
                                \Carbon\Carbon::now()->subWeek(),
                            );
                        })
                        ->take(5);
                @endphp
                <ul class="space-y-3">
                    @if (isset($topVisitedThisWeek) && $topVisitedThisWeek->count() > 0)
                        @foreach ($topVisitedThisWeek as $url)
                            <li class="p-3.5 bg-gray-800/50 rounded-lg hover:bg-gray-700 transition-colors">
                                <div class="flex flex-col">
                                    <a href="{{ request()->getSchemeAndHttpHost() . '/' . $url->short_code }}"
                                        target="_blank">
                                        <span class="font-medium text-white">
                                            {{ request()->getSchemeAndHttpHost() }}/{{ $url->short_code }}
                                        </span>
                                    </a>
                                    <span class="text-sm text-gray-400 truncate">{{ $url->original_url }}</span>
                                    <div class="flex items-center justify-between mt-1">
                                        <span class="text-xs text-blue-400 font-medium">{{ $url->hits }} visits</span>
                                        <a href="{{ request()->getSchemeAndHttpHost() . '/qr/' . $url->short_code }}"
                                            class="px-2 py-1 bg-green-600 hover:bg-green-700 rounded text-white text-xs">
                                            QR Code
                                        </a>
                                    </div>
                                </div>
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
                @php
                    $recentThisWeek = $recentlyCreated
                        ->filter(function ($url) {
                            return \Carbon\Carbon::parse($url->created_at)->greaterThan(
                                \Carbon\Carbon::now()->subWeek(),
                            );
                        })
                        ->take(5);
                @endphp
                <ul class="space-y-3">
                    @if ($recentThisWeek->count() > 0)
                        @foreach ($recentThisWeek as $url)
                            <li class="p-3.5 bg-gray-800/50 rounded-lg hover:bg-gray-700 transition-colors">
                                <div class="flex flex-col">
                                    <a href="{{ request()->getSchemeAndHttpHost() . '/' . $url->short_code }}"
                                        target="_blank">
                                        <span class="font-medium text-white">
                                            {{ request()->getSchemeAndHttpHost() }}/{{ $url->short_code }}
                                        </span>
                                    </a>
                                    <span class="text-sm text-gray-400 truncate">{{ $url->original_url }}</span>
                                    <div class="flex items-center justify-between mt-1">
                                        <span class="text-xs text-purple-400">
                                            @if ($url->created_at instanceof \Carbon\Carbon)
                                                {{ $url->created_at->diffForHumans() }}
                                            @else
                                                {{ $url->created_at }}
                                            @endif
                                        </span>
                                        <a href="{{ request()->getSchemeAndHttpHost() . '/qr/' . $url->short_code }}"
                                            class="px-2 py-1 bg-green-600 hover:bg-green-700 rounded text-white text-xs">
                                            QR Code
                                        </a>
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    @else
                        <li class="p-4 bg-gray-800/50 rounded-lg text-gray-400">No data available.</li>
                    @endif
                </ul>
            </div>
        </div>
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
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content')
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
                    <a href="${location.origin}/qr/${data.short_code}" target="_blank" class="px-3 py-1 bg-green-600 hover:bg-green-700 rounded text-white text-sm transition-colors">
                        QR Code
                    </a>
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
@endsection
