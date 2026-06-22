<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Buat tabel close_friends untuk menyimpan daftar close friend setiap user
     */
    public function up(): void
    {
        Schema::create('close_friends', function (Blueprint $table) {
            $table->id();
            // Pemilik daftar close friend
            $table->foreignId('account_id')->constrained('accounts')->onDelete('cascade');
            // Akun yang dimasukkan ke daftar close friend
            $table->foreignId('friend_id')->constrained('accounts')->onDelete('cascade');
            $table->timestamps();

            // Satu pasang akun hanya bisa satu entri
            $table->unique(['account_id', 'friend_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('close_friends');
    }
};