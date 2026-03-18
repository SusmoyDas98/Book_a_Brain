<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('job_posts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('guardian_id');
            $table->string('title', 255);
            $table->string('subject', 255);
            $table->string('class_level', 100);
            $table->decimal('expected_salary', 10, 2);
            $table->string('location', 255);
            $table->enum('medium', ['Bangla', 'English', 'Both']);
            $table->enum('mode', ['Online', 'Offline', 'Both']);
            $table->text('description')->nullable();
            $table->enum('status', ['Open', 'Shortlisting', 'Hired', 'Closed'])->default('Open');
            $table->unsignedTinyInteger('shortlisted_count')->default(0);
            $table->timestamps();

            $table->foreign('guardian_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('job_posts');
    }
};
