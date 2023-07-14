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
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable()->default(null);
            $table->text('content'); // Markdown

            // ['A', 'B'] for multiple answers
            // {'pattern' : '/regex/'}
            // {'contains' : 'string'}
            // {'expression' : '$_ > 23 && $_ < 42'}
            $table->json('validation');
            $table->enum('type', ['short-answer', 'multiple-choice', 'code', 'fill-in-the-gaps'])->default('short-answer');
            $table->json('options')->nullable();
            $table->enum('difficulty', ['easy', 'medium', 'hard', 'insane'])->default('easy');
            $table->text('explanation')->nullable()->default(null); // Markdown
            $table->tinyInteger('is_public')->default(true);
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
        Schema::dropIfExists('questions');
    }
};
