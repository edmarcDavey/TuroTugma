<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // For SQLite, we need to use CHECK constraint or recreate the table
        // Since we're using SQLite and enum is implemented as CHECK constraint
        // We'll need to recreate the table with the new constraint
        
        // First, get existing data
        $existingData = DB::table('scheduling_configs')->get();
        
        // Drop and recreate the table with updated enum
        Schema::dropIfExists('scheduling_configs');
        
        Schema::create('scheduling_configs', function (Blueprint $table) {
            $table->id();
            $table->enum('level', ['junior_high', 'senior_high', 'general'])->unique();
            $table->unsignedTinyInteger('max_subjects_per_day')->default(8)->comment('Max subjects that can be scheduled per day');
            $table->unsignedTinyInteger('max_periods_per_week')->default(40)->comment('Total periods available per week');
            $table->unsignedTinyInteger('weekly_load_limit')->nullable()->comment('Max teaching loads per week');
            $table->integer('max_consecutive_periods')->default(3);
            $table->integer('max_teaching_days_per_week')->default(5);
            $table->integer('load_distribution_threshold')->default(2)->comment('Max variance in units between teachers');
            $table->string('senior_junior_ratio')->default('equal')->comment('equal, 80-20, or custom ratio');
            $table->json('jhs_constraints')->nullable()->comment('JHS-specific constraints');
            $table->json('shs_constraints')->nullable()->comment('SHS-specific constraints');
            $table->json('optimization_settings')->nullable()->comment('Optimization rules for schedule generation');
            $table->json('faculty_restrictions')->nullable()->comment('Faculty role-based period restrictions');
            $table->boolean('is_locked')->default(false)->comment('Lock schedule to prevent changes');
            $table->json('unit_distribution_rules')->nullable()->comment('Rules for distributing units across week');
            $table->json('jh_config')->nullable()->comment('Junior High specific configuration');
            $table->json('sh_config')->nullable()->comment('Senior High specific configuration');
            $table->json('optimization_weights')->nullable()->comment('Algorithm optimization weights');
            $table->timestamps();
        });
        
        // Restore existing data
        foreach ($existingData as $row) {
            DB::table('scheduling_configs')->insert((array) $row);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Get existing data
        $existingData = DB::table('scheduling_configs')->where('level', '!=', 'general')->get();
        
        // Drop and recreate with old constraint
        Schema::dropIfExists('scheduling_configs');
        
        Schema::create('scheduling_configs', function (Blueprint $table) {
            $table->id();
            $table->enum('level', ['junior_high', 'senior_high'])->unique();
            $table->unsignedTinyInteger('max_subjects_per_day')->default(8);
            $table->unsignedTinyInteger('max_periods_per_week')->default(40);
            $table->unsignedTinyInteger('weekly_load_limit')->nullable();
            $table->integer('max_consecutive_periods')->default(3);
            $table->integer('max_teaching_days_per_week')->default(5);
            $table->integer('load_distribution_threshold')->default(2);
            $table->string('senior_junior_ratio')->default('equal');
            $table->json('jhs_constraints')->nullable();
            $table->json('shs_constraints')->nullable();
            $table->json('optimization_settings')->nullable();
            $table->json('faculty_restrictions')->nullable();
            $table->boolean('is_locked')->default(false);
            $table->json('unit_distribution_rules')->nullable();
            $table->json('jh_config')->nullable();
            $table->json('sh_config')->nullable();
            $table->json('optimization_weights')->nullable();
            $table->timestamps();
        });
        
        // Restore data (excluding 'general' level)
        foreach ($existingData as $row) {
            DB::table('scheduling_configs')->insert((array) $row);
        }
    }
};
