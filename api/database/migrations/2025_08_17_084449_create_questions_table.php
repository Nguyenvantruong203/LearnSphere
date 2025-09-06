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
      $table->json('options')->nullable();          // [{key:"A", text:"..."}]
      $table->json('correct_options')->nullable();  // ["A","C"] ...
      $table->decimal('weight', 8, 2)->default(1.00);
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
