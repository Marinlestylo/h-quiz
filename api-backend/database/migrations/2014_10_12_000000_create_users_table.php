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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->integer('unique_id')->comment('Switch AAI unique ID');
            $table->string('firstname');
            $table->string('lastname');
            $table->string('email')->unique();
            $table->string('keycloak_id')->nullable();
            $table->tinyInteger('gender');
            $table->string('affiliation')->comment('Usually `member;staff` or `member;student`');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
