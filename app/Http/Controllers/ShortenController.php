<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ShortenedUrl;
use Illuminate\Support\Str;

class ShortenController extends Controller
{

    protected $dates = ['created_at', 'updated_at'];

    public function getTopVisitedShortCodes()
    {
        $topVisited = ShortenedUrl::orderBy('hits', 'DESC')->take(10)->get(['short_code', 'hits', 'original_url']);
        return view('home', compact('topVisited'));
    }

    public function getRecentlyCreatedShortCodes()
    {
        $recentlyCreated = ShortenedUrl::orderBy('created_at', 'DESC')->take(10)->get(['short_code', 'original_url', 'created_at']);
        return view('home', compact('recentlyCreated'));
    }

    public function shorten(Request $request)
    {
        // Validate the input
        $request->validate([
            'original_url' => 'required|url'
        ]);

        // Search for an existing short code with the same original URL
        $existingUrl = ShortenedUrl::where('original_url', $request->original_url)->first();

        if ($existingUrl) {
            // Return the existing short code and QR URL if found
            return response()->json([
                'short_code' => $existingUrl->short_code,
                'qr_code_url' => url("/qr/{$existingUrl->short_code}")
            ]);
        } else {
            // Generate a unique short code if not found
            $shortCode = Str::random(6);

            // Save to database
            ShortenedUrl::create([
                'original_url' => $request->original_url,
                'short_code' => $shortCode,
            ]);

            // Return the new short code and QR URL
            return response()->json([
                'short_code' => $shortCode,
                'qr_code_url' => url("/qr/{$shortCode}")
            ]);
        }
    }

    public function index()
    {
        $topVisited = ShortenedUrl::orderBy('hits', 'DESC')->take(10)->get(['short_code', 'original_url', 'hits']);
        $recentlyCreated = ShortenedUrl::orderBy('created_at', 'DESC')->take(10)->get(['short_code', 'original_url', 'created_at']);
        return view('home', compact('topVisited', 'recentlyCreated'));
    }
}
