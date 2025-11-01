<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('grade_levels', function (Blueprint $table) {
            $table->string('section_naming')->nullable()->after('year')->comment('theme key for section names');
            $table->json('section_naming_options')->nullable()->after('section_naming');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('grade_levels', function (Blueprint $table) {
            $table->dropColumn(['section_naming', 'section_naming_options']);
        });
    }
};
