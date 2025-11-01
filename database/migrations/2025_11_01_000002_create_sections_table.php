<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('sections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('grade_level_id')->constrained('grade_levels')->onDelete('restrict');
            $table->string('name');
            $table->string('code')->nullable();
            $table->unsignedSmallInteger('ordinal')->nullable();
            $table->foreignId('advisor_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->unsignedSmallInteger('capacity')->nullable();
            $table->string('school_year')->nullable();
            $table->boolean('is_active')->default(true);
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->unique(['grade_level_id','name']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('sections');
    }
};
