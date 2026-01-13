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
                if (!Schema::hasColumn('scheduling_configs', 'optimization_settings')) {
                    $table->json('optimization_settings')->nullable()->after('shs_constraints')->comment('Optimization rules for schedule generation');
                }
                if (!Schema::hasColumn('scheduling_configs', 'faculty_restrictions')) {
                    $table->json('faculty_restrictions')->nullable()->after('optimization_settings')->comment('Faculty role-based period restrictions');
                }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('scheduling_configs', function (Blueprint $table) {
                $table->dropColumnIfExists('optimization_settings');
                $table->dropColumnIfExists('faculty_restrictions');
        });
    }
};
