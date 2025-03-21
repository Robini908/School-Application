<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUserIdToParentDetailsAndStudentRecordsTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Add user_id column to parent_details table
        Schema::table('parent_details', function (Blueprint $table) {
            $table->unsignedInteger('user_id')->nullable()->after('parent_id_no');
            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('set null');  // If the user is deleted, set user_id to null
        });

        // Add user_id column to student_records table
        Schema::table('student_records', function (Blueprint $table) {
            $table->unsignedInteger('user_id')->nullable()->after('id');
            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('set null');  // If the user is deleted, set user_id to null
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Drop foreign key and user_id column from parent_details table
        Schema::table('parent_details', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });

        // Drop foreign key and user_id column from student_records table
        Schema::table('student_records', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });
    }
}