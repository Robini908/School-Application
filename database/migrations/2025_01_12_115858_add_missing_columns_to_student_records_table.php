<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMissingColumnsToStudentRecordsTable extends Migration
{
    /**
     * Run the migration.
     */
    public function up()
    {
        Schema::table('student_records', function (Blueprint $table) {
            // Add 'kcpe' column if it doesn't exist
            if (!Schema::hasColumn('student_records', 'kcpe')) {
                $table->integer('kcpe')->nullable()->after('year_admitted');
            }

            // Add 'nationality' column if it doesn't exist
            if (!Schema::hasColumn('student_records', 'nationality')) {
                $table->string('nationality')->nullable()->after('dob');
            }

            // Add 'state' column if it doesn't exist
            if (!Schema::hasColumn('student_records', 'state')) {
                $table->string('state')->nullable()->after('nationality');
            }

            // Add 'town' column if it doesn't exist
            if (!Schema::hasColumn('student_records', 'town')) {
                $table->string('town')->nullable()->after('state');
            }

            // Add 'upi_number' column if it doesn't exist
            if (!Schema::hasColumn('student_records', 'upi_number')) {
                $table->string('upi_number')->unique()->nullable()->after('town');
            }
        });
    }

    /**
     * Reverse the migration.
     */
    public function down()
    {
        Schema::table('student_records', function (Blueprint $table) {
            // Drop the columns if they exist
            $table->dropColumn(['kcpe', 'nationality', 'state', 'town', 'upi_number']);
        });
    }
}