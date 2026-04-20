<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('job_post_responses', function (Blueprint $table) {
            $table->unique(['job_post_id', 'tutor_id'], 'job_post_responses_job_post_id_tutor_id_unique');
        });
    }

    public function down(): void
    {
        Schema::table('job_post_responses', function (Blueprint $table) {
            $table->dropUnique('job_post_responses_job_post_id_tutor_id_unique');
        });
    }
};
