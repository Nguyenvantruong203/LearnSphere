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
        Schema::create('quiz_attempts', function (Blueprint $table) {
            $table->id();

            // Khóa ngoại
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('quiz_id')->constrained()->onDelete('cascade');

            // Thông tin lượt làm bài
            $table->unsignedInteger('attempt_no')->default(1)->comment('Số lần làm bài (1,2,3,...)');
            $table->enum('status', ['in_progress', 'completed', 'expired'])->default('in_progress');

            // Kết quả
            $table->float('score')->nullable()->comment('Điểm đạt được');
            $table->float('max_score')->nullable()->comment('Điểm tối đa');
            $table->integer('correct_count')->nullable()->comment('Số câu đúng');
            $table->integer('wrong_count')->nullable()->comment('Số câu sai');
            $table->integer('duration_seconds')->nullable()->comment('Thời gian làm bài (giây)');

            // Thời gian bắt đầu / nộp
            $table->timestamp('started_at')->nullable();
            $table->timestamp('submitted_at')->nullable();

            $table->timestamps();

            // Mỗi user có thể làm lại nhiều lần -> không unique (user_id, quiz_id)
            $table->index(['user_id', 'quiz_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quiz_attempts');
    }
};
