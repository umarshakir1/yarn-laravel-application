<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add cost_price to services table
        Schema::table('services', function (Blueprint $table) {
            $table->decimal('cost_price', 15, 2)->default(0)->after('price');
        });

        // Add cost and profit snapshots to sale_services pivot table
        Schema::table('sale_services', function (Blueprint $table) {
            $table->decimal('service_cost', 15, 2)->default(0)->after('price'); // snapshot of total cost
            $table->decimal('service_profit', 15, 2)->default(0)->after('service_cost'); // price snapshot - cost snapshot
        });
    }

    public function down(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->dropColumn('cost_price');
        });

        Schema::table('sale_services', function (Blueprint $table) {
            $table->dropColumn(['service_cost', 'service_profit']);
        });
    }
};
