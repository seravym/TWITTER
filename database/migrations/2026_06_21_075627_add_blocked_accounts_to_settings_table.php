<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tambahkan kolom blocked_accounts ke tabel settings
     * untuk menyimpan daftar account_id yang di-block (JSON array)
     */
    public function up(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->json('blocked_accounts')->nullable()->after('language');
        });
    }

    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn('blocked_accounts');
        });
    }
};