<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('tuition_payments')) {
            return;
        }

        Schema::create('tuition_payments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('transaction_id', 64)->unique();
            $table->unsignedBigInteger('guardian_id');
            $table->unsignedBigInteger('tutor_id');
            $table->decimal('amount', 10, 2);
            $table->string('currency', 10)->default('BDT');
            $table->string('payment_method', 50)->default('portal');
            $table->string('payment_status', 20);
            $table->date('payment_date')->nullable();
            $table->string('month_label', 20)->nullable();
            $table->string('description', 255)->nullable();
            $table->timestamps();

            $table->index('guardian_id');
            $table->index('tutor_id');
            $table->index('payment_status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tuition_payments');
    }
};
