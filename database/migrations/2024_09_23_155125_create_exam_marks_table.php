<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExamMarksTable extends Migration
{
    public function up()
    {
        Schema::create('exam_marks', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->unsignedInteger('student_id'); // Matches student_records
            $table->unsignedInteger('exam_id'); // Matches exams
            $table->unsignedInteger('subject_id'); // Ensure this matches subjects
            $table->unsignedBigInteger('grading_range_id')->nullable(); // Matches grading_ranges
            $table->float('marks')->nullable();
            $table->timestamps();
        });

        // Adding foreign key constraints after the table is created
        Schema::table('exam_marks', function (Blueprint $table) {
            $table->foreign('student_id')->references('id')->on('student_records')->onDelete('cascade');
            $table->foreign('exam_id')->references('id')->on('exams')->onDelete('cascade');
            $table->foreign('subject_id')->references('id')->on('subjects')->onDelete('cascade');
            $table->foreign('grading_range_id')->references('id')->on('grading_ranges')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('exam_marks');
    }
}
