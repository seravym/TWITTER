<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('account_id')->constrained('accounts')->onDelete('cascade');     // penerima notif
            $table->foreignId('sender_id')->nullable()->constrained('accounts')->onDelete('cascade'); // pengirim aksi
            $table->string('type');           // follow_request, follow_accepted, like, comment
            $table->string('message');        // teks notifikasi
            $table->unsignedBigInteger('reference_id')->nullable(); // id post/follow terkait
            $table->boolean('is_read')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};