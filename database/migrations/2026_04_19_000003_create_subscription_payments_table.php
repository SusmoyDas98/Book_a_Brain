<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('subscription_payments')) {
            return;
        }

        Schema::create('subscription_payments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('transaction_id', 64)->unique();
            $table->string('subscriber_type', 20);
            $table->unsignedBigInteger('subscriber_id');
            $table->unsignedBigInteger('subscription_id');
            $table->decimal('amount', 10, 2);
            $table->string('currency', 10)->default('BDT');
            $table->string('payment_method', 50)->default('portal');
            $table->string('payment_status', 20);
            $table->date('payment_date')->nullable();
            $table->string('billing_period', 20)->nullable();
            $table->timestamps();

            $table->index(['subscriber_type', 'subscriber_id']);
            $table->index('subscription_id');
            $table->index('payment_status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subscription_payments');
    }
};
