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
        Schema::create('activities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quiz_id')->constrained('quizzes');
            $table->foreignId('roster_id')->constrained();
            $table->foreignId('user_id')->constrained();
            $table->integer('duration')->comment('In seconds');
            $table->boolean('shuffle_questions')->default(false);
            $table->boolean('shuffle_propositions')->default(false);
            $table->bigInteger('seed')->default(0)->comment('For random generator');
            $table->boolean('hidden')->default(false);

            $table->timestamp('started_at')->nullable()->comment('Activity start time');
            $table->timestamp('opened_at')->nullable()->comment('When the activity was called to be joined');
            $table->timestamp('ended_at')->nullable()->comment('When the activity was finished');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activities');
    }
};
