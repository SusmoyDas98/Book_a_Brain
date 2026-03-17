<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('job_post_responses', function (Blueprint $table) {
            $table->id();

            // Guardian reference (just integer for now)
            $table->unsignedBigInteger('guardian_id');

            // Tutor reference
            $table->unsignedBigInteger('tutor_id');

            // Snapshot of tutor profile data
            $table->text('tutor_profile_pic')->nullable();
            $table->string('tutor_name')->nullable();
            $table->string('gender')->nullable();
            $table->string('cv')->nullable();
            $table->json('tutor_educational_institutions')->nullable();
            $table->json('tutor_work_experience')->nullable();
            $table->json('teaching_method')->nullable();
            $table->json('availability')->nullable();
            $table->json('preferred_mediums')->nullable();
            $table->json('preferred_subjects')->nullable();
            $table->json('preferred_classes')->nullable();
            $table->decimal('expected_salary', 10, 2)->nullable();

            // Tutor rating from tutors table
            $table->float('tutor_rating')->nullable();

            // Shortlist status
            $table->boolean('shortlisted')->default(false);

            $table->timestamps();

            // Foreign key: ensures tutor exists
            $table->foreign('tutor_id')
                  ->references('tutor_id')
                  ->on('tutor_profiles')
                  ->onDelete('cascade');

            // Guardian FK can be added later when the table exists
            // $table->foreign('guardian_id')->references('id')->on('guardians')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('job_post_responses');
    }
};