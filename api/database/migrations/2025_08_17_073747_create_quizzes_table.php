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
        Schema::create('quizzes', function (Blueprint $table) {
            $table->id();

            // Quan hệ đến Topic (bắt buộc)
            $table->foreignId('topic_id')
                ->constrained('topics')
                ->cascadeOnDelete();

            // Quan hệ đến Lesson (optional)
            $table->foreignId('lesson_id')
                ->nullable()
                ->constrained('lessons')
                ->cascadeOnDelete();

            // Quiz info
            $table->string('title');
            $table->unsignedInteger('duration_minutes')->default(0);
            $table->tinyInteger('shuffle_questions')->default(0);
            $table->tinyInteger('shuffle_options')->default(0);
            $table->unsignedInteger('max_attempts')->default(0); // 0 = không giới hạn
            $table->timestamps();

            // Index hỗ trợ query
            $table->index(['topic_id', 'lesson_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quizzes');
    }
};
