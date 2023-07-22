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
        Schema::create('exam_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quiz_id')->constrained('quizzes');
            $table->foreignId('question_id')->constrained();
            $table->string('name')->nullable()->default(null);
            $table->text('content');
            $table->json('validation');
            $table->enum('type', ['short-answer', 'long-answer', 'multiple-choice', 'code', 'fill-in-the-gaps'])->default('short-answer');
            $table->json('options')->nullable();
            $table->text('explanation')->nullable()->default(null);
            $table->double('points')->default(0);
            $table->foreignId('user_id')->constrained();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exam_questions');
    }
};
