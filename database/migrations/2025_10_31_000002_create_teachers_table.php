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
        Schema::create('teachers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('login_id')->nullable()->index();
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('contact')->nullable();
            $table->integer('max_load_per_week')->nullable();
            $table->integer('max_load_per_day')->nullable();
            $table->json('availability')->nullable();
            $table->json('preferences')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teachers');
    }
};
