<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('complaints', function (Blueprint $table) {
            $table->id();
            $table->foreignId('filed_by')->constrained('users')->onDelete('cascade');
            $table->foreignId('against_user')->constrained('users')->onDelete('cascade');
            $table->foreignId('contract_id')->nullable()->constrained('tuition_contracts')->onDelete('set null');
            $table->string('subject', 255);
            $table->text('description');
            $table->enum('status', ['Open', 'Under Review', 'Resolved', 'Dismissed'])->default('Open');
            $table->text('admin_note')->nullable();
            $table->foreignId('resolved_by')->nullable()->constrained('users');
            $table->timestamp('resolved_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('complaints');
    }
};
