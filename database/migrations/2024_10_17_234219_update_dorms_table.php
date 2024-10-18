<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateDormsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('dorms', function (Blueprint $table) {
            // Updating or adding the 'name' column
            if (!Schema::hasColumn('dorms', 'name')) {
                $table->string('name')->nullable();
            }

            // Updating or adding the 'capacity' column
            if (!Schema::hasColumn('dorms', 'capacity')) {
                $table->integer('capacity')->nullable();
            }

            // Adding the 'user_id' column if it does not exist
            if (!Schema::hasColumn('dorms', 'user_id')) {
                $table->unsignedBigInteger('user_id')->nullable();
            }

            // Updating or adding the 'session' column
            if (!Schema::hasColumn('dorms', 'session')) {
                $table->string('session')->nullable();
            }

            // Adding foreign key constraint to user_id if it exists
            if (Schema::hasColumn('dorms', 'user_id')) {
                $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
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
        Schema::table('dorms', function (Blueprint $table) {
            // Drop foreign key if exists
            if (Schema::hasColumn('dorms', 'user_id')) {
                $table->dropForeign(['user_id']);
            }

            // Drop columns
            $table->dropColumn(['user_id', 'session']); // Dropping 'session' and 'user_id'
        });
    }
}
