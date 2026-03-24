<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Universal double-entry ledger covering all account types:
     * customer, supplier, bank, and cash.
     * Every financial transaction posts at least two rows here
     * (one debit, one credit) to keep the books balanced.
     */
    public function up(): void
    {
        Schema::create('ledger_entries', function (Blueprint $table) {
            $table->id();

            // Polymorphic-style account reference (manual, not Laravel morph)
            $table->enum('account_type', ['customer', 'supplier', 'bank', 'cash']);
            $table->unsignedBigInteger('account_id'); // client_id or accounts.id depending on type

            $table->enum('entry_type', ['debit', 'credit']);
            $table->decimal('amount', 15, 2);
            $table->date('date');
            $table->string('description');

            // Polymorphic reference to the source transaction
            $table->string('reference_type')->nullable();  // e.g. Sale, Purchase, Transfer
            $table->unsignedBigInteger('reference_id')->nullable();

            $table->timestamps();

            // Index for fast ledger page queries
            $table->index(['account_type', 'account_id', 'date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ledger_entries');
    }
};
