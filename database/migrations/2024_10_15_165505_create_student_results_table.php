<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudentResultsTable extends Migration
{
    public function up()
    {
        Schema::create('student_results', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('student_id'); // Make sure this matches the type in student_records
            $table->string('student_name'); // This could be derived from student_records, consider removing it
            $table->json('marks'); // Store marks in JSON format
            $table->json('grades'); // Store grades in JSON format
            $table->json('subjects'); // Store subjects in JSON format
            $table->integer('total_marks');
            $table->integer('total_points');
            $table->float('mean_score');
            $table->string('mean_grade');
            $table->string('stream')->nullable();
            $table->integer('position')->nullable();
            $table->integer('stream_position')->nullable();
            $table->timestamps();

            // Foreign key constraint for student_id
            $table->foreign('student_id')
                  ->references('id') // Assumes id is the primary key in student_records
                  ->on('student_records')
                  ->onDelete('cascade'); // If the student is deleted, cascade delete
        });
    }

    public function down()
    {
        Schema::dropIfExists('student_results');
    }
}
