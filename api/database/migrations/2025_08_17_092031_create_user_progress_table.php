<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('course_id')->constrained('courses')->cascadeOnDelete();
            $table->float('progress_percent')->default(0);
            $table->timestamp('last_updated')->useCurrent();
            $table->timestamps();

            $table->unique(['user_id', 'course_id'], 'user_course_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_progress');
    }
};
