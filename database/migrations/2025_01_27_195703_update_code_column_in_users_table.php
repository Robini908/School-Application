<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateCodeColumnInUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // Drop the existing unique constraint (if it exists)
            $table->dropUnique('users_code_unique');

            // Modify the 'code' column to be nullable and unique
            $table->string('code', 100)->nullable()->unique()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            // Drop the unique constraint added in the `up` method
            $table->dropUnique('users_code_unique');

            // Revert the column to its original state (not nullable and not unique)
            $table->string('code', 100)->nullable(false)->unique(false)->change();
        });
    }
}