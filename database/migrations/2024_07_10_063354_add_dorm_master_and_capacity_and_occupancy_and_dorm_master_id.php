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
            $table->unsignedInteger('dorm_master_id'); 
            $table->string('dorm_master');
            $table->unsignedInteger('capacity');
            $table->unsignedInteger('occupancy');
            $table->foreign('dorm_master_id')->references('id')->on('dorm_masters')->onDelete('cascade');
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
            $table->dropColumn(['dorm_master_id', 'dorm_master', 'capacity', 'occupancy']);
        });
    }
}
