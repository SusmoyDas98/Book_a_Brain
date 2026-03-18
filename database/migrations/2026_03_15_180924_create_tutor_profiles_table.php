<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tutor_profiles', function (Blueprint $table) {

            // tutor_id (FK -> users.id)
            $table->unsignedBigInteger('tutor_id')->primary();

            // profile picture path
            $table->text('profile_picture')->nullable();

            // basic information
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->string('contact_no', 20)->nullable();
            $table->string('gender', 20)->nullable();

            // CV path
            $table->text('cv')->nullable();

            // education & experience
            $table->json('educational_institutions')->nullable();
            $table->json('work_experience')->nullable();

            // teaching related information
            $table->json('teaching_method')->nullable();
            $table->json('availability')->nullable();
            $table->json('preferred_mediums')->nullable();
            $table->json('preferred_subjects')->nullable();
            $table->json('preferred_classes')->nullable();

            // expected salary
            $table->decimal('expected_salary', 10, 2)->nullable();
            // verification
            $table->enum('verification_status', ['PENDING', 'APPROVED', 'REJECTED'])->default('PENDING');
            $table->text('rejection_reason')->nullable();
            $table->timestamp('verified_at')->nullable();

            $table->timestamps();

            // foreign key
            $table->foreign('tutor_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tutor_profiles');
    }
};