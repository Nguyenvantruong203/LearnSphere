<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('course_certificates', function (Blueprint $table) {
            $table->id();

            // Foreign keys
            $table->foreignId('user_id')
                ->constrained('users')
                ->onDelete('cascade');

            $table->foreignId('course_id')
                ->constrained('courses')
                ->onDelete('cascade');

            // Certificate info
            $table->string('certificate_code')->unique();  // LS-ABC123 hoặc random
            $table->timestamp('issued_at');               // ngày cấp chứng chỉ
            $table->timestamps();

            // Mỗi user chỉ có duy nhất 1 chứng chỉ cho 1 khóa
            $table->unique(['user_id', 'course_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('course_certificates');
    }
};
