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
        Schema::table('teachers', function (Blueprint $table) {
            if (!Schema::hasColumn('teachers', 'sex')) {
                $table->string('sex')->nullable()->after('name');
            }
            if (!Schema::hasColumn('teachers', 'designation')) {
                $table->string('designation')->nullable()->after('sex');
            }
            if (!Schema::hasColumn('teachers', 'status_of_appointment')) {
                $table->string('status_of_appointment')->nullable()->after('designation');
            }
            if (!Schema::hasColumn('teachers', 'course_degree')) {
                $table->string('course_degree')->nullable()->after('status_of_appointment');
            }
            if (!Schema::hasColumn('teachers', 'ancillary_assignments')) {
                $table->text('ancillary_assignments')->nullable()->after('course_degree');
            }
            if (!Schema::hasColumn('teachers', 'course_major')) {
                $table->string('course_major')->nullable()->after('ancillary_assignments');
            }
            if (!Schema::hasColumn('teachers', 'course_minor')) {
                $table->string('course_minor')->nullable()->after('course_major');
            }
            if (!Schema::hasColumn('teachers', 'number_handled_per_week')) {
                $table->integer('number_handled_per_week')->nullable()->after('course_minor');
            }
            if (!Schema::hasColumn('teachers', 'advisory')) {
                $table->string('advisory')->nullable()->after('number_handled_per_week');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('teachers', function (Blueprint $table) {
            if (Schema::hasColumn('teachers', 'advisory')) {
                $table->dropColumn('advisory');
            }
            if (Schema::hasColumn('teachers', 'number_handled_per_week')) {
                $table->dropColumn('number_handled_per_week');
            }
            if (Schema::hasColumn('teachers', 'course_minor')) {
                $table->dropColumn('course_minor');
            }
            if (Schema::hasColumn('teachers', 'course_major')) {
                $table->dropColumn('course_major');
            }
            if (Schema::hasColumn('teachers', 'ancillary_assignments')) {
                $table->dropColumn('ancillary_assignments');
            }
            if (Schema::hasColumn('teachers', 'course_degree')) {
                $table->dropColumn('course_degree');
            }
            if (Schema::hasColumn('teachers', 'status_of_appointment')) {
                $table->dropColumn('status_of_appointment');
            }
            if (Schema::hasColumn('teachers', 'designation')) {
                $table->dropColumn('designation');
            }
            if (Schema::hasColumn('teachers', 'sex')) {
                $table->dropColumn('sex');
            }
        });
    }
};
