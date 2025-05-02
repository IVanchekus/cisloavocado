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
        Schema::create('training_options', function (Blueprint $table) {
            $table->id();
            $table->string('hash')->unique()->nullable();
            $table->string('name');
            $table->string('description')->nullable();
            $table->boolean('with_solution')->default(false);
            $table->dateTime('deadline')->nullable();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->boolean('is_public')->default(false);
            $table->boolean('is_approved')->default(false);
            $table->foreignId('subject_id')->constrained('subjects')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('training_options');
    }
};
