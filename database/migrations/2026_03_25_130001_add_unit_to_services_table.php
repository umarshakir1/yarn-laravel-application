<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add unit to services table
        Schema::table('services', function (Blueprint $table) {
            $table->string('unit')->default('per_bag')->after('price'); // per_kg, per_bundle, per_bag
        });

        // Add unit and quantity_used to sale_services pivot table
        Schema::table('sale_services', function (Blueprint $table) {
            $table->string('unit')->after('service_id');
            $table->decimal('quantity_used', 15, 2)->after('unit');
        });
    }

    public function down(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->dropColumn('unit');
        });

        Schema::table('sale_services', function (Blueprint $table) {
            $table->dropColumn(['unit', 'quantity_used']);
        });
    }
};
