<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('recipient_type', 20);
            $table->unsignedBigInteger('recipient_id');
            $table->string('title', 100);
            $table->text('message');
            $table->string('type', 30);
            $table->unsignedBigInteger('related_job_id')->nullable();
            $table->boolean('is_read')->default(false);
            $table->timestamp('read_at')->nullable();
            $table->timestamps();

            $table->index(['recipient_type', 'recipient_id']);
            $table->index('is_read');
            $table->index('related_job_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
