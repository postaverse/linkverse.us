<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ShortenedUrl; // Assuming you have a ShortenedUrl model

class RedirectController extends Controller
{
    public function redirect($code)
    {
        // Find the original URL based on the code
        $url = ShortenedUrl::where('short_code', $code)->first();

        if ($url) {
            // Redirect to the original URL
            return redirect()->away($url->original_url);
        } else {
            // Redirect to a default page or show an error message if the URL is not found
            return redirect('/')->with('error', 'Shortened URL not found.');
        }
    }
}
