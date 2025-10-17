<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('chat_threads', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->nullable()->constrained('courses')->cascadeOnDelete(); // nếu gắn với khóa học
            $table->boolean('is_group')->default(false);
            $table->string('thread_type')->default('private'); // 'private', 'course_group', 'support'
            $table->string('title')->nullable();
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['course_id', 'is_group', 'thread_type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chat_threads');
    }
};
