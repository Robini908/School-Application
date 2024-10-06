<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameExpulsionFieldsToSuspensionInStudentRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('student_records', function (Blueprint $table) {
            // Rename expulsion fields to suspension fields
            $table->renameColumn('is_expelled', 'is_suspended');
            $table->renameColumn('expulsion_reason', 'suspension_reason');
            $table->renameColumn('expelled_by', 'suspended_by');
            $table->renameColumn('expulsion_date', 'suspension_date');
            $table->renameColumn('expulsion_type', 'suspension_type');
            $table->renameColumn('expulsion_end_date', 'suspension_end_date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('student_records', function (Blueprint $table) {
            // Rename suspension fields back to expulsion fields
            $table->renameColumn('is_suspended', 'is_expelled');
            $table->renameColumn('suspension_reason', 'expulsion_reason');
            $table->renameColumn('suspended_by', 'expelled_by');
            $table->renameColumn('suspension_date', 'expulsion_date');
            $table->renameColumn('suspension_type', 'expulsion_type');
            $table->renameColumn('suspension_end_date', 'expulsion_end_date');
        });
    }
}
