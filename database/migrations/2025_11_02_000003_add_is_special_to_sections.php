<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsSpecialToSections extends Migration
{
    public function up()
    {
        Schema::table('sections', function (Blueprint $table) {
            $table->boolean('is_special')->default(false)->after('is_active');
        });
    }

    public function down()
    {
        Schema::table('sections', function (Blueprint $table) {
            $table->dropColumn('is_special');
        });
    }
}
