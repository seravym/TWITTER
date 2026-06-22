<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tambahkan kolom media, pin, dan visibility ke tabel posts
     */
    public function up(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            // Media upload (gambar atau video)
            $table->string('media_path')->nullable()->after('content');
            $table->string('media_type')->nullable()->after('media_path'); // 'image' | 'video' | null

            // Pin post ke atas profil
            $table->boolean('is_pinned')->default(false)->after('media_type');

            // Visibilitas post: public = semua, close_friend = hanya close friend
            $table->enum('visibility', ['public', 'close_friend'])->default('public')->after('is_pinned');
        });
    }

    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropColumn(['media_path', 'media_type', 'is_pinned', 'visibility']);
        });
    }
};