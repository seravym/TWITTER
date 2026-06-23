<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reposts', function (Blueprint $table) {
            $table->id();

            $table->foreignId('account_id')
                ->constrained('accounts')
                ->onDelete('cascade');

            $table->foreignId('post_id')
                ->constrained('posts')
                ->onDelete('cascade');

            $table->timestamps();

            $table->unique(['account_id', 'post_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reposts');
    }
};