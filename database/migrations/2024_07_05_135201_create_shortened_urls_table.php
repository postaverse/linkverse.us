<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('shortened_urls', function (Blueprint $table) {
            $table->id();
            $table->string('original_url', 2048); // Length is set to 2048 to accommodate very long URLs
            $table->string('short_code', 10)->unique(); // Assuming short codes will be up to 10 characters and unique
            $table->timestamp('created_at')->useCurrent(); // Automatically set the creation date
            $table->unsignedBigInteger('hits')->default(0); // Initialize hit count to 0
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shortened_urls');
    }
};