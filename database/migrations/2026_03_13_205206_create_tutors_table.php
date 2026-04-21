<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tutors', function (Blueprint $table) {

            // tutor_id (FK -> users.id)
            $table->unsignedBigInteger('tutor_id')->primary();

            // Gender
            $table->enum('gender', ['Male', 'Female', 'Not Specified'])->default('Not Specified')->nullable();

            // National ID card image path
            $table->text('nid_card')->nullable();

            // CV file path (PDF)
            $table->text('cv_pdf')->nullable();

            // stores the path of the stored image
            $table->text('student_id_card')->nullable();

            // total earnings
            $table->decimal('total_earning', 10, 2)->default(0);

            // rating value
            $table->float('ratings')->default(0);

            // review text
            $table->text('review')->nullable();

            $table->timestamps();

            // foreign keys
            $table->foreign('tutor_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tutors');
    }
};
