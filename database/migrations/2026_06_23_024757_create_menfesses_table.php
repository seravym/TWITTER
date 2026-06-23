<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('menfesses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sender_id')->constrained('accounts')->onDelete('cascade');
            $table->foreignId('base_id')->constrained('accounts')->onDelete('cascade');
            $table->text('message');
            $table->enum('status', ['pending', 'approved'])->default('pending');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('menfesses');
    }
};
