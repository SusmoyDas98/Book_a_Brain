<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('subscriptions')) {
            return;
        }

        Schema::create('subscriptions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('transaction_id', 64)->unique();
            $table->string('subscriber_type', 20);
            $table->unsignedBigInteger('subscriber_id');
            $table->string('plan_name', 50);
            $table->decimal('subscription_amount', 10, 2);
            $table->string('currency', 10)->default('BDT');
            $table->string('billing_cycle', 20);
            $table->string('payment_method', 50)->default('portal');
            $table->string('status', 20);
            $table->date('started_at');
            $table->date('expires_at');
            $table->timestamps();

            $table->index(['subscriber_type', 'subscriber_id']);
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
    }
};
