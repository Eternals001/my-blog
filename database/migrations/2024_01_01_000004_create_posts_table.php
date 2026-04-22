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
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('category_id')->nullable()->constrained('categories')->nullOnDelete();
            $table->string('title', 255);
            $table->string('slug', 255)->unique();
            $table->longText('content'); // Markdown
            $table->longText('html_content')->nullable(); // 渲染后 HTML
            $table->text('excerpt')->nullable();
            $table->string('cover_image', 500)->nullable();
            $table->enum('status', ['draft', 'published', 'scheduled', 'private'])->default('draft');
            $table->boolean('is_sticky')->default(false);
            $table->unsignedInteger('view_count')->default(0);
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // 索引
            $table->index(['status', 'published_at']);
            $table->index(['user_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
