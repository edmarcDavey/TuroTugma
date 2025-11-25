<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('substitutions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('absence_id')->constrained('absences')->onDelete('cascade');
            $table->foreignId('substitute_teacher_id')->constrained('teachers')->onDelete('cascade');
            $table->foreignId('schedule_entry_id')->nullable()->constrained('schedule_entries')->nullOnDelete();
            $table->timestamp('applied_at')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('substitutions');
    }
};
