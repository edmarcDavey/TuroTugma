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
        Schema::create('teacher_qualifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('teacher_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('subject_id')->constrained('subjects')->onDelete('cascade');
            $table->enum('level', ['junior_high', 'senior_high', 'both'])->default('both');
            $table->enum('proficiency', ['primary_expert', 'capable', 'can_assist'])->default('capable');
            $table->enum('seniority', ['senior', 'mid_level', 'junior'])->default('mid_level');
            $table->json('tracks')->nullable()->comment('SHS tracks: STEM, ABM, HUMSS, etc');
            $table->timestamps();
            $table->unique(['teacher_id', 'subject_id', 'level']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teacher_qualifications');
    }
};
