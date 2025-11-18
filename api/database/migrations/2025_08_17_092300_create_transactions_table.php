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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete();
            $table->decimal('amount', 12, 2);
            $table->string('status')->default('pending'); // pending|succeeded|failed
            $table->string('provider')->nullable();       // vnpay|momo|paypal...
            $table->string('transaction_code')->nullable(); // mã nội bộ
            $table->string('currency', 10)->default('USD');
            $table->string('provider_txn_id')->nullable();  // vnp_TransactionNo
            $table->string('provider_order_id')->nullable(); // vnp_TxnRef
            $table->text('signature')->nullable();          // vnp_SecureHash
            $table->json('raw_params')->nullable();         // toàn bộ payload IPN/return
            $table->timestamp('ipn_received_at')->nullable();
            $table->timestamps();

            $table->unique('transaction_code');
            $table->index(['order_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
