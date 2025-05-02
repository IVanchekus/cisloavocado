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
        Schema::create('exercises', function (Blueprint $table) {
            $table->id();
            $table->json('question');
            $table->string('answer');
            $table->json('solution')->nullable();
            $table->string('img_url')->nullable();
            $table->string('video_url')->nullable();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('category_exercise_id')->nullable()->constrained('category_exercises')->onDelete('cascade');
            $table->foreignId('level_exercise_id')->nullable()->constrained('level_exercises')->onDelete('cascade');
            $table->foreignId('subject_id')->constrained('subjects')->onDelete('cascade');
            $table->boolean('is_ai')->default(false);
            $table->boolean('is_public')->default(false);
            $table->boolean('is_approved')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exercises');
    }
};
