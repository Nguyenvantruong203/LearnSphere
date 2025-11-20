<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('lessons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('topic_id')->constrained('topics')->cascadeOnDelete();
            $table->string('title');

            $table->enum('video_provider', ['youtube', 'cloudinary', 'vimeo'])->default('youtube');
            $table->string('video_id')->nullable();
            $table->string('video_url')->nullable();

            $table->text('content')->nullable();
            $table->unsignedInteger('order')->default(1);
            $table->unsignedInteger('duration_seconds')->nullable();
            $table->json('player_params')->nullable();
            $table->timestamps();

            $table->unique(['topic_id', 'title']);
            $table->index(['topic_id', 'order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lessons');
    }
};
