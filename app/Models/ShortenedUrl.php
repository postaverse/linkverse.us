<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShortenedUrl extends Model
{
    use HasFactory;

    // Specify the table if it's not the pluralized form of the model name
    protected $table = 'shortened_urls';

    // Specify which attributes can be mass assignable
    protected $fillable = ['original_url', 'short_code', 'creation_date', 'hits'];

    // Specify if the timestamps are used (created_at and updated_at)
    public $timestamps = false;

    public static function originalUrl($shortUrl)
    {
        // First, split the Short URL to get the short code
        $shortCode = explode('/', $shortUrl);
        $shortCode = end($shortCode);
        return self::where('short_code', $shortCode)->value('original_url');
    }
}