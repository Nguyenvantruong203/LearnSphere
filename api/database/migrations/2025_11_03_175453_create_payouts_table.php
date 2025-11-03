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
        Schema::create('payouts', function (Blueprint $t) {
            $t->id();
            $t->foreignId('instructor_id')->constrained('users')->cascadeOnDelete();
            $t->foreignId('order_item_id')->constrained('order_items')->cascadeOnDelete();
            $t->decimal('total_amount', 12, 2);
            $t->decimal('platform_fee', 12, 2);
            $t->decimal('instructor_amount', 12, 2);
            $t->enum('status', ['pending', 'paid'])->default('pending');
            $t->timestamp('paid_at')->nullable();
            $t->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payouts');
    }
};
