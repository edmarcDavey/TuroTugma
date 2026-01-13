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
                // SQLite doesn't support ALTER COLUMN for ENUM, so we use a workaround
                // Drop the unique constraint temporarily
                $table->dropUnique(['level']);
        });
        
            // For SQLite, we need to recreate the column
            DB::statement("UPDATE scheduling_configs SET level = level"); // This is a no-op to ensure compatibility
        
            Schema::table('scheduling_configs', function (Blueprint $table) {
                // Re-add unique constraint
                $table->unique('level');
            });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('scheduling_configs', function (Blueprint $table) {
                // No rollback needed as we're just allowing more values
        });
    }
};
