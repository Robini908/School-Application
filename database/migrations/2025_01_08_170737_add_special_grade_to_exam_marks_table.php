<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSpecialGradeToExamMarksTable extends Migration
{
    public function up()
    {
        Schema::table('exam_marks', function (Blueprint $table) {
            // Add the special_grade column
            $table->string('special_grade', 1)->nullable()->after('marks');
        });
    }

    public function down()
    {
        Schema::table('exam_marks', function (Blueprint $table) {
            // Drop the special_grade column if the migration is rolled back
            $table->dropColumn('special_grade');
        });
    }
}
