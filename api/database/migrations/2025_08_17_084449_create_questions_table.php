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
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quiz_id')->constrained('quizzes')->cascadeOnDelete();
            $table->string('type');             // single|multi|truefalse|fillblank...
            $table->longText('text');           // nội dung câu hỏi
            $table->json('options')->nullable();          // {"A":"...", "B":"..."}
            $table->json('correct_options')->nullable();  // ["A","C"]
            $table->decimal('weight', 8, 2)->default(1.00);
            $table->boolean('is_temp')->default(false);   // tạm thời, chưa chính thức

            // mới thêm: liên kết câu hỏi gốc (nếu copy từ lesson sang topic)
            $table->unsignedBigInteger('origin_question_id')->nullable();
            $table->foreign('origin_question_id')->references('id')->on('questions')->nullOnDelete();

            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('questions');
    }
};
