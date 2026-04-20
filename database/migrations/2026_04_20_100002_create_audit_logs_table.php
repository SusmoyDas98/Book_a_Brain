<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('event_type', 50);
            $table->unsignedBigInteger('job_id')->nullable();
            $table->unsignedBigInteger('application_id')->nullable();
            $table->unsignedBigInteger('guardian_id')->nullable();
            $table->unsignedBigInteger('tutor_id')->nullable();
            $table->unsignedBigInteger('performed_by');
            $table->string('performed_role', 20);
            $table->decimal('payment_amount', 10, 2)->nullable();
            $table->string('payment_status', 20)->default('pending');
            $table->unsignedBigInteger('payment_ref_id')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index('event_type');
            $table->index('job_id');
            $table->index('guardian_id');
            $table->index('tutor_id');
            $table->index('performed_by');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }
};
