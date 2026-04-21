<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('session_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contract_id')->constrained('tuition_contracts')->onDelete('cascade');
            $table->date('week_start');           // Monday of that week
            $table->boolean('mon')->default(false);
            $table->boolean('tue')->default(false);
            $table->boolean('wed')->default(false);
            $table->boolean('thu')->default(false);
            $table->boolean('fri')->default(false);
            $table->boolean('sat')->default(false);
            $table->boolean('sun')->default(false);
            $table->text('tutor_note')->nullable();
            $table->text('guardian_note')->nullable();
            $table->timestamps();

            // One log per week per contract
            $table->unique(['contract_id', 'week_start']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('session_logs');
    }
};
