<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddParentIdForeignKeyToStudentRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('student_records', function (Blueprint $table) {
            $table->foreign('parent_id', 'fks_parent_id_foreign')
                  ->references('parent_id_no')
                  ->on('parent_details')
                  ->onDelete('set null');
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
            $table->dropForeign('fks_parent_id_foreign');
        });
    }
}
