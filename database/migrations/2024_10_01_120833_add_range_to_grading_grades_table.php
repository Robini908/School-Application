<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRangeToGradingGradesTable extends Migration
{
    public function up()
    {
        Schema::table('grading_grades', function (Blueprint $table) {
            // Adding 'range_from' and 'range_to' columns
            $table->integer('range_from')->nullable()->after('additional_info'); // Assuming a whole number
            $table->integer('range_to')->nullable()->after('range_from'); // Assuming a whole number
        });
    }

    public function down()
    {
        Schema::table('grading_grades', function (Blueprint $table) {
            // Dropping the 'range_from' and 'range_to' columns in case of rollback
            $table->dropColumn(['range_from', 'range_to']);
        });
    }
}
