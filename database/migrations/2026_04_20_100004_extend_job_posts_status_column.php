<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('job_posts', function (Blueprint $table) {
            // Change from enum to string to support new status values:
            // Existing: Open, Shortlisting, Hired, Closed
            // New: Online, Completed, Cancelled
            $table->string('status')->default('Open')->change();
        });
    }

    public function down(): void
    {
        Schema::table('job_posts', function (Blueprint $table) {
            $table->enum('status', ['Open', 'Shortlisting', 'Hired', 'Closed', 'Online', 'Completed', 'Cancelled'])
                ->default('Open')->change();
        });
    }
};
