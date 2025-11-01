<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('teachers', 'staff_id')) {
            Schema::table('teachers', function (Blueprint $table) {
                $table->string('staff_id')->nullable()->after('id');
            });
        }

        // Copy values from login_id if present
        if (Schema::hasColumn('teachers', 'login_id')) {
            // Use a raw statement to ensure compatibility across drivers
            DB::statement('UPDATE teachers SET staff_id = login_id WHERE login_id IS NOT NULL');

            // Try to drop login_id column. Some drivers (sqlite) may keep an index; drop it first if present.
            try {
                DB::statement('DROP INDEX IF EXISTS teachers_login_id_index');
            } catch (\Exception $e) {
                // ignore if drop index is not supported
            }

            Schema::table('teachers', function (Blueprint $table) {
                if (Schema::hasColumn('teachers', 'login_id')) {
                    $table->dropColumn('login_id');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Recreate login_id if missing and copy back
        if (!Schema::hasColumn('teachers', 'login_id')) {
            Schema::table('teachers', function (Blueprint $table) {
                $table->string('login_id')->nullable()->after('id');
            });
        }

        if (Schema::hasColumn('teachers', 'staff_id')) {
            DB::statement('UPDATE teachers SET login_id = staff_id WHERE staff_id IS NOT NULL');
            Schema::table('teachers', function (Blueprint $table) {
                $table->dropColumn('staff_id');
            });
        }
    }
};
