<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMasterIdToMyClassesTable extends Migration
{
    public function up()
    {
        Schema::table('my_classes', function (Blueprint $table) {
            // Adding master_id as an unsigned big integer
            $table->unsignedInteger('master_id')->nullable() // Ensure it's an unsigned big integer
                ->index() // Optional: index for better query performance
                ->after('teacher_id'); // Optional: specify position if needed

            // Adding foreign key constraint
            $table->foreign('master_id')->references('id')->on('users')
                ->onDelete('set null'); // Set to null if the user is deleted
        });
    }

    public function down()
    {
        Schema::table('my_classes', function (Blueprint $table) {
            $table->dropForeign(['master_id']); // Drop foreign key constraint
            $table->dropColumn('master_id'); // Drop the master_id column
        });
    }
}
