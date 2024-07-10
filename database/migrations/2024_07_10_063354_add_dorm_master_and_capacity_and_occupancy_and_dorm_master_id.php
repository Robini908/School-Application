<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDormMasterAndCapacityAndOccupancyAndDormMasterId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('dorms', function (Blueprint $table) {
            $table->string('dorm_master')->nullable();
            $table->unsignedInteger('capacity')->nullable();
            $table->unsignedInteger('occupancy')->nullable();
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
            $table->dropColumn(['dorm_master', 'capacity', 'occupancy']);
        });
    }
}
