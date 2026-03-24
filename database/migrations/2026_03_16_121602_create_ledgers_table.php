<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ledgers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained()->onDelete('cascade');
            $table->date('date');
            $table->string('description');
            $table->enum('transaction_type', ['sale', 'purchase', 'payment_received', 'payment_made', 'opening_balance']);
            $table->string('reference_type')->nullable(); // Sale or Purchase
            $table->unsignedBigInteger('reference_id')->nullable(); // sale_id or purchase_id
            $table->decimal('debit', 15, 2)->default(0);  // Receivable increases / Payable decreases
            $table->decimal('credit', 15, 2)->default(0); // Payable increases / Receivable decreases
            $table->decimal('balance', 15, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ledgers');
    }
};
