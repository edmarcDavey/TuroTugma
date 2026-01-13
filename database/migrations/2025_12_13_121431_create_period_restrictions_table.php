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
        Schema::create('period_restrictions', function (Blueprint $table) {
            $table->id();
                        $table->foreignId('scheduling_config_id')->constrained('scheduling_configs')->onDelete('cascade');
                        $table->unsignedTinyInteger('day_of_week')->comment('1=Monday, 2=Tuesday, ..., 7=Sunday');
                        $table->unsignedTinyInteger('period_number')->comment('Period number to restrict');
                        $table->enum('restriction_type', ['subject', 'teacher_ancillary'])->comment('Type of restriction');
                        $table->foreignId('subject_id')->nullable()->constrained('subjects')->onDelete('cascade')->comment('Subject that cannot be taught in this period');
                        $table->string('teacher_ancillary')->nullable()->comment('Teacher ancillary type that cannot teach in this period');
            $table->timestamps();
            
                    $table->index(['scheduling_config_id', 'day_of_week', 'period_number']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('period_restrictions');
    }
};
