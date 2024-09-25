<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MakeSessionNullableInMyClassesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('my_classes', function (Blueprint $table) {
            // Change the 'session' column to nullable
            $table->string('session')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('my_classes', function (Blueprint $table) {
            // Revert the 'session' column back to not nullable
            $table->string('session')->nullable(false)->change();
        });
    }
}
