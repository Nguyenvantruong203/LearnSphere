<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();

            // 🔹 Thông tin cơ bản
            $table->string('name')->nullable();
            $table->string('username', 50)->nullable()->unique();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('phone', 20)->nullable()->unique();
            $table->string('password')->nullable();

            // 🔹 Hồ sơ cá nhân
            $table->string('address')->nullable();
            $table->string('avatar_url')->nullable();
            $table->date('birth_date')->nullable();
            $table->enum('gender', ['male', 'female', 'other'])->nullable();

            // 🔹 Đăng nhập bên thứ ba
            $table->string('google_id')->nullable()->comment('Google User ID');
            $table->text('google_token')->nullable()->comment('Google Access Token');
            $table->text('google_refresh_token')->nullable()->comment('Google Refresh Token');

            // 🔹 Vai trò & trạng thái
            $table->enum('role', ['admin', 'instructor', 'student'])->default('student');
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending')
                ->comment('Trạng thái của tài khoản hoặc hồ sơ giảng viên');

            // 🔹 Thông tin giảng viên (chỉ áp dụng khi role = instructor)
            $table->string('expertise')->nullable()->comment('Chuyên môn chính');
            $table->text('bio')->nullable()->comment('Giới thiệu ngắn');
            $table->string('linkedin_url')->nullable()->comment('Liên kết LinkedIn');
            $table->string('portfolio_url')->nullable()->comment('Liên kết Portfolio / Website');
            $table->integer('teaching_experience')->nullable()->comment('Số năm kinh nghiệm giảng dạy');

            // 🔹 Token & timestamp
            $table->rememberToken();
            $table->timestamps();
        });

        // 🔹 Password resets
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        // 🔹 Sessions
        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('users');
    }
};
