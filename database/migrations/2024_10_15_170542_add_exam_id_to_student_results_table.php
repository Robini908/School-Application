<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddExamIdToStudentResultsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('student_results', function (Blueprint $table) {
            $table->unsignedInteger('exam_id')->after('student_id'); // Adding exam_id after student_id

            // Adding foreign key constraint for exam_id
            $table->foreign('exam_id')
                  ->references('id')->on('exams')
                  ->onDelete('cascade'); // If the exam is deleted, cascade delete
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('student_results', function (Blueprint $table) {
            // Drop foreign key constraint
            $table->dropForeign(['exam_id']);
            
            // Drop the exam_id column
            $table->dropColumn('exam_id');
        });
    }
}
