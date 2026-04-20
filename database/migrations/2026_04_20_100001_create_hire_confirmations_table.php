<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hire_confirmations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('job_id');
            $table->unsignedBigInteger('application_id');
            $table->unsignedBigInteger('guardian_id');
            $table->unsignedBigInteger('tutor_id');
            $table->boolean('guardian_confirmed')->default(false);
            $table->boolean('tutor_confirmed')->default(false);
            $table->timestamp('guardian_confirmed_at')->nullable();
            $table->timestamp('tutor_confirmed_at')->nullable();
            $table->string('status', 30)->default('awaiting_tutor');
            $table->text('cancellation_reason')->nullable();
            $table->unsignedBigInteger('cancelled_by')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->timestamps();

            $table->unique(['job_id', 'application_id']);
            $table->index('guardian_id');
            $table->index('tutor_id');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hire_confirmations');
    }
};
