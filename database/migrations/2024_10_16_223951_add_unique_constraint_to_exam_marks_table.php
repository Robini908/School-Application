<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUniqueConstraintToExamMarksTable extends Migration
{
    public function up()
    {
        Schema::table('exam_marks', function (Blueprint $table) {
            // Add unique constraint to student_id, exam_id, and subject_id
            $table->unique(['student_id', 'exam_id', 'subject_id']);
        });
    }

    public function down()
    {
        Schema::table('exam_marks', function (Blueprint $table) {
            // Remove unique constraint
            $table->dropUnique(['student_id', 'exam_id', 'subject_id']);
        });
    }
}
