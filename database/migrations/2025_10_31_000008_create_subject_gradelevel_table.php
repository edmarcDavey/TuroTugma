<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subject_gradelevel', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subject_id')->constrained('subjects')->onDelete('cascade');
            $table->foreignId('grade_level_id')->constrained('grade_levels')->onDelete('cascade');
            $table->timestamps();
            $table->unique(['subject_id','grade_level_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subject_gradelevel');
    }
};
