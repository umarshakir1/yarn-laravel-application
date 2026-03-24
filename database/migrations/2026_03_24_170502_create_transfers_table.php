<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Records every account-to-account transfer movement.
     * Each saved transfer automatically generates two ledger_entries rows
     * (debit on the destination side, credit on the source side).
     *
     * Supported transfer types:
     *  - bank       → supplier  : Pay supplier from bank
     *  - customer   → bank      : Receive payment from customer into bank
     *  - bank       → bank      : Transfer between two bank accounts
     *  - cash       → supplier  : Pay supplier in cash
     *  - customer   → cash      : Receive payment from customer into cash
     */
    public function up(): void
    {
        Schema::create('transfers', function (Blueprint $table) {
            $table->id();

            $table->date('date');

            // Source (Credit side)
            $table->enum('from_account_type', ['customer', 'supplier', 'bank', 'cash']);
            $table->unsignedBigInteger('from_account_id'); // client_id or accounts.id

            // Destination (Debit side)
            $table->enum('to_account_type', ['customer', 'supplier', 'bank', 'cash']);
            $table->unsignedBigInteger('to_account_id');   // client_id or accounts.id

            $table->decimal('amount', 15, 2);
            $table->string('description')->nullable();
            $table->string('reference_no')->nullable()->unique(); // Auto-generated or manual reference

            $table->timestamps();

            // Index for filtering on the transfer history page
            $table->index(['date']);
            $table->index(['from_account_type', 'from_account_id']);
            $table->index(['to_account_type', 'to_account_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transfers');
    }
};
