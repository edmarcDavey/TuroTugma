<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('scheduling_configs', function (Blueprint $table) {
            $table->json('subject_constraints')->nullable()->after('faculty_restrictions')->comment('Subject period restrictions');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('scheduling_configs', function (Blueprint $table) {
            $table->dropColumnIfExists('subject_constraints');
        });
    }
};
