<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateUserIdOnDeleteBehavior extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Update foreign key constraint in parent_details table
        Schema::table('parent_details', function (Blueprint $table) {
            // Drop the existing foreign key constraint
            $table->dropForeign(['user_id']);

            // Re-add the foreign key with onDelete('cascade')
            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');  // Delete the user when the parent is deleted
        });

        // Update foreign key constraint in student_records table
        Schema::table('student_records', function (Blueprint $table) {
            // Drop the existing foreign key constraint
            $table->dropForeign(['user_id']);

            // Re-add the foreign key with onDelete('cascade')
            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');  // Delete the user when the student is deleted
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Revert the foreign key constraint in parent_details table
        Schema::table('parent_details', function (Blueprint $table) {
            // Drop the cascade foreign key constraint
            $table->dropForeign(['user_id']);

            // Re-add the foreign key with onDelete('set null')
            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('set null');  // Revert to setting user_id to null
        });

        // Revert the foreign key constraint in student_records table
        Schema::table('student_records', function (Blueprint $table) {
            // Drop the cascade foreign key constraint
            $table->dropForeign(['user_id']);

            // Re-add the foreign key with onDelete('set null')
            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('set null');  // Revert to setting user_id to null
        });
    }
}