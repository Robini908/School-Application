<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignKeyToSectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sections', function (Blueprint $table) {
            // Check if the foreign key already exists before adding
            if (!Schema::hasColumn('sections', 'teacher_id')) {
                $table->unsignedInteger('teacher_id')->nullable()->change();
            }

            // Check if the foreign key constraint exists
            $foreignKeyExists = DB::select("
                SELECT COUNT(*) as count 
                FROM information_schema.KEY_COLUMN_USAGE 
                WHERE CONSTRAINT_NAME = 'sections_teacher_id_foreign' 
                AND TABLE_NAME = 'sections'
            ");

            if ($foreignKeyExists[0]->count == 0) {
                // Add foreign key constraint to the teacher_id column
                $table->foreign('teacher_id')
                      ->references('id')->on('users')
                      ->onDelete('set null'); // Optionally set to null if the user is deleted
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sections', function (Blueprint $table) {
            // Drop the foreign key if it exists
            $table->dropForeign(['teacher_id']);
        });
    }
}
