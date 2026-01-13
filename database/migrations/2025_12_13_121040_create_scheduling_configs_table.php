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
        Schema::create('scheduling_configs', function (Blueprint $table) {
            $table->id();
                        $table->enum('level', ['junior_high', 'senior_high'])->unique();
                        $table->unsignedTinyInteger('max_subjects_per_day')->default(8)->comment('Max subjects that can be scheduled per day');
                        $table->unsignedTinyInteger('max_periods_per_week')->default(40)->comment('Total periods available per week');
                        $table->unsignedTinyInteger('weekly_load_limit')->nullable()->comment('Max teaching loads per week');
                        $table->boolean('is_locked')->default(false)->comment('Lock schedule to prevent changes');
                        $table->json('unit_distribution_rules')->nullable()->comment('Rules for distributing units across week');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('scheduling_configs');
    }
};
