<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('account_id')->constrained('accounts')->onDelete('cascade');
            $table->boolean('isPrivateAccount')->default(false);
            $table->string('allowDmFrom')->default('everyone'); // 'everyone' atau 'following'
            $table->boolean('showOnlineStatus')->default(true);
            $table->boolean('notificationMessage')->default(true);
            $table->boolean('notificationFollow')->default(true);
            $table->boolean('notificationLike')->default(true);
            $table->string('theme')->default('light'); // 'light', 'dark', 'system'
            $table->string('language')->default('id'); // 'id', 'en'
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('settings');
    }
};