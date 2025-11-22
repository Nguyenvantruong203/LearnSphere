<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('flashcard_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('flashcard_id')->constrained('flashcards')->cascadeOnDelete();
            $table->timestamp('reviewed_at')->useCurrent();
            $table->timestamps();

            $table->index(['user_id', 'flashcard_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('flashcard_logs');
    }
};
