<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tutor_profiles', function (Blueprint $table) {
            if (!Schema::hasColumn('tutor_profiles', 'gender')) {
                $table->string('gender', 20)->nullable();
            }
            if (!Schema::hasColumn('tutor_profiles', 'profile_picture')) {
                $table->text('profile_picture')->nullable();
            }
        });

        Schema::table('guardians', function (Blueprint $table) {
            if (!Schema::hasColumn('guardians', 'gender')) {
                $table->string('gender', 20)->nullable();
            }
            if (!Schema::hasColumn('guardians', 'profile_picture')) {
                $table->text('profile_picture')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('tutor_profiles', function (Blueprint $table) {
            $table->dropColumn(['gender', 'profile_picture']);
        });
        Schema::table('guardians', function (Blueprint $table) {
            $table->dropColumn(['gender', 'profile_picture']);
        });
    }
};