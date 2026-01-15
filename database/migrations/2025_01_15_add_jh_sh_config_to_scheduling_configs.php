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
        Schema::table('scheduling_configs', function (Blueprint $table) {
            // Add JSON columns to store JH and SH configuration snapshots
            $table->json('jh_config')->nullable()->after('level')->comment('Junior High configuration: period duration, breaks, etc.');
            $table->json('sh_config')->nullable()->after('jh_config')->comment('Senior High configuration: period duration, breaks, etc.');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('scheduling_configs', function (Blueprint $table) {
            $table->dropColumn(['jh_config', 'sh_config']);
        });
    }
};
