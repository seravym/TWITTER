<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reporter_id')->constrained('accounts')->onDelete('cascade');
            $table->morphs('reportable');
            $table->string('reason', 100);
            $table->text('details')->nullable();
            $table->enum('status', ['pending', 'reviewed', 'rejected'])->default('pending');
            $table->timestamps();

            $table->unique(['reporter_id', 'reportable_type', 'reportable_id'], 'reports_unique_user_target');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
