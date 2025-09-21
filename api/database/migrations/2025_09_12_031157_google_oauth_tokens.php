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
        Schema::create('google_oauth_tokens', function (Blueprint $t) {
            $t->id();
            $t->foreignId('user_id')->constrained()->cascadeOnDelete();
            $t->string('provider', 32)->default('youtube'); // 'youtube', ...
            $t->longText('access_token');                   // lưu JSON access_token
            $t->string('refresh_token')->nullable();
            $t->timestamp('expires_at')->nullable();
            $t->timestamps();

            $t->unique(['user_id','provider']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('google_oauth_tokens');
    }
};
