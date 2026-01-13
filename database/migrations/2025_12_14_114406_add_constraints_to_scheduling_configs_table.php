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
            if (!Schema::hasColumn('scheduling_configs', 'max_consecutive_periods')) {
                $table->integer('max_consecutive_periods')->default(3)->after('weekly_load_limit');
            }
            if (!Schema::hasColumn('scheduling_configs', 'max_teaching_days_per_week')) {
                $table->integer('max_teaching_days_per_week')->default(5)->after('max_consecutive_periods');
            }
            if (!Schema::hasColumn('scheduling_configs', 'load_distribution_threshold')) {
                $table->integer('load_distribution_threshold')->default(2)->after('max_teaching_days_per_week')->comment('Max variance in units between teachers');
            }
            if (!Schema::hasColumn('scheduling_configs', 'senior_junior_ratio')) {
                $table->string('senior_junior_ratio')->default('equal')->after('load_distribution_threshold')->comment('equal, 80-20, or custom ratio');
            }
            if (!Schema::hasColumn('scheduling_configs', 'jhs_constraints')) {
                $table->json('jhs_constraints')->nullable()->after('senior_junior_ratio')->comment('JHS-specific constraints');
            }
            if (!Schema::hasColumn('scheduling_configs', 'shs_constraints')) {
                $table->json('shs_constraints')->nullable()->after('jhs_constraints')->comment('SHS-specific constraints');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('scheduling_configs', function (Blueprint $table) {
            $table->dropColumnIfExists('max_consecutive_periods');
            $table->dropColumnIfExists('max_teaching_days_per_week');
            $table->dropColumnIfExists('load_distribution_threshold');
            $table->dropColumnIfExists('senior_junior_ratio');
            $table->dropColumnIfExists('jhs_constraints');
            $table->dropColumnIfExists('shs_constraints');
        });
    }
};
