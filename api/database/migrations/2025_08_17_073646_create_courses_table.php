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
        Schema::create('courses', function (Blueprint $t) {
            $t->id();
            $t->string('title');
            $t->string('slug')->unique();
            $t->string('thumbnail_url')->nullable();
            $t->string('short_description', 255)->nullable();
            $t->text('description')->nullable();

            $t->enum('status', ['draft', 'published', 'archived'])->default('draft');
            $t->timestamp('publish_at')->nullable();
            $t->string('subject')->nullable();

            $t->decimal('price', 12, 2)->default(0);
            $t->char('currency', 3)->default('VND');

            $t->enum('level', ['beginner', 'intermediate', 'advanced'])->default('beginner');
            $t->string('language', 5)->default('vi');

            $t->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            $t->timestamps();
            $t->softDeletes();

            $t->index(['status', 'subject', 'publish_at']);
            $t->fullText(['title', 'short_description', 'description']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
