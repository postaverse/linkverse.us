<?php

namespace App\Http\Controllers;

use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Models\ShortenedUrl;

class QrController extends Controller
{
    public function generate($code)
    {
        $shortUrl = url("/{$code}");
        $originalUrl = ShortenedUrl::originalUrl($shortUrl);

        // Generate QR as SVG image from the full URL without requiring external extensions
        $qrImage = QrCode::format('svg')->size(300)->generate($shortUrl);

        return view('qr', [
            'qrImage' => $qrImage,
            'shortUrl' => $shortUrl,
            'originalUrl' => $originalUrl,
            'code'    => $code,
        ]);
    }
}