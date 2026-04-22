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
        Schema::table('posts', function (Blueprint $table) {
            // 添加 SEO 字段
            $table->string('seo_title', 255)->nullable()->after('excerpt');
            $table->string('seo_description', 500)->nullable()->after('seo_title');

            // 添加索引
            $table->index('seo_title');
            $table->index('seo_description');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropIndex(['seo_title']);
            $table->dropIndex(['seo_description']);
            $table->dropColumn(['seo_title', 'seo_description']);
        });
    }
};
