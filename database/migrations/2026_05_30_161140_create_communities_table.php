<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('communities', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->foreignId('creator_id')->constrained('accounts')->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('community_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('community_id')->constrained('communities')->onDelete('cascade');
            $table->foreignId('account_id')->constrained('accounts')->onDelete('cascade');
            $table->string('role')->default('member'); // contoh: admin, member
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('community_user');
        Schema::dropIfExists('communities');
    }
};