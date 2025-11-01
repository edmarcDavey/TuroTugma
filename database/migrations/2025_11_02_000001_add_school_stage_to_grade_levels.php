<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('grade_levels', function (Blueprint $table) {
            $table->string('school_stage')->nullable()->after('section_naming')->comment('JHS/SHS/Both');
        });
    }

    public function down(): void
    {
        Schema::table('grade_levels', function (Blueprint $table) {
            $table->dropColumn('school_stage');
        });
    }
};
