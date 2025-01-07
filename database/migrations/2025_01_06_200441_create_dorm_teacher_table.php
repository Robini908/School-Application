<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDormTeacherTable extends Migration
{
    public function up()
    {
        Schema::create('dorm_teacher', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('dorm_id');       // Foreign key for the dorm
            $table->unsignedInteger('user_id');       // Foreign key for the teacher (user)
            $table->string('session');                // Session or year
            $table->timestamps();                     // Optional: for tracking when the assignment was made

            // Foreign key constraints
            $table->foreign('dorm_id')->references('id')->on('dorms')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            // Unique constraint to prevent duplicate assignments for the same dorm, teacher, and session
            $table->unique(['dorm_id', 'user_id', 'session']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('dorm_teacher');
    }
}