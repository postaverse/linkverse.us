<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ShortenedUrl;
use Illuminate\Support\Str;

class ShortenController extends Controller
{
    public function shorten(Request $request)
    {
        // Validate the input
        $request->validate([
            'original_url' => 'required|url'
        ]);

        // Search for an existing short code with the same original URL
        $existingUrl = ShortenedUrl::where('original_url', $request->original_url)->first();

        if ($existingUrl) {
            // Return the existing short code if found
            return response()->json([
                'short_code' => $existingUrl->short_code
            ]);
        } else {
            // Generate a unique short code if not found
            $shortCode = Str::random(6);

            // Save to database
            $url = ShortenedUrl::create([
                'original_url' => $request->original_url,
                'short_code' => $shortCode,
            ]);

            // Return the new short code
            return response()->json([
                'short_code' => $shortCode
            ]);
        }
    }
}
