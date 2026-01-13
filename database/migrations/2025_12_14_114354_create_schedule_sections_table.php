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
        Schema::create('schedule_sections', function (Blueprint $table) {
            $table->id();
            $table->enum('level', ['junior_high', 'senior_high']);
            $table->string('name')->comment('e.g., 7A, 11-STEM-A');
            $table->integer('student_count')->default(40);
            $table->string('track')->nullable()->comment('For SHS: STEM, ABM, HUMSS, etc');
            $table->integer('grade_level')->comment('7-10 for JHS, 11-12 for SHS');
            $table->json('required_subjects')->comment('Subject IDs required for this section');
            $table->timestamps();
            $table->unique(['level', 'name', 'grade_level']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schedule_sections');
    }
};
