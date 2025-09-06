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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('username', 50)->nullable()->unique();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('phone', 20)->nullable()->unique();
            $table->string('password')->nullable();
            $table->string('address')->nullable();
            $table->string('avatar_url')->nullable();
            $table->string('google_id')->nullable()->comment('Google User ID');
            $table->text('google_token')->nullable()->comment('Google Access Token');
            $table->text('google_refresh_token')->nullable()->comment('Google Refresh Token');
            $table->date('birth_date')->nullable();
            $table->enum('gender', ['male','female','other'])->nullable();

            $table->string('role')->default('student'); // admin|instructor|student (tùy Policy)
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
