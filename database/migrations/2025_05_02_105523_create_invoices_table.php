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
        // migration: create_invoices_and_invoice_items.php
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('babysitter_id')->constrained('users');
            $table->date('period_start');
            $table->date('period_end');
            $table->enum('status', ['draft', 'sent', 'paid'])->default('draft');
            $table->decimal('total_amount', 10, 2);
            $table->timestamps();
        });

        Schema::create('invoice_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')->constrained();
            $table->foreignId('work_id')->constrained();
            $table->string('description');
            $table->integer('hours');
            $table->decimal('unit_price', 10, 2);
            $table->decimal('amount', 10, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
        Schema::dropIfExists('invoice_items');
    }
};
