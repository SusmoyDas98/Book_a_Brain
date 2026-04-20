<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tutor_profiles', function (Blueprint $table) {
            $table->decimal('trust_score', 5, 2)->default(0)->after('expected_salary');
            $table->timestamp('trust_score_updated_at')->nullable()->after('trust_score');
        });
    }

    public function down(): void
    {
        Schema::table('tutor_profiles', function (Blueprint $table) {
            $table->dropColumn(['trust_score', 'trust_score_updated_at']);
        });
    }
};
