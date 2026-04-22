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
        Schema::create('visitors', function (Blueprint $table) {
            $table->id();
            $table->string('ip_address', 45)->nullable()->comment('访客 IP');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null')->comment('用户 ID');
            $table->foreignId('post_id')->nullable()->constrained()->onDelete('cascade')->comment('文章 ID');
            $table->string('user_agent', 500)->nullable()->comment('User Agent');
            $table->string('referer', 500)->nullable()->comment('来源页面');
            $table->string('country', 100)->nullable()->comment('国家');
            $table->string('city', 100)->nullable()->comment('城市');
            $table->timestamp('visited_at')->nullable()->comment('访问时间');

            // 索引
            $table->index(['ip_address', 'visited_at']);
            $table->index(['post_id', 'visited_at']);
            $table->index(['visited_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('visitors');
    }
};
