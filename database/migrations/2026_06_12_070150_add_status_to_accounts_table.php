<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
    Schema::table('accounts', function (Blueprint $table) {
        $table->string('status_text')->nullable();
        $table->timestamp('status_expires_at')->nullable();
    });
    }

    public function down()
    {
    Schema::table('accounts', function (Blueprint $table) {
        $table->dropColumn(['status_text', 'status_expires_at']);
    });
    }
};
