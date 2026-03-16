<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('verification_documents', function (Blueprint $table) {
            // Drop the old foreign key pointing to tutors
            $table->dropForeign(['tutor_id']);

            // Add new foreign key pointing to users
            $table->foreign('tutor_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('verification_documents', function (Blueprint $table) {
            $table->dropForeign(['tutor_id']);
            $table->foreign('tutor_id')
                  ->references('tutor_id')
                  ->on('tutors')
                  ->onDelete('cascade');
        });
    }
};