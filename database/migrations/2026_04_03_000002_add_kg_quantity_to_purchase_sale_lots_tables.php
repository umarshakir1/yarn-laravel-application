<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('purchase_items', function (Blueprint $table) {
            $table->decimal('kg_quantity', 10, 2)->nullable()->after('subtotal');
        });

        Schema::table('lots', function (Blueprint $table) {
            $table->decimal('kg_quantity', 10, 2)->nullable()->after('remaining_bags');
        });

        Schema::table('sale_items', function (Blueprint $table) {
            $table->decimal('kg_quantity', 10, 2)->nullable()->after('profit');
        });
    }

    public function down(): void
    {
        Schema::table('purchase_items', function (Blueprint $table) {
            $table->dropColumn('kg_quantity');
        });

        Schema::table('lots', function (Blueprint $table) {
            $table->dropColumn('kg_quantity');
        });

        Schema::table('sale_items', function (Blueprint $table) {
            $table->dropColumn('kg_quantity');
        });
    }
};
