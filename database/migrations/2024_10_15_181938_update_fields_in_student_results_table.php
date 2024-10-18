<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateFieldsInStudentResultsTable extends Migration
{
    public function up()
    {
        Schema::table('student_results', function (Blueprint $table) {
            // Allow 'subjects' field to accept NULL values
           
            $table->integer('total_marks')->nullable()->change();
            $table->integer('total_points')->nullable()->change();
            $table->float('mean_score')->nullable()->change();
            $table->string('mean_grade')->nullable()->change();
           
        });
    }

    public function down()
    {
        Schema::table('student_results', function (Blueprint $table) {
            // Revert the fields back to non-nullable
           
            $table->integer('total_marks')->nullable(false)->change();
            $table->integer('total_points')->nullable(false)->change();
            $table->float('mean_score')->nullable(false)->change();
            $table->string('mean_grade')->nullable(false)->change();
        });
    }
}
