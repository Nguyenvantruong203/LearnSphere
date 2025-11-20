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
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('thumbnail_url')->nullable();
            $table->string('short_description', 255)->nullable();
            $table->text('description')->nullable();

            $table->string('status')->default('draft');
            $table->timestamp('publish_at')->nullable();
            $table->string('subject')->nullable();

            // 💲 Giá theo USD
            $table->decimal('price', 12, 2)->default(0);
            $table->char('currency', 3)->default('USD'); // ✅ đổi sang USD

            // 💰 Phần chia doanh thu
            $table->decimal('instructor_share', 5, 2)->default(70.00);
            $table->decimal('platform_fee', 5, 2)->default(30.00);

            // 🌍 Ngôn ngữ mặc định là English
            $table->enum('level', ['beginner', 'intermediate', 'advanced'])->default('beginner');
            $table->string('language', 5)->default('en'); // ✅ đổi sang en

            $table->boolean('is_featured')->default(false);

            $table->text('rejection_reason')->nullable();
            $table->timestamp('rejected_at')->nullable();

            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['status', 'subject', 'publish_at']);
            $table->fullText(['title', 'short_description', 'description']);
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
