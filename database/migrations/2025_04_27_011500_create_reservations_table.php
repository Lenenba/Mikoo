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
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();

            // The user who made the reservation
            $table->foreignId('user_id')
                ->constrained()
                ->onDelete('cascade');

            // The babysitter being booked
            $table->foreignId('babysitter_id')
                ->constrained('users')
                ->onDelete('cascade');

            $table->enum('status', ['pending', 'confirmed', 'canceled'])
                ->default('pending');

            $table->text('notes')->nullable();
            $table->time('start_time')
                ->nullable();
            $table->time('end_time')
                ->nullable();

            // Recurrence fields
            $table->boolean('is_recurring')
                ->default(false);
            $table->enum('recurrence_freq', ['daily', 'weekly', 'monthly', 'yearly'])
                ->nullable();
            $table->integer('recurrence_interval')
                ->nullable();
            $table->date('recurrence_start_date')
                ->nullable();
            $table->date('recurrence_end_date')
                ->nullable();
            $table->string('recurrence_rule')
                ->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};
