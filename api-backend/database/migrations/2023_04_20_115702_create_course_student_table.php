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
        //TODO : Changer le nom du fichier ?? C'est pas le bon nom dans le projet de base
        Schema::create('roster_student', function (Blueprint $table) {
            $table->foreignId('roster_id')->constrained();
            $table->foreignId('student_id')->constrained();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student');
        Schema::dropIfExists('class_student');
    }
};
