<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClassTeacherTable extends Migration
{
    public function up()
    {
        Schema::create('class_teacher', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('my_class_id'); // Foreign key for the class
            $table->unsignedInteger('user_id');     // Foreign key for the teacher (user)
            $table->string('session');                 // Session or year
            $table->timestamps();                      // Optional: for tracking when the assignment was made

            // Foreign key constraints
            $table->foreign('my_class_id')->references('id')->on('my_classes')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            // Unique constraint to prevent duplicate assignments for the same class, teacher, and session
            $table->unique(['my_class_id', 'user_id', 'session']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('class_teacher');
    }
}