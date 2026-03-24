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
        Schema::create('purchase_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('purchase_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->decimal('bags', 15, 2); // Recorded in bags
            $table->decimal('bundles', 15, 2); // Calculated: bags * 5
            $table->decimal('unit_price_per_bundle', 15, 2); // Pricing is per Bundle
            $table->decimal('subtotal', 15, 2); // Calculated: bundles * unit_price_per_bundle
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_items');
    }
};
