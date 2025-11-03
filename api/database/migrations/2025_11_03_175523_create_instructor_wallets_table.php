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
        Schema::create('instructor_wallets', function (Blueprint $t) {
            $t->id();
            $t->foreignId('instructor_id')->constrained('users')->cascadeOnDelete();
            $t->decimal('balance', 12, 2)->default(0);
            $t->decimal('total_earned', 12, 2)->default(0);
            $t->decimal('total_withdrawn', 12, 2)->default(0);
            $t->char('currency', 3)->default('USD');
            $t->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('instructor_wallets');
    }
};
