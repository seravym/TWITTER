<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('comments', 'is_toxic')) {
            Schema::table('comments', function (Blueprint $table) {
                $table->boolean('is_toxic')->default(false)->after('content');
            }); 
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('comments', 'is_toxic')) {
            Schema::table('comments', function (Blueprint $table) {
                $table->dropColumn('is_toxic');
            });
        }
    }
};