<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tuition_contracts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('guardian_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('tutor_id')->constrained('users')->onDelete('cascade');
            $table->string('subject');
            $table->string('schedule');           // e.g. "Mon, Wed, Fri - 5pm"
            $table->decimal('salary', 10, 2);     // monthly salary agreed
            $table->date('start_date');
            $table->enum('status', ['PENDING', 'ACTIVE', 'ENDED'])->default('PENDING');
            $table->text('guardian_notes')->nullable();
            $table->text('tutor_notes')->nullable();
            $table->timestamp('ended_at')->nullable();
            $table->boolean('is_paid')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tuition_contracts');
    }
};
