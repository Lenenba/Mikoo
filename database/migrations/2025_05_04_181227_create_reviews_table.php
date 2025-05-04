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
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();                                      // Primary key
            $table->foreignId('work_id')                       // Link to the work (reservation)
                ->constrained('works')                       // Assumes table 'works'
                ->cascadeOnDelete();                         // Delete reviews if work is removed

            $table->unsignedTinyInteger('rating');             // Rating 1–5
            $table->string('headline')->nullable();            // Short title of the review
            $table->text('review')->nullable();                // Full text of the review

            $table->timestamps();
        });

        Schema::create('review_photos', function (Blueprint $table) {
            $table->id();                                        // Primary key
            $table->foreignId('review_id')                       // Link back to the review
                ->constrained('reviews')
                ->cascadeOnDelete();                           // Delete photos if review is removed

            $table->string('file_path');                        // Stored path or URL of the photo
            $table->unsignedSmallInteger('order')->default(0);  // Optional: order for display

            $table->timestamps();                               // created_at & updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
        Schema::dropIfExists('review_photos');
    }
};
