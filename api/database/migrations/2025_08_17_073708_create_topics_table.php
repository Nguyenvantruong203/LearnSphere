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
        Schema::create('topics', function (Blueprint $t) {
            $t->id();
            $t->foreignId('course_id')->constrained('courses')->cascadeOnDelete();
            $t->string('title');
            $t->unsignedInteger('order')->default(1);
            $t->timestamps();
            $t->softDeletes(); // khôi phục

            $t->unique(['course_id', 'title']);
            $t->index(['course_id', 'order']);
            $t->unique(['course_id','order']); // chặn trùng thứ tự
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('topics');
    }
};
