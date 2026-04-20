<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('bkash_accounts')) {
            return;
        }

        Schema::create('bkash_accounts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('account_holder_type', 20);
            $table->unsignedBigInteger('account_holder_id');
            $table->string('phone_number', 20);
            $table->string('otp', 10)->default('1234');
            $table->string('password', 10)->default('1111');
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['account_holder_type', 'account_holder_id']);
            $table->index('phone_number');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bkash_accounts');
    }
};
