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
        Schema::create('babysitter_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                ->constrained()
                ->onDelete('cascade');
            $table->string('first_name')->default('User');
            $table->string('last_name')->default('User');
            $table->date('birthdate')->nullable();
            $table->string('phone')->nullable();
            $table->string('address')->nullable();
            $table->text('bio')->nullable();
            $table->text('experience')->nullable();
            $table->timestamps();
        });

        Schema::create('babysitter_profile_photos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('babysitter_profile_id')->constrained()->onDelete('cascade');
            $table->boolean('is_profile_picture')->default(false);
            $table->string('url');
            $table->timestamps();
        });

        Schema::create('babysitter_profile_certifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('babysitter_profile_id')->constrained()->onDelete('cascade');
            $table->string('title')->default('certification');
            $table->text('description')->nullable(); // Optional description
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('babysitter_profiles');
        Schema::dropIfExists('babysitter_profile_photos');
        Schema::dropIfExists('babysitter_profile_certifications');
    }
};
