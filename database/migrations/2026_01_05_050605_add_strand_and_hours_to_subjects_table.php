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
        Schema::table('subjects', function (Blueprint $table) {
            $table->foreignId('strand_id')->nullable()->after('type')->constrained('strands')->nullOnDelete();
            $table->unsignedInteger('hours_per_week')->nullable()->after('strand_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('subjects', function (Blueprint $table) {
            $table->dropForeign(['strand_id']);
            $table->dropColumn(['strand_id', 'hours_per_week']);
        });
    }
};
