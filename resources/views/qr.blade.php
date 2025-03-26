@extends('layouts.app')

@section('title', 'QR Code - Linkverse')

@section('content')
    <div class="bg-gray-900/80 backdrop-blur-lg rounded-xl p-6 border border-gray-800 shadow-2xl">
        <h1 class="text-2xl text-center font-semibold pb-4 text-purple-400">QR Code for your Link</h1>
        <div class="flex flex-col items-center">
            <!-- Render the inline SVG QR code with a white border -->
            <div class="mb-6 inline-block border border-white border-10">
                {!! $qrImage !!}
            </div>
            <div class="mb-4">
                <span class="text-green-400 font-medium">Original URL:</span>
                <a href="{{ $originalUrl }}" target="_blank" class="text-white hover:text-blue-300 hover:underline">
                    {{ $originalUrl }}
                </a>
            </div>
            <div class="mb-4">
                <span class="text-green-400 font-medium">Shortened URL:</span>
                <a href="{{ $shortUrl }}" target="_blank" class="text-white hover:text-blue-300 hover:underline">
                    {{ $shortUrl }}
                </a>
            </div>
            <div>
                <a href="{{ url('/') }}"
                    class="px-4 py-2 bg-blue-600 hover:bg-blue-700 rounded text-white transition-colors">
                    Back to Home
                </a>
            </div>
        </div>
    </div>
@endsection
