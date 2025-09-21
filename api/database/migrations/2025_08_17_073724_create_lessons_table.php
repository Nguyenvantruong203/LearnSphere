<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('lessons', function (Blueprint $t) {
            $t->id();
            $t->foreignId('topic_id')->constrained('topics')->cascadeOnDelete();
            $t->string('title');

            $t->enum('video_provider', ['youtube', 'cloudinary', 'vimeo'])->default('youtube');
            $t->string('video_id')->nullable();
            $t->string('video_url')->nullable();

            $t->text('content')->nullable();
            $t->unsignedInteger('order')->default(1);
            $t->unsignedInteger('duration_seconds')->nullable();
            $t->json('player_params')->nullable(); 
            $t->timestamps();

            $t->unique(['topic_id', 'title']);
            $t->index(['topic_id', 'order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lessons');
    }
};
