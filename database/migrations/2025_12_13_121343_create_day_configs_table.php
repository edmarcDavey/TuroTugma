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
        Schema::create('day_configs', function (Blueprint $table) {
            $table->id();
                        $table->foreignId('scheduling_config_id')->constrained('scheduling_configs')->onDelete('cascade');
                        $table->unsignedTinyInteger('day_of_week')->comment('1=Monday, 2=Tuesday, ..., 7=Sunday');
                        $table->boolean('is_active')->default(true)->comment('Whether this day has classes');
                        $table->enum('session_type', ['regular', 'shortened'])->default('regular');
                        $table->unsignedTinyInteger('period_count')->default(8)->comment('Number of periods for this day');
                        $table->time('start_time')->nullable();
                        $table->time('end_time')->nullable();
                        $table->unsignedTinyInteger('period_duration')->default(50)->comment('Duration in minutes');
                        $table->json('breaks')->nullable()->comment('Break configuration: {morning: {enabled, duration, after_period}, lunch: {...}, afternoon: {...}}');
                        $table->json('manual_period_times')->nullable()->comment('Manual override for specific period times');
            $table->timestamps();
            
                    $table->unique(['scheduling_config_id', 'day_of_week']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('day_configs');
    }
};
