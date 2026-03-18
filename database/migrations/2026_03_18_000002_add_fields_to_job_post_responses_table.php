<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('job_post_responses', function (Blueprint $table) {
            $table->unsignedBigInteger('job_post_id')->nullable()->after('id');
            $table->text('application_message')->nullable()->after('job_post_id');
            $table->string('status')->default('Pending')->after('application_message');

            $table->foreign('job_post_id')->references('id')->on('job_posts')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('job_post_responses', function (Blueprint $table) {
            $table->dropForeign(['job_post_id']);
            $table->dropColumn(['job_post_id', 'application_message', 'status']);
        });
    }
};
